<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 p-6">
    <div class="max-w-6xl mx-auto">
      <!-- Header Section -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Teams Dashboard</h2>
        <p class="text-gray-600 dark:text-gray-400">Manage your teams and collaborate with others</p>
      </div>

      <!-- Forms Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <!-- Create Team Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
          <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-3">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create a New Team</h3>
          </div>
          <form @submit.prevent="createTeam" class="space-y-4">
            <div>
              <label for="team-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Team Name</label>
              <input 
                id="team-name"
                type="text" 
                v-model="newTeamName" 
                placeholder="Enter team name" 
                required 
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-colors"
              />
            </div>
            <button 
              type="submit" 
              class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center shadow-sm hover:shadow-md"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Create Team
            </button>
        </form>
      </div>

        <!-- Join Team Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
          <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mr-3">
              <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Join a Team</h3>
          </div>
          <form @submit.prevent="joinTeam" class="space-y-4">
            <div>
              <label for="invite-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invite Code</label>
              <input 
                id="invite-code"
                type="text" 
                v-model="enteredInviteCode" 
                placeholder="Enter invite code" 
                required 
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500 transition-colors"
              />
            </div>
            <button 
              type="submit" 
              class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center shadow-sm hover:shadow-md"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
              Join Team
            </button>
        </form>
      </div>
    </div>

      <!-- Teams Section -->
      <div class="mb-6">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Your Teams</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your teams and team members</p>
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ teams.length }} {{ teams.length === 1 ? 'team' : 'teams' }}
          </div>
        </div>
        
        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center h-40">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>
        
        <!-- Error State -->
        <div v-if="error" class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 p-4 rounded-lg mb-6 border border-red-200 dark:border-red-800">
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ error }}
          </div>
        </div>
        
        <!-- No Teams State -->
        <div v-if="teams.length === 0 && !loading" class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 text-center border border-gray-100 dark:border-gray-700">
          <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No teams found</h3>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Create a new team or join an existing one to get started.</p>
          <div class="mt-6 flex justify-center space-x-3">
            <button 
              @click="scrollToCreateForm" 
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Create Team
              </button>
            <button 
              @click="scrollToJoinForm" 
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
              Join Team
            </button>
          </div>
        </div>

        <!-- Teams List -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="team in teams" 
            :key="team.id" 
            class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300"
          >
            <!-- Team Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-700 dark:to-blue-800 px-6 py-4">
              <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold text-white">{{ team.name }}</h4>
                <span class="text-xs bg-white/20 text-white px-2 py-1 rounded-full">
                  {{ team.users.length }} {{ team.users.length === 1 ? 'member' : 'members' }}
                </span>
              </div>
            </div>
            
            <!-- Team Body -->
            <div class="p-6">
              <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Members
              </h5>
              
              <div class="space-y-3 mb-6 max-h-48 overflow-y-auto pr-2">
                <div 
                  v-for="user in team.users" 
                  :key="user.id" 
                  class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0"
                >
                  <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-3">
                      <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        {{ user.name.charAt(0) }}
                      </span>
                    </div>
                    <div>
                      <span class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ user.name }} 
                        <span v-if="user.id === currentUser.id" class="text-xs text-blue-600 dark:text-blue-400">(You)</span>
                      </span>
                      <span class="block text-xs text-gray-500 dark:text-gray-400">{{ user.role }}</span>
                    </div>
                  </div>
                  
                  <button 
                    v-if="team.pivot.role === 'admin' && user.id !== currentUser.id"
                    @click="removeMember(team.id, user.id)" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                    title="Remove member"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Invite Section -->
              <div class="space-y-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                  <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    Invite Code
                  </h5>
                  <div class="flex items-center space-x-2">
                    <button 
                      @click="toggleInviteCode(team.id)" 
                      class="text-sm px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-md hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors"
                    >
                      {{ visibleInviteCode === team.id ? 'Hide' : 'Show' }}
                    </button>
                    <button 
                      @click="generateNewInviteCode(team.id)" 
                      class="text-sm px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                    >
                      Generate New
                    </button>
                  </div>
                </div>
                
                <div v-if="visibleInviteCode === team.id" class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                  <div class="flex items-center justify-between">
                    <code class="text-sm font-mono text-gray-800 dark:text-gray-200">{{ team.invite_code }}</code>
                    <button 
                      @click="copyInviteCode(team.invite_code)" 
                      class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                      title="Copy to clipboard"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Add this inside the team card, after the invite section -->
              <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                <button 
                  @click="openChat(team)" 
                  class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  Open Chat
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add this at the end of the template, before the closing </div> -->
    <Modal :show="showChat" @close="closeChat">
      <div class="h-[600px] w-[800px]">
        <TeamChat 
          v-if="selectedTeamForChat"
          :team="selectedTeamForChat"
          @close="closeChat"
        />
      </div>
    </Modal>
  </div>
</template>

<script>
import axios from 'axios';
import TeamChat from '../Goal/TeamChat.vue';

export default {
  components: {
    TeamChat
  },
  data() {
    return {
      teams: [],
      loading: true,
      error: null,
      currentUser: null,
      newTeamName: '',
      enteredInviteCode: '',
      visibleInviteCode: null,
      showChat: false,
      selectedTeamForChat: null,
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
      if (!confirm('Are you sure you want to remove this member from the team?')) return;
      
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
    },
    
    copyInviteCode(code) {
      navigator.clipboard.writeText(code)
        .then(() => {
          // You could add a toast notification here
          console.log('Invite code copied to clipboard');
        })
        .catch(err => {
          console.error('Failed to copy invite code', err);
        });
    },
    
    scrollToCreateForm() {
      const createForm = document.getElementById('team-name');
      if (createForm) {
        createForm.scrollIntoView({ behavior: 'smooth' });
        createForm.focus();
      }
    },
    
    scrollToJoinForm() {
      const joinForm = document.getElementById('invite-code');
      if (joinForm) {
        joinForm.scrollIntoView({ behavior: 'smooth' });
        joinForm.focus();
      }
    },

    openChat(team) {
      this.selectedTeamForChat = team;
      this.showChat = true;
    },

    closeChat() {
      this.showChat = false;
      this.selectedTeamForChat = null;
    },
  }
};
</script>

<style scoped>
/* Custom scrollbar for the teams list */
.overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Dark mode scrollbar */
.dark .overflow-y-auto::-webkit-scrollbar-track {
  background: #374151;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
  background: #4b5563;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}

/* Card hover effects */
.team-card {
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.team-card:hover {
  transform: translateY(-2px);
}
</style>
