<template>
  <div class="container">
 

    <div class="forms-container">
      <div class="form-box">
        <h2>Create a New Team</h2>
        <form @submit.prevent="createTeam">
          <input type="text" v-model="newTeamName" placeholder="Enter team name" required />
          <button type="submit" class="btn create-btn">Create Team</button>
        </form>
      </div>

      <div class="form-box">
        <h2>Join a Team</h2>
        <form @submit.prevent="joinTeam">
          <input type="text" v-model="enteredInviteCode" placeholder="Enter invite code" required />
          <button type="submit" class="btn join-btn">Join Team</button>
        </form>
      </div>
    </div>
    <h1>Your Teams</h1>
    <div v-if="loading" class="loader">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="teams.length === 0 && !loading" class="no-teams">No teams found.</div>

    <div class="teams-container">
      <div v-for="team in teams" :key="team.id" class="team-card">
        <div class="team-header">
          <h2>{{ team.name }}</h2>
        </div>
        <div class="team-body">
          <h3>Members</h3>
          <ul>
            <li v-for="user in team.users" :key="user.id">
              <span>{{ user.name }} <span v-if="user.id === currentUser.id">(You)</span></span>
              <span class="role">{{ user.role }}</span> <!-- Role on the right -->
              <button v-if="team.pivot.role === 'admin' && user.id !== currentUser.id"
                @click="removeMember(team.id, user.id)" class="kick-btn">
                Remove
              </button>
            </li>
          </ul>

          <div class="invite-section">
            <button @click="toggleInviteCode(team.id)" class="btn invite-btn">
              {{ visibleInviteCode === team.id ? `${team.invite_code}` : 'Show Invite Code' }}
            </button>
            <button @click="generateNewInviteCode(team.id)" class="btn generate-btn">Generate New Invite</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  data() {
    return {
      teams: [],
      loading: true,
      error: null,
      currentUser: null,
      newTeamName: '',
      enteredInviteCode: '',
      visibleInviteCode: null,
    };
  },

  mounted() {
    this.fetchCurrentUser();
    this.fetchTeams();
  },

  methods: {
    async fetchCurrentUser() {
      try {
        const response = await axios.get('/api/user');
        this.currentUser = response.data;
      } catch (err) {
        this.error = 'Failed to load user data';
      }
    },

    async fetchTeams() {
      try {
        const response = await axios.get('/api/teams');
        this.teams = response.data;
      } catch (err) {
        this.error = 'Failed to load teams';
      } finally {
        this.loading = false;
      }
    },

    async createTeam() {
      try {
        const response = await axios.post('/api/teams', { name: this.newTeamName });
        this.teams.push(response.data.team);
        this.newTeamName = '';
      } catch (err) {
        this.error = 'Failed to create team';
      }
    },

    async joinTeam() {
      try {
        const response = await axios.post('/api/teams/join', { invite_code: this.enteredInviteCode });
        this.teams.push(response.data.team);
        this.enteredInviteCode = '';
      } catch (err) {
        this.error = 'Failed to join team';
      }
    },

    async removeMember(teamId, userId) {
      try {
        await axios.delete(`/api/teams/${teamId}/users/${userId}`);
        const team = this.teams.find(t => t.id === teamId);
        team.users = team.users.filter(u => u.id !== userId);
      } catch (err) {
        this.error = 'Failed to remove the member';
      }
    },

    toggleInviteCode(teamId) {
      this.visibleInviteCode = this.visibleInviteCode === teamId ? null : teamId;
    },

    async generateNewInviteCode(teamId) {
      try {
        const response = await axios.post(`/api/teams/${teamId}/invite`);
        const team = this.teams.find(t => t.id === teamId);
        if (team) team.invite_code = response.data.invite_code;
      } catch (err) {
        this.error = 'Failed to generate new invite code';
      }
    }
  }
};
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: auto;
  text-align: center;
  padding: 20px;

}

.forms-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 15%;
}

.form-box {
  flex: 1;
  padding: 20px;
  background: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

input {
  width: 100%;
  padding: 10px;
  margin-top: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.btn {
  margin-top: 10px;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.create-btn {
  background: #242323;
  color: white;
}

.join-btn {
  background: #007bff;
  color: white;
}
.invite-btn, .generate-btn {
  min-width: 150px;   
}
.invite-btn {
  background: #17a2b8;
  color: white;
}

.generate-btn {
  background: #ffc107;
  color: black;
}

.teams-container {
  display: flex;
  flex-direction: column;
  /* Stack teams vertically */
  gap: 20px;
  /* Space between teams */
  margin-top: 30px;
  align-items: center;
  /* Optional: Center teams */
}

.team-card {
  width: 320px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.team-body {
  padding-bottom: 50px;
}

.team-body ul {
  list-style-type: none;
  padding: 0;
}

.team-body li {
  display: flex;
  justify-content: space-between;
  /* Align name and role */
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #ddd;
  /* Optional: Add a border between members */
}

.team-body li span {
  flex: 1;
  /* Username takes up available space */
}

.team-body li .role {
  font-size: 14px;
  /* Smaller font for role */
  color: #555;
  /* Optional: Lighter color for role */
}

.invite-section {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  /* Space between invite buttons */
  margin-top: 15px;
}

.kick-btn {
  background: #dc3545;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

.loader {
  font-size: 18px;
  color: #999;
}

.error {
  color: #dc3545;
  font-size: 16px;
}

.no-teams {
  font-size: 18px;
  color: #666;
}
</style>
