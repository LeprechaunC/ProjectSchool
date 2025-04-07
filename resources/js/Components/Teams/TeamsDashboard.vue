<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 p-6">
    <div class="max-w-6xl mx-auto">
      <!-- Notification -->
      <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="notification.show" 
          class="fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50"
          :class="{
            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': notification.type === 'success',
            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': notification.type === 'error'
          }"
        >
          <div class="flex items-center">
            <span class="mr-2">
              <svg v-if="notification.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </span>
            <span>{{ notification.message }}</span>
          </div>
        </div>
      </Transition>

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
                <div class="flex items-center space-x-2">
                  <h4 class="text-lg font-semibold text-white">{{ team.name }}</h4>
                  <button 
                    v-if="isTeamAdmin(team)"
                    @click="openEditNameModal(team)" 
                    class="text-white/80 hover:text-white transition-colors"
                    title="Edit team name"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                  </button>
                </div>
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
              
              <div class="space-y-3 mb-6 h-[200px] overflow-y-auto pr-2">
                <div 
                  v-for="user in paginatedMembers(team)" 
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
                      <span class="block text-xs text-gray-500 dark:text-gray-400">{{ user.pivot.role }}</span>
                    </div>
                  </div>
                  
                  <!-- Admin Actions -->
                  <div class="flex items-center space-x-2" v-if="team.pivot.role === 'admin' && user.id !== currentUser.id">
                    <!-- Make/Remove Admin Button -->
                    <button 
                      v-if="user.pivot.role !== 'admin'"
                      @click="makeAdmin(team.id, user.id)" 
                      class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                      title="Make admin"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    </button>
                    <button 
                      v-else
                      @click="removeAdmin(team.id, user.id)" 
                      class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors"
                      title="Remove admin"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                    
                    <!-- Remove Member Button -->
                    <button 
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
              </div>

              <!-- Pagination Controls -->
              <div v-if="team.users.length > 5" class="flex justify-center items-center space-x-2 mb-4">
                <button 
                  @click="prevPage(team.id)"
                  :disabled="currentPages[team.id] === 1"
                  class="px-2 py-1 text-sm rounded-md"
                  :class="{
                    'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300': currentPages[team.id] > 1,
                    'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed': currentPages[team.id] === 1
                  }"
                >
                  Previous
                </button>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                  Page {{ currentPages[team.id] }} of {{ Math.ceil(team.users.length / 5) }}
                </span>
                <button 
                  @click="nextPage(team.id)"
                  :disabled="currentPages[team.id] >= Math.ceil(team.users.length / 5)"
                  class="px-2 py-1 text-sm rounded-md"
                  :class="{
                    'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300': currentPages[team.id] < Math.ceil(team.users.length / 5),
                    'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed': currentPages[team.id] >= Math.ceil(team.users.length / 5)
                  }"
                >
                  Next
                </button>
              </div>
              
              <!-- Team Actions -->
              <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 space-y-3">
                <!-- Team Action Buttons -->
                <div class="flex space-x-2">
                  <button 
                    @click="openChat(team)" 
                    class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center"
                  >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Open Chat
                  </button>
                  
                  <!-- Delete Team (Admin Only) -->
                  <button 
                    v-if="isTeamAdmin(team)"
                    @click="deleteTeam(team.id)" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                  
                  <!-- Leave Team (Non-Admin) -->
                  <button 
                    v-else
                    @click="exitTeam(team.id)" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
                  >
                    Leave Team
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add this at the end of the template, before the closing </div> -->
    <Modal :show="showChat" @close="closeChat">
      <div class="mb-6 transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:mx-auto sm:w-full sm:max-w-2xl">
        <TeamChat 
          v-if="selectedTeamForChat"
          :team="selectedTeamForChat"
          @close="closeChat"
        />
      </div>
    </Modal>

    <!-- Add this at the end of the template, before the closing </div> -->
    <Modal :show="showEditNameModal" @close="closeEditNameModal">
      <div class="p-6">
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Team Name</h2>
          <button @click="closeEditNameModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Team Name</label>
            <input 
              v-model="editingTeamName" 
              type="text" 
              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter team name"
            />
          </div>
          
          <div class="mt-6 flex justify-end space-x-3">
            <button 
              @click="closeEditNameModal" 
              class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
              Cancel
            </button>
            <button 
              @click="updateTeamName" 
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Update
            </button>
          </div>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script>
import axios from 'axios';
import TeamChat from '../Goal/TeamChat.vue';
import Modal from '@/Components/Modal.vue';

export default {
  components: {
    TeamChat,
    Modal
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
      showEditNameModal: false,
      editingTeamName: '',
      selectedTeamForEdit: null,
      currentPages: {},
      notification: {
        show: false,
        message: '',
        type: 'success'
      }
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
        this.showNotification('Team created successfully!');
      } catch (err) {
        this.error = 'Failed to create team';
        this.showNotification('Failed to create team', 'error');
      }
    },

    async joinTeam() {
      try {
        const response = await axios.post('/api/teams/join', { invite_code: this.enteredInviteCode });
        this.teams.push(response.data.team);
        this.enteredInviteCode = '';
        this.showNotification('Successfully joined the team!');
      } catch (err) {
        this.error = 'Failed to join team';
        this.showNotification('Failed to join team', 'error');
      }
    },

    async removeMember(teamId, userId) {
      if (!confirm('Are you sure you want to remove this member from the team?')) return;
      
      try {
        await axios.delete(`/api/teams/${teamId}/users/${userId}`);
        const team = this.teams.find(t => t.id === teamId);
        team.users = team.users.filter(u => u.id !== userId);
        this.showNotification('Member removed successfully');
      } catch (err) {
        this.error = 'Failed to remove the member';
        this.showNotification('Failed to remove member', 'error');
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

    async makeAdmin(teamId, userId) {
      try {
        await axios.post(`/api/teams/${teamId}/members/${userId}/make-admin`);
        await this.fetchTeams();
        this.showNotification('User promoted to admin successfully');
      } catch (err) {
        this.error = 'Failed to make user admin';
        this.showNotification('Failed to promote user to admin', 'error');
      }
    },
    
    async removeAdmin(teamId, userId) {
      try {
        await axios.post(`/api/teams/${teamId}/members/${userId}/remove-admin`);
        await this.fetchTeams();
        this.showNotification('Admin privileges removed successfully');
      } catch (err) {
        this.error = 'Failed to remove admin privileges';
        this.showNotification('Failed to remove admin privileges', 'error');
      }
    },
    
    openEditNameModal(team) {
      this.selectedTeamForEdit = team;
      this.editingTeamName = team.name;
      this.showEditNameModal = true;
    },
    
    closeEditNameModal() {
      this.showEditNameModal = false;
      this.selectedTeamForEdit = null;
      this.editingTeamName = '';
    },
    
    async updateTeamName() {
      if (!this.editingTeamName || !this.selectedTeamForEdit) return;
      
      try {
        await axios.put(`/api/teams/${this.selectedTeamForEdit.id}/name`, {
          name: this.editingTeamName
        });
        await this.fetchTeams();
        this.closeEditNameModal();
        this.showNotification('Team name updated successfully');
      } catch (err) {
        this.error = 'Failed to update team name';
        this.showNotification('Failed to update team name', 'error');
      }
    },
    
    async deleteTeam(teamId) {
      if (!confirm('Are you sure you want to delete this team? This action cannot be undone.')) return;
      
      try {
        await axios.delete(`/api/teams/${teamId}`);
        await this.fetchTeams();
        this.showNotification('Team deleted successfully');
      } catch (err) {
        this.error = 'Failed to delete team';
        this.showNotification('Failed to delete team', 'error');
      }
    },
    
    async exitTeam(teamId) {
      if (!confirm('Are you sure you want to leave this team?')) return;
      
      try {
        await axios.post(`/api/teams/${teamId}/exit`);
        await this.fetchTeams();
        this.showNotification('Successfully left the team');
      } catch (err) {
        this.error = 'Failed to leave team';
        this.showNotification('Failed to leave team', 'error');
      }
    },

    isTeamAdmin(team) {
      return team.users.find(user => user.id === this.currentUser.id)?.pivot?.role === 'admin';
    },

    showNotification(message, type = 'success') {
      this.notification = {
        show: true,
        message,
        type
      };
      
      // Auto-hide notification after 3 seconds
      setTimeout(() => {
        this.notification.show = false;
      }, 3000);
    },

    paginatedMembers(team) {
      if (!this.currentPages[team.id]) {
        this.currentPages[team.id] = 1;
      }
      const start = (this.currentPages[team.id] - 1) * 5;
      const end = start + 5;
      return team.users.slice(start, end);
    },

    prevPage(teamId) {
      if (this.currentPages[teamId] > 1) {
        this.currentPages[teamId]--;
      }
    },

    nextPage(teamId) {
      const team = this.teams.find(t => t.id === teamId);
      if (team && this.currentPages[teamId] < Math.ceil(team.users.length / 5)) {
        this.currentPages[teamId]++;
      }
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

/* Notification animation */
.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from,
.notification-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>
