<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Admin Dashboard</h1>
            
            <!-- Admin Navigation Tabs -->
            <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
              <nav class="flex -mb-px">
                <button 
                  v-for="tab in tabs" 
                  :key="tab.id"
                  @click="activeTab = tab.id"
                  class="mr-8 py-4 px-1 border-b-2 font-medium text-sm"
                  :class="activeTab === tab.id 
                    ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                >
                  {{ tab.name }}
                </button>
              </nav>
            </div>
            
            <!-- Users Management Tab -->
            <div v-if="activeTab === 'users'" class="space-y-6">
              <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">User Management</h2>
                <button 
                  @click="openUserModal()"
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                  Add User
                </button>
              </div>
              
              <!-- Users Table -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ user.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ user.email }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                          :class="user.role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'">
                          {{ user.role }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                          :class="user.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'">
                          {{ user.status }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button @click="editUser(user)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Edit</button>
                        <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Teams Management Tab -->
            <div v-if="activeTab === 'teams'" class="space-y-6">
              <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Team Management</h2>
                <button 
                  @click="openTeamModal()"
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                  Add Team
                </button>
              </div>
              
              <!-- Teams Table -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Team Name</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Members</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="team in teams" :key="team.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ team.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ team.member_count }} members</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ formatDate(team.created_at) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button @click="editTeam(team)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Edit</button>
                        <button @click="deleteTeam(team.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Settings Tab -->
            <div v-if="activeTab === 'settings'" class="space-y-6">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">System Settings</h2>
              
              <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">General Settings</h3>
                
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Name</label>
                    <input 
                      v-model="settings.siteName" 
                      type="text" 
                      class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default User Role</label>
                    <select 
                      v-model="settings.defaultRole" 
                      class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option value="user">User</option>
                      <option value="admin">Admin</option>
                    </select>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Notifications</label>
                    <div class="flex items-center">
                      <input 
                        v-model="settings.emailNotifications" 
                        type="checkbox" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                      />
                      <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Enable email notifications</label>
                    </div>
                  </div>
                </div>
                
                <div class="mt-6">
                  <button 
                    @click="saveSettings"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                  >
                    Save Settings
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- User Modal -->
    <div v-if="showUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md">
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ editingUser ? 'Edit User' : 'Add User' }}</h2>
            <button @click="closeUserModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
              <input 
                v-model="userForm.name" 
                type="text" 
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <input 
                v-model="userForm.email" 
                type="email" 
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
              <select 
                v-model="userForm.role" 
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
              <select 
                v-model="userForm.status" 
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
          
          <div class="mt-6 flex justify-end space-x-3">
            <button 
              @click="closeUserModal" 
              class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
              Cancel
            </button>
            <button 
              @click="saveUser" 
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              {{ editingUser ? 'Update' : 'Create' }}
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Team Modal -->
    <div v-if="showTeamModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md">
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ editingTeam ? 'Edit Team' : 'Add Team' }}</h2>
            <button @click="closeTeamModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Team Name</label>
              <input 
                v-model="teamForm.name" 
                type="text" 
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
              <textarea 
                v-model="teamForm.description" 
                rows="3"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Team Members</label>
              <div class="border border-gray-300 dark:border-gray-600 rounded-md p-3 max-h-40 overflow-y-auto">
                <div v-for="user in availableUsers" :key="user.id" class="flex items-center mb-2">
                  <input 
                    :id="'user-' + user.id"
                    type="checkbox"
                    :value="user.id"
                    v-model="teamForm.members"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  />
                  <label :for="'user-' + user.id" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    {{ user.name }} ({{ user.email }})
                  </label>
                </div>
              </div>
            </div>
          </div>
          
          <div class="mt-6 flex justify-end space-x-3">
            <button 
              @click="closeTeamModal" 
              class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
              Cancel
            </button>
            <button 
              @click="saveTeam" 
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              {{ editingTeam ? 'Update' : 'Create' }}
            </button>
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
      activeTab: 'users',
      tabs: [
        { id: 'users', name: 'Users' },
        { id: 'teams', name: 'Teams' },
        { id: 'settings', name: 'Settings' }
      ],
      users: [],
      teams: [],
      settings: {
        siteName: 'Goal Management System',
        defaultRole: 'user',
        emailNotifications: true
      },
      showUserModal: false,
      showTeamModal: false,
      editingUser: null,
      editingTeam: null,
      userForm: {
        name: '',
        email: '',
        role: 'user',
        status: 'active'
      },
      teamForm: {
        name: '',
        description: '',
        members: []
      },
      availableUsers: []
    };
  },
  
  mounted() {
    this.fetchUsers();
    this.fetchTeams();
    this.fetchSettings();
  },
  
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get('/api/admin/users');
        this.users = response.data;
      } catch (error) {
        console.error('Error fetching users:', error);
      }
    },
    
    async fetchTeams() {
      try {
        const response = await axios.get('/api/admin/teams');
        this.teams = response.data;
      } catch (error) {
        console.error('Error fetching teams:', error);
      }
    },
    
    async fetchSettings() {
      try {
        const response = await axios.get('/api/admin/settings');
        this.settings = response.data;
      } catch (error) {
        console.error('Error fetching settings:', error);
      }
    },
    
    openUserModal(user = null) {
      this.editingUser = user;
      if (user) {
        this.userForm = { ...user };
      } else {
        this.userForm = {
          name: '',
          email: '',
          role: 'user',
          status: 'active'
        };
      }
      this.showUserModal = true;
    },
    
    closeUserModal() {
      this.showUserModal = false;
      this.editingUser = null;
      this.userForm = {
        name: '',
        email: '',
        role: 'user',
        status: 'active'
      };
    },
    
    async saveUser() {
      try {
        if (this.editingUser) {
          await axios.put(`/api/admin/users/${this.editingUser.id}`, this.userForm);
        } else {
          await axios.post('/api/admin/users', this.userForm);
        }
        this.fetchUsers();
        this.closeUserModal();
      } catch (error) {
        console.error('Error saving user:', error);
      }
    },
    
    async deleteUser(userId) {
      if (!confirm('Are you sure you want to delete this user?')) return;
      
      try {
        await axios.delete(`/api/admin/users/${userId}`);
        this.fetchUsers();
      } catch (error) {
        console.error('Error deleting user:', error);
      }
    },
    
    openTeamModal(team = null) {
      this.editingTeam = team;
      if (team) {
        this.teamForm = { 
          name: team.name,
          description: team.description || '',
          members: team.members || []
        };
      } else {
        this.teamForm = {
          name: '',
          description: '',
          members: []
        };
      }
      this.fetchAvailableUsers();
      this.showTeamModal = true;
    },
    
    closeTeamModal() {
      this.showTeamModal = false;
      this.editingTeam = null;
      this.teamForm = {
        name: '',
        description: '',
        members: []
      };
    },
    
    async fetchAvailableUsers() {
      try {
        const response = await axios.get('/api/users');
        this.availableUsers = response.data;
      } catch (error) {
        console.error('Error fetching available users:', error);
      }
    },
    
    async saveTeam() {
      try {
        if (this.editingTeam) {
          await axios.put(`/api/admin/teams/${this.editingTeam.id}`, this.teamForm);
        } else {
          await axios.post('/api/teams', this.teamForm);
        }
        this.fetchTeams();
        this.closeTeamModal();
      } catch (error) {
        console.error('Error saving team:', error);
      }
    },
    
    async deleteTeam(teamId) {
      if (!confirm('Are you sure you want to delete this team?')) return;
      
      try {
        await axios.delete(`/api/teams/${teamId}`);
        this.fetchTeams();
      } catch (error) {
        console.error('Error deleting team:', error);
      }
    },
    
    async saveSettings() {
      try {
        await axios.put('/api/admin/settings', this.settings);
        alert('Settings saved successfully!');
      } catch (error) {
        console.error('Error saving settings:', error);
      }
    },
    
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString();
    }
  }
};
</script>

<style scoped>
/* Custom scrollbar for the modal */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
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
</style> 