<template>
  <Head title="GoalWeb" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Admin Dashboard</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Navigation Tabs -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6 sticky top-0 z-10">
          <nav class="flex space-x-4 px-4 py-3" aria-label="Tabs">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              class="px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200"
              :class="[
                activeTab === tab.id
                  ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200'
                  : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
              ]"
            >
              {{ tab.name }}
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
          <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Admin Dashboard</h1>
            
            <!-- Statistics Tab -->
            <div v-if="activeTab === 'statistics'" class="space-y-6">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">System Statistics</h2>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Users Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                  <div class="p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Users</dt>
                          <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ statistics.totalUsers }}</div>
                            <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ statistics.activeUsers }} active)</div>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Teams Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                  <div class="p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Teams</dt>
                          <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ statistics.totalTeams }}</dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Goals Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                  <div class="p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Goals</dt>
                          <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ statistics.totalGoals }}</div>
                            <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ statistics.completedGoals }} completed)</div>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Discussions Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                  <div class="p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Discussions</dt>
                          <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ statistics.totalDiscussions }}</div>
                            <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ statistics.totalReplies }} replies)</div>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Activity Charts -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">User Activity</h3>
                  <div class="h-64">
                    <UserActivityChart :activity-data="statistics.recentActivity" />
                  </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Goal Completion Rate</h3>
                  <div class="h-64">
                    <GoalCompletionChart 
                      :total-goals="statistics.totalGoals" 
                      :completed-goals="statistics.completedGoals" 
                    />
                  </div>
                </div>
              </div>
              
              <!-- Recent Activity -->
              <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Activity</h3>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
                  <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="(activity, index) in statistics.recentActivity" :key="index" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                      <div class="flex items-center">
                        <div class="flex-shrink-0">
                          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full" 
                            :class="{
                              'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': activity.type === 'user',
                              'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': activity.type === 'team',
                              'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200': activity.type === 'goal'
                            }">
                            <svg v-if="activity.type === 'user'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <svg v-if="activity.type === 'team'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <svg v-if="activity.type === 'goal'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                          </span>
                        </div>
                        <div class="ml-4">
                          <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activity.description }}</p>
                          <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(activity.timestamp) }}</p>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
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
  </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserActivityChart from '@/Components/Charts/UserActivityChart.vue';
import GoalCompletionChart from '@/Components/Charts/GoalCompletionChart.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const activeTab = ref('statistics');
const tabs = ref([
  { id: 'statistics', name: 'Statistics' },
  { id: 'users', name: 'Users' },
  { id: 'teams', name: 'Teams' },
  { id: 'settings', name: 'Settings' }
]);

const users = ref([]);
const teams = ref([]);
const settings = ref({
  siteName: 'Goal Management System',
  defaultRole: 'user',
  emailNotifications: true
});

const statistics = ref({
  totalUsers: 0,
  activeUsers: 0,
  totalTeams: 0,
  totalGoals: 0,
  completedGoals: 0,
  totalDiscussions: 0,
  totalReplies: 0,
  recentActivity: []
});

const showUserModal = ref(false);
const showTeamModal = ref(false);
const editingUser = ref(null);
const editingTeam = ref(null);
const userForm = ref({
  name: '',
  email: '',
  role: 'user',
  status: 'active'
});
const teamForm = ref({
  name: '',
  description: '',
  members: []
});
const availableUsers = ref([]);

onMounted(() => {
  fetchStatistics();
  fetchUsers();
  fetchTeams();
  fetchSettings();
});

const fetchStatistics = async () => {
  try {
    const response = await axios.get('/api/admin/statistics');
    statistics.value = response.data;
  } catch (error) {
    console.error('Error fetching statistics:', error);
  }
};

const fetchUsers = async () => {
  try {
    const response = await axios.get('/api/admin/users');
    users.value = response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

const fetchTeams = async () => {
  try {
    const response = await axios.get('/api/admin/teams');
    teams.value = response.data;
  } catch (error) {
    console.error('Error fetching teams:', error);
  }
};

const fetchSettings = async () => {
  try {
    const response = await axios.get('/api/admin/settings');
    settings.value = response.data;
  } catch (error) {
    console.error('Error fetching settings:', error);
  }
};

const openUserModal = (user = null) => {
  editingUser.value = user;
  if (user) {
    userForm.value = { ...user };
  } else {
    userForm.value = {
      name: '',
      email: '',
      role: 'user',
      status: 'active'
    };
  }
  showUserModal.value = true;
};

const closeUserModal = () => {
  showUserModal.value = false;
  editingUser.value = null;
  userForm.value = {
    name: '',
    email: '',
    role: 'user',
    status: 'active'
  };
};

const saveUser = async () => {
  try {
    if (editingUser.value) {
      await axios.put(`/api/admin/users/${editingUser.value.id}`, userForm.value);
    } else {
      await axios.post('/api/admin/users', userForm.value);
    }
    fetchUsers();
    closeUserModal();
  } catch (error) {
    console.error('Error saving user:', error);
  }
};

const deleteUser = async (userId) => {
  if (!confirm('Are you sure you want to delete this user?')) return;
  
  try {
    await axios.delete(`/api/admin/users/${userId}`);
    fetchUsers();
  } catch (error) {
    console.error('Error deleting user:', error);
  }
};

const openTeamModal = (team = null) => {
  editingTeam.value = team;
  if (team) {
    teamForm.value = { 
      name: team.name,
      description: team.description || '',
      members: team.members || []
    };
  } else {
    teamForm.value = {
      name: '',
      description: '',
      members: []
    };
  }
  fetchAvailableUsers();
  showTeamModal.value = true;
};

const closeTeamModal = () => {
  showTeamModal.value = false;
  editingTeam.value = null;
  teamForm.value = {
    name: '',
    description: '',
    members: []
  };
};

const fetchAvailableUsers = async () => {
  try {
    const response = await axios.get('/api/admin/users');
    availableUsers.value = response.data;
  } catch (error) {
    console.error('Error fetching available users:', error);
  }
};

const saveTeam = async () => {
  try {
    if (editingTeam.value) {
      await axios.put(`/api/admin/teams/${editingTeam.value.id}`, teamForm.value);
    } else {
      await axios.post('/api/admin/teams', teamForm.value);
    }
    fetchTeams();
    closeTeamModal();
  } catch (error) {
    console.error('Error saving team:', error);
  }
};

const deleteTeam = async (teamId) => {
  if (!confirm('Are you sure you want to delete this team?')) return;
  
  try {
    await axios.delete(`/api/teams/${teamId}`);
    fetchTeams();
  } catch (error) {
    console.error('Error deleting team:', error);
  }
};

const saveSettings = async () => {
  try {
    await axios.put('/api/admin/settings', settings.value);
    alert('Settings saved successfully!');
  } catch (error) {
    console.error('Error saving settings:', error);
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
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