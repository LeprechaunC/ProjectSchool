<template>
  <div>
    <h1>Your Teams</h1>


    <div v-if="loading" class="loader">Loading...</div>

    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="teams.length === 0 && !loading">No teams found.</div>

    <div v-for="team in teams" :key="team.id" class="team">
      <h2>{{ team.name }}</h2>
 
      <h3>Members:</h3>
      <ul>
        <li v-for="user in team.users" :key="user.id">
          <span>{{ user.name }}</span>
          <button v-if="team.pivot.role === 'admin' && user.id !== currentUser.id" @click="votekick(team.id, user.id)" class="kick-btn">
            <i class="fa-solid fa-ban"></i>
          </button>
        </li>
      </ul>
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
      currentUser: { id: 4 } // Simulated logged-in user ID, replace with actual authentication logic
    };
  },

  mounted() {
    this.fetchTeams();
  },
  methods: {
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
    async votekick(teamId, userId) {
      if (!confirm('Are you sure you want to votekick this user?')) return;
      try {
        await axios.post(`/api/teams/${teamId}/votekick`, { userId });
        this.teams = this.teams.map(team => {
          if (team.id === teamId) {
            team.users = team.users.filter(user => user.id !== userId);
          }
          return team;
        });
      } catch (err) {
        this.error = 'Failed to votekick user';
      }
    }
  }
};
</script>

<style scoped>
/* General styling for the page */
div {
  font-family: 'Arial', sans-serif;
  max-width: 1000px; /* Increase the width to give more breathing room */
  margin: 0 auto;
  padding: 20px;
}

h1 {
  font-weight: 700;
  font-size: 2.2rem; /* Slightly larger font size for the heading */
  text-align: center;
  margin-bottom: 30px; /* Increase space below the heading */
  color: #333;
}

/* Loader styling */
.loader {
  text-align: center;
  font-size: 20px;
  font-weight: bold;
  color: #555;
  margin-top: 20px;
}

/* Error message styling */
.error {
  color: #d9534f;
  background-color: #f8d7da;
  padding: 10px;
  text-align: center;
  border-radius: 5px;
  margin-top: 20px;
  font-weight: bold;
}

/* Teams container */
.team {
  margin-bottom: 40px; /* Increased margin for better separation */
  padding: 2rem;
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Team heading styling */
.team h2 {
  font-size: 1.8rem; /* Slightly larger font size for team name */
  margin-bottom: 15px; /* Added margin for separation */
  color: #333;
}

.team .admin-label {
  font-size: 1rem;
  color: #5bc0de;
  margin-top: 10px;
  font-weight: bold;
}

/* Members section heading */
h3 {
  font-size: 1.4rem;
  color: #555;
  margin-bottom: 10px;
}

/* List styling for members */
ul {
  padding-left: 0;
  margin: 0;
}

ul li {
  display: flex;
  justify-content: space-between;
  list-style-type: none;
  background: #f9f9f9; /* Lighter background for list items */
  margin: 10px 0; /* Increased gap between list items */
  padding: 12px;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Light shadow for list items */
}

ul li:hover {
  background: #f1f1f1;
}

ul li span {
  flex-grow: 1;
  font-size: 1.1rem;
  color: #333;
}

.user-role {
  font-style: italic;
  font-size: 1rem;
  color: #777;
  margin-left: 10px;
}

/* Button styling */
.kick-btn {
  margin-left: 10px;
  background-color: #d9534f;
  color: white;
  border: none;
  padding: 8px 12px; /* Larger padding for the button */
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.kick-btn:hover {
  background-color: #c9302c;
  transform: scale(1.05); /* Slightly enlarge button on hover */
}

.kick-btn:focus {
  outline: none; /* Remove focus outline */
}

.kick-btn:active {
  background-color: #c12e1b; /* Darker shade on button press */
}
</style>
