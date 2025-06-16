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
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Komandu Vadības Panelis</h2>
        <p class="text-gray-600 dark:text-gray-400">Pārvaldiet savas komandas un sadarbojieties ar citiem</p>
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
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Izveidot Jaunu Komandu</h3>
          </div>
          <form @submit.prevent="createTeam" class="space-y-4">
            <div>
              <label for="team-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Komandas Nosaukums</label>
              <input 
                id="team-name"
                type="text" 
                v-model="newTeamName" 
                placeholder="Ievadiet komandas nosaukumu" 
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
              Izveidot Komandu
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
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pievienoties Komandai</h3>
          </div>
          <form @submit.prevent="joinTeam" class="space-y-4">
            <div>
              <label for="invite-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ielūguma Kods</label>
              <input 
                id="invite-code"
                type="text" 
                v-model="enteredInviteCode" 
                placeholder="Ievadiet ielūguma kodu" 
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
              Pievienoties Komandai
            </button>
        </form>
      </div>
    </div>

      <!-- Teams Section -->
      <div class="mb-6">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Jūsu Komandas</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pārvaldiet savas komandas un komandas locekļus</p>
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ teams.length }} {{ teams.length === 1 ? 'komanda' : 'komandas' }}
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
          <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Nav atrastas komandas</h3>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Izveidojiet jaunu komandu vai pievienojieties esošai, lai sāktu.</p>
          <div class="mt-6 flex justify-center space-x-3">
            <button 
              @click="scrollToCreateForm" 
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Izveidot Komandu
              </button>
            <button 
              @click="scrollToJoinForm" 
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
              Pievienoties Komandai
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
                <div class="flex items-center space-x-2">
                  <!-- Invite Code Button (Admin only) -->
                  <div v-if="isTeamAdmin(team)" class="relative">
                    <button 
                      @click="toggleInviteCodeVisible(team.id)"
                      class="text-white bg-blue-700/50 hover:bg-blue-700/80 transition-colors flex items-center px-2 py-1 rounded"
                      title="Rādīt ielūguma kodu"
                    >
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                      </svg>
                      Ielūgt
                    </button>
                    
                    <!-- Invite Code Dropdown -->
                    <div 
                      v-if="visibleInviteCode === team.id"
                      class="absolute [left:-80px] top-full mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 p-3 border border-gray-200 dark:border-gray-700"
                      :ref="`invite-dropdown-${team.id}`"
                    >
                      <div class="flex justify-between items-center mb-2">
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Ielūguma Kods</h5>
                        <button 
                          @click="generateNewInviteCode(team.id)"
                          class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                        >
                          Ģenerēt Jaunu
                        </button>
                      </div>
                      <div class="flex items-center mb-2">
                        <code class="bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded text-xs text-blue-800 dark:text-blue-300 flex-1 border border-gray-200 dark:border-gray-700 overflow-x-auto">
                          {{ team.invite_code }}
                        </code>
                        <button 
                          @click="copyInviteCode(team.invite_code)"
                          class="ml-2 p-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                          title="Kopēt ielūguma kodu"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                          </svg>
                        </button>
                      </div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">
                        Dalieties ar šo kodu, lai ļautu citiem pievienoties jūsu komandai
                      </div>
                    </div>
                  </div>
                  <span class="text-xs bg-white/20 text-white px-2 py-1 rounded-full">
                    {{ (team.users && team.users.length) ? team.users.length : 0 }} {{ (team.users && team.users.length) === 1 ? 'biedrs' : 'biedri' }}
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Team Body -->
            <div class="p-6 flex flex-col">
              <div class="flex flex-col flex-grow min-h-[300px]">
                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                  <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  biedri
                </h5>
                
                <div class="space-y-3 mb-6 overflow-y-auto pr-2 flex-grow">
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
                        <span 
                          class="block text-xs px-2 py-0.5 rounded-full inline-block mt-1"
                          :class="{
                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': getUserRole(user, team) === 'admin',
                            'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200': getUserRole(user, team) === 'owner',
                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200': getUserRole(user, team) === 'member'
                          }"
                        >
                          {{ getUserRole(user, team) === 'owner' ? 'Īpašnieks' : (getUserRole(user, team) === 'admin' ? 'Administrators' : 'Dalībnieks') }}
                        </span>
                      </div>
                    </div>
                    
                    <!-- Admin Actions -->
                    <div class="flex items-center space-x-2" v-if="isTeamAdmin(team) && user.id !== currentUser.id && getUserRole(user, team) !== 'admin'">
                      <!-- Make Admin Button -->
                      <button 
                        @click="makeAdmin(team.id, user.id)" 
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                        title="Padarīt par administratoru"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                      </button>
                      
                      <!-- Remove Member Button -->
                      <button 
                        @click="removeMember(team.id, user.id)" 
                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                        title="Noņemt dalībnieku"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Pagination Controls -->
                <div v-if="team.users && team.users.length > 5" class="flex justify-center items-center space-x-2 mb-4">
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
                    Page {{ currentPages[team.id] }} of {{ Math.ceil((team.users ? team.users.length : 0) / 5) }}
                  </span>
                  <button 
                    @click="nextPage(team.id)"
                    :disabled="currentPages[team.id] >= Math.ceil((team.users ? team.users.length : 0) / 5)"
                    class="px-2 py-1 text-sm rounded-md"
                    :class="{
                      'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300': currentPages[team.id] < Math.ceil((team.users ? team.users.length : 0) / 5),
                      'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed': currentPages[team.id] >= Math.ceil((team.users ? team.users.length : 0) / 5)
                    }"
                  >
                    Next
                  </button>
                </div>
              </div>
                
              <!-- Team Actions -->
              <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700 h-14">
                <!-- Team Action Buttons -->
                <div class="flex justify-between items-center h-full">
                  <button 
                    @click="openChat(team)" 
                    class="flex-1 mr-2 py-2 px-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center h-10"
                  >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Tērzēšana
                  </button>
                  
                  <div v-if="isTeamAdmin(team)" class="flex space-x-1">
                    <!-- Leave Team (Admin) -->
                    <button 
                      v-if="!isTeamOwner(team)"
                      @click="exitTeam(team.id)" 
                      class="px-2 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 h-10 w-10 flex items-center justify-center"
                      title="Pamest Komandu"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                      </svg>
                    </button>
                    
                    <!-- Delete Team (Owner only) -->
                    <button 
                      v-if="isTeamOwner(team)"
                      @click="deleteTeam(team.id)" 
                      class="px-2 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 h-10 w-10 flex items-center justify-center"
                      title="Dzēst Komandu"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                  
                  <!-- Leave Team (Non-Admin) -->
                  <button 
                    v-else
                    @click="exitTeam(team.id)" 
                    class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 h-10 w-20 flex items-center justify-center"
                  >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Leave
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
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Rediģēt Komandas Nosaukumu</h2>
          <button @click="closeEditNameModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Komandas Nosaukums</label>
            <input 
              v-model="editingTeamName" 
              type="text" 
              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              placeholder="Ievadiet komandas nosaukumu"
            />
          </div>
          
          <div class="mt-6 flex justify-end space-x-3">
            <button 
              @click="closeEditNameModal" 
              class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
              Atcelt
            </button>
            <button 
              @click="updateTeamName" 
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Atjaunināt
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
        this.error = 'Neizdevās ielādēt lietotāja datus';
      }
    },

    async fetchTeams() {
      try {
        const response = await axios.get('/api/teams');
        this.teams = response.data;
        // Log the team data structure for debugging
        console.log('Teams data:', this.teams);
        if (this.teams.length > 0) {
          console.log('First team structure:', this.teams[0]);
          if (this.teams[0].users && this.teams[0].users.length > 0) {
            console.log('First user in team:', this.teams[0].users[0]);
            console.log('User pivot data:', this.teams[0].users[0].pivot);
          }
        }
      } catch (err) {
        this.error = 'Neizdevās ielādēt komandas';
      } finally {
        this.loading = false;
      }
    },

    async createTeam() {
      try {
        const response = await axios.post('/api/teamsMake', { name: this.newTeamName });
        await this.fetchTeams();
        this.newTeamName = '';
        this.showNotification('Komanda veiksmīgi izveidota!');
      } catch (err) {
        this.error = 'Neizdevās izveidot komandu';
        this.showNotification('Neizdevās izveidot komandu', 'error');
      }
    },

    async joinTeam() {
      try {
        const response = await axios.post('/api/teams/join', { invite_code: this.enteredInviteCode });
        this.teams.push(response.data.team);
        this.enteredInviteCode = '';
        this.showNotification('Veiksmīgi pievienojāties komandai!');
      } catch (err) {
        this.error = 'Neizdevās pievienoties komandai';
        this.showNotification('Neizdevās pievienoties komandai', 'error');
      }
    },

    async removeMember(teamId, userId) {
      if (!confirm('Vai tiešām vēlaties noņemt šo dalībnieku no komandas?')) return;
      
      try {
        await axios.delete(`/api/teams/${teamId}/users/${userId}`);
        const team = this.teams.find(t => t.id === teamId);
        team.users = team.users.filter(u => u.id !== userId);
        this.showNotification('Dalībnieks veiksmīgi noņemts');
      } catch (err) {
        this.error = 'Neizdevās noņemt dalībnieku';
        this.showNotification('Neizdevās noņemt dalībnieku', 'error');
      }
    },

    toggleInviteCodeVisible(teamId) {
      if (this.visibleInviteCode === teamId) {
        this.visibleInviteCode = null;
      } else {
        this.visibleInviteCode = teamId;
        
        // Add a click event listener to close the dropdown when clicking outside
        this.$nextTick(() => {
          const handleClickOutside = (event) => {
            // Get both the dropdown and the button
            const dropdownRef = this.$refs[`invite-dropdown-${teamId}`];
            const buttons = document.querySelectorAll(`button[title="Rādīt ielūguma kodu"]`);
            let clickedOnButton = false;
            
            // Check if the click was on the invite button
            buttons.forEach(button => {
              if (button.contains(event.target)) {
                clickedOnButton = true;
              }
            });
            
            // If click was not on the dropdown and not on the button, close the dropdown
            if (dropdownRef && !dropdownRef.contains(event.target) && !clickedOnButton) {
              this.visibleInviteCode = null;
              document.removeEventListener('mousedown', handleClickOutside);
            }
          };
          
          // Use mousedown instead of click for better handling
          document.addEventListener('mousedown', handleClickOutside);
        });
      }
    },

    async generateNewInviteCode(teamId) {
      try {
        const response = await axios.post(`/api/teams/${teamId}/invite`);
        const team = this.teams.find(t => t.id === teamId);
        if (team) {
          team.invite_code = response.data.invite_code;
          this.showNotification('Jauns ielūguma kods ģenerēts');
        }
      } catch (err) {
        this.error = 'Neizdevās ģenerēt jaunu ielūguma kodu';
        this.showNotification('Neizdevās ģenerēt jaunu ielūguma kodu', 'error');
      }
    },
    
    copyInviteCode(code) {
      navigator.clipboard.writeText(code)
        .then(() => {
          this.showNotification('Ielūguma kods kopēts starpliktuvē');
        })
        .catch(err => {
          console.error('Neizdevās kopēt ielūguma kodu', err);
          this.showNotification('Neizdevās kopēt ielūguma kodu', 'error');
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
        this.showNotification('Lietotājs veiksmīgi paaugstināts līdz administratoram');
      } catch (err) {
        this.error = 'Neizdevās padarīt lietotāju par administratoru';
        this.showNotification('Neizdevās paaugstināt lietotāju līdz administratoram', 'error');
      }
    },
    
    async removeAdmin(teamId, userId) {
      try {
        await axios.post(`/api/teams/${teamId}/members/${userId}/remove-admin`);
        await this.fetchTeams();
        this.showNotification('Administratora privilēģijas veiksmīgi noņemtas');
      } catch (err) {
        this.error = 'Neizdevās noņemt administratora privilēģijas';
        this.showNotification('Neizdevās noņemt administratora privilēģijas', 'error');
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
        this.showNotification('Komandas nosaukums veiksmīgi atjaunināts');
      } catch (err) {
        this.error = 'Neizdevās atjaunināt komandas nosaukumu';
        this.showNotification('Neizdevās atjaunināt komandas nosaukumu', 'error');
      }
    },
    
    async deleteTeam(teamId) {
      if (!confirm('Vai tiešām vēlaties dzēst šo komandu? Šo darbību nevar atsaukt.')) return;
      
      try {
        await axios.delete(`/api/teams/${teamId}`);
        await this.fetchTeams();
        this.showNotification('Komanda veiksmīgi dzēsta');
      } catch (err) {
        this.error = 'Neizdevās dzēst komandu';
        this.showNotification('Neizdevās dzēst komandu', 'error');
      }
    },
    
    async exitTeam(teamId) {
      if (!confirm('Vai tiešām vēlaties pamest šo komandu?')) return;
      
      try {
        await axios.post(`/api/teams/${teamId}/exit`);
        await this.fetchTeams();
        this.showNotification('Veiksmīgi pameta komandu');
      } catch (err) {
        this.error = 'Neizdevās pamest komandu';
        this.showNotification('Neizdevās pamest komandu', 'error');
      }
    },

    isTeamOwner(team) {
      if (!this.currentUser) return false;
      return this.currentUser.id === team.owner_id;
    },

    isTeamAdmin(team) {
      if (!this.currentUser) return false;
      
      // First check if user is the owner
      if (this.currentUser.id === team.owner_id) {
        return true;
      }
      
      // Then check if user is an admin in the team's pivot data
      if (team.pivot && team.pivot.role === 'admin') {
        return true;
      }
      
      return false;
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
      if (!team.users || !Array.isArray(team.users)) {
        return [];
      }

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

    getUserRole(user, team) {
      // Check if this is the team owner
      if (user.id === team.owner_id) {
        return 'owner';
      }
      
      // For other users, check their role directly on the user object
      if (user.role) {
        return user.role;
      }
      
      // If no role found, default to member
      return 'member';
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
