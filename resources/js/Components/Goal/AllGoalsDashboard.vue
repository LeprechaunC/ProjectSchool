<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>

    <div v-else-if="error" class="max-w-4xl mx-auto">
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg my-4">
        <p>{{ error }}</p>
        <button @click="fetchGoals" class="mt-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
          Try Again
        </button>
      </div>
    </div>

    <div v-else class="max-w-4xl mx-auto">
      <!-- Header Section -->
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">All Goals Dashboard</h2>
        
        <!-- Controls Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Team Selector -->
            <div>
              <label for="team-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Team</label>
              <select 
                v-model="selectedTeam" 
                @change="fetchGoals" 
                id="team-select" 
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="all">All Goals</option>
                <option value="personal">Personal</option>
                <option v-for="team in teams" :key="team.id" :value="team.id">
                  {{ team.name }}
                </option>
              </select>
            </div>
            
            <!-- Search Input -->
            <div>
              <label for="search-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Goals</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <input
                  v-model="searchQuery"
                  type="text"
                  id="search-input"
                  placeholder="Search goals..."
                  class="w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                />
              </div>
            </div>
            
            <!-- Sort Options -->
            <div>
              <label for="sort-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
              <select 
                v-model="sortOption" 
                id="sort-select" 
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="az">Title (A-Z)</option>
                <option value="za">Title (Z-A)</option>
                <option value="oldest">Start Date (Oldest First)</option>
                <option value="youngest">Start Date (Youngest First)</option>
              </select>
            </div>
          </div>
          
          <!-- Priority Filters -->
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by Priority</label>
            <div class="flex flex-wrap gap-2">
              <button 
                @click="togglePriorityFilter('all')" 
                class="px-3 py-1.5 text-sm rounded-lg transition-colors" 
                :class="priorityFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
              >
                All
              </button>
              <button 
                @click="togglePriorityFilter('high')" 
                class="px-3 py-1.5 text-sm rounded-lg transition-colors" 
                :class="priorityFilter === 'high' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300'"
              >
                High
              </button>
              <button 
                @click="togglePriorityFilter('medium')" 
                class="px-3 py-1.5 text-sm rounded-lg transition-colors" 
                :class="priorityFilter === 'medium' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300'"
              >
                Medium
              </button>
              <button 
                @click="togglePriorityFilter('low')" 
                class="px-3 py-1.5 text-sm rounded-lg transition-colors" 
                :class="priorityFilter === 'low' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300'"
              >
                Low
              </button>
            </div>
          </div>
          
          <!-- PDF Export Options -->
          <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Export Options</h3>
            <div class="flex flex-wrap gap-2">
              <a
                href="/export/personal-goals"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Personal Goals
              </a>
              
              <a
                v-if="selectedTeam !== 'all' && selectedTeam !== 'personal' && selectedTeam"
                :href="`/export/team-goals/${selectedTeam}`"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export {{ selectedTeamName }} Goals
              </a>
              
              <a
                href="/export/user-teams"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export My Teams
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Goals List Section -->
      <div v-if="paginatedGoals.length > 0">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Goals for: {{ selectedTeamName }}</h3>
          <span class="text-sm text-gray-500 dark:text-gray-400">{{ filteredGoals.length }} goals found</span>
        </div>
        
        <div class="space-y-4">
          <div 
            v-for="goal in paginatedGoals" 
            :key="goal.id" 
            @click="openGoalModal(goal)"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 hover:shadow-md transition-shadow cursor-pointer border border-gray-100 dark:border-gray-700"
          >
            <div class="flex justify-between items-start">
              <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ goal.title }}</h4>
              <div class="flex items-center space-x-2">
                <!-- Priority Badge -->
                <span 
                  class="text-xs px-2 py-1 rounded-full"
                  :class="{
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': goal.priority === 'high',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': goal.priority === 'medium',
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': goal.priority === 'low',
                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200': !goal.priority
                  }"
                >
                  {{ goal.priority ? goal.priority.charAt(0).toUpperCase() + goal.priority.slice(1) : 'Medium' }}
                </span>
                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                  {{ formatDate(goal.start_time) }}
                </span>
              </div>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">{{ goal.description || 'No description provided' }}</p>
            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
              <span><strong>Start:</strong> {{ formatDateTime(goal.start_time) }}</span>
              <span><strong>End:</strong> {{ formatDateTime(goal.end_time) }}</span>
            </div>
          </div>
        </div>

        <!-- Pagination Controls -->
        <div v-if="totalPages > 1" class="mt-6 flex justify-center items-center space-x-2">
          <button 
            @click="changePage(currentPage - 1)" 
            :disabled="currentPage === 1"
            class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <span class="text-sm text-gray-600 dark:text-gray-400">Page {{ currentPage }} of {{ totalPages }}</span>
          <button 
            @click="changePage(currentPage + 1)" 
            :disabled="currentPage === totalPages"
            class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>

      <div v-else class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No goals available</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No goals found for the selected options.</p>
      </div>
    </div>

    <!-- Fullscreen Modal -->
    <div v-if="isModalVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="closeModal">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ selectedGoal.title }}</h2>
            <button @click="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div v-if="isEditing" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Goal Title</label>
              <input 
                v-model="selectedGoal.title" 
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Goal Title" 
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
              <textarea 
                v-model="selectedGoal.description" 
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Goal Description"
                rows="4"
              ></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                <input 
                  v-model="selectedGoal.start_time" 
                  type="datetime-local" 
                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500" 
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                <input 
                  v-model="selectedGoal.end_time" 
                  type="datetime-local" 
                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500" 
                />
              </div>
            </div>

            <!-- Priority Selector -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
              <div class="flex space-x-3">
                <label class="relative flex items-center">
                  <input 
                    type="radio" 
                    v-model="selectedGoal.priority" 
                    value="high" 
                    class="sr-only"
                  />
                  <div 
                    class="p-2 rounded-lg cursor-pointer border-2"
                    :class="selectedGoal.priority === 'high' 
                      ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border-red-500' 
                      : 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-300 border-transparent'"
                  >
                    High
                  </div>
                </label>
                <label class="relative flex items-center">
                  <input 
                    type="radio" 
                    v-model="selectedGoal.priority" 
                    value="medium" 
                    class="sr-only"
                  />
                  <div 
                    class="p-2 rounded-lg cursor-pointer border-2"
                    :class="selectedGoal.priority === 'medium' || !selectedGoal.priority
                      ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 border-yellow-500' 
                      : 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-300 border-transparent'"
                  >
                    Medium
                  </div>
                </label>
                <label class="relative flex items-center">
                  <input 
                    type="radio" 
                    v-model="selectedGoal.priority" 
                    value="low" 
                    class="sr-only"
                  />
                  <div 
                    class="p-2 rounded-lg cursor-pointer border-2"
                    :class="selectedGoal.priority === 'low' 
                      ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border-green-500' 
                      : 'bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-300 border-transparent'"
                  >
                    Low
                  </div>
                </label>
              </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
              <button 
                @click="cancelEdit" 
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
              >
                Cancel
              </button>
              <button 
                @click="saveGoal" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              >
                Save Changes
              </button>
            </div>
          </div>

          <div v-else class="space-y-4">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
              <!-- Priority display at the top of the details -->
              <div class="mb-4 flex justify-between items-center">
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Priority</h4>
                  <span 
                    class="inline-block px-3 py-1 mt-1 text-sm font-medium rounded-full"
                    :class="{
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': selectedGoal.priority === 'high',
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': selectedGoal.priority === 'medium' || !selectedGoal.priority,
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': selectedGoal.priority === 'low'
                    }"
                  >
                    {{ selectedGoal.priority ? (selectedGoal.priority.charAt(0).toUpperCase() + selectedGoal.priority.slice(1)) : 'Medium' }}
                  </span>
                </div>
                <div v-if="selectedGoal.done" class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-sm">
                  Completed
                </div>
              </div>
              
              <p class="text-gray-700 dark:text-gray-300">{{ selectedGoal.description || 'No description provided' }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Start Time</h4>
                <p class="text-gray-900 dark:text-white">{{ formatDateTime(selectedGoal.start_time) }}</p>
              </div>
              
              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">End Time</h4>
                <p class="text-gray-900 dark:text-white">{{ formatDateTime(selectedGoal.end_time) }}</p>
              </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
              <button 
                @click="deleteGoal(selectedGoal.id)" 
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
              >
                Delete
              </button>
              <button 
                @click="startEdit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              >
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    team_id: {
      type: [Number, String, null],
      default: null
    }
  },
  data() {
    return {
      teams: [],
      goals: [],
      selectedTeam: this.team_id ? this.team_id : "personal",
      searchQuery: "",
      loading: true,
      error: null,
      sortOption: "az", // Default sorting option
      isModalVisible: false,
      isEditing: false,
      selectedGoal: null,
      currentPage: 1,  // Start on page 1
      goalsPerPage: 10, // Increased to show more goals per page
      allTeamsGoals: [], // Separate array to store goals from all teams
      personalGoals: [], // Separate array to store personal goals
      teamsGoals: [], // Separate array to store team goals
      priorityFilter: 'all',
    };
  },

  computed: {
    filteredGoals() {
      // Start with all goals
      let filtered = [...this.goals];
      
      // Apply priority filter
      if (this.priorityFilter !== 'all') {
        filtered = filtered.filter(goal => {
          // If no priority is set, treat it as 'medium' (default)
          const goalPriority = goal.priority || 'medium';
          return goalPriority === this.priorityFilter;
        });
      }
      
      // If search query is empty, just return the priority-filtered goals with sorting
      if (!this.searchQuery.trim()) {
        return this.sortGoals(filtered);
      }
      
      // Apply search query filter with null checks
      const searchLower = this.searchQuery.toLowerCase().trim();
      filtered = filtered.filter(goal => {
        // Check title (with null check)
        const titleMatch = goal.title ? goal.title.toLowerCase().includes(searchLower) : false;
        
        // Check description (with null check)
        const descriptionMatch = goal.description ? goal.description.toLowerCase().includes(searchLower) : false;
        
        return titleMatch || descriptionMatch;
      });
      
      // Apply sorting
      return this.sortGoals(filtered);
    },

    paginatedGoals() {
      // Determine the start and end index for the current page
      const startIndex = (this.currentPage - 1) * this.goalsPerPage;
      const endIndex = startIndex + this.goalsPerPage;
      return this.filteredGoals.slice(startIndex, endIndex);
    },

    totalPages() {
      // Total number of pages
      return Math.ceil(this.filteredGoals.length / this.goalsPerPage);
    },

    selectedTeamName() {
      if (this.selectedTeam === "personal") return "Personal Goals";
      if (this.selectedTeam === "all") return "All Goals";
      const team = this.teams.find((t) => t.id === this.selectedTeam);
      return team ? team.name : "All Goals";
    },
  },

  watch: {
    // Watch for changes in the search query to reset pagination
    searchQuery() {
      this.currentPage = 1;
    },
    // Watch for changes in the selected team to fetch the right goals
    selectedTeam() {
      this.fetchGoals();
      this.currentPage = 1;
    },
    // Watch for changes in priority filter to reset pagination
    priorityFilter() {
      this.currentPage = 1;
    }
  },

  mounted() {
    this.fetchTeams();
    this.fetchGoals();
  },

  methods: {
    async fetchTeams() {
      try {
        const response = await axios.get("/api/teams");
        this.teams = response.data;
      } catch (err) {
        console.error("Failed to fetch teams", err);
        this.error = "Failed to load teams";
      }
    },

    async fetchGoals() {
      this.loading = true;
      this.error = null;
      
      try {
        let response;
        if (this.selectedTeam === "personal") {
          response = await axios.get("/api/goals/user/allusergoals");
          this.goals = response.data || [];
        } else if (this.selectedTeam === "all") {
          // Fetch personal goals and team goals separately
          const [personalGoalsResponse, teamGoalsResponse] = await Promise.all([
            axios.get("/api/goals/user/allusergoals"),
            axios.get("/api/goals/user/teamgoals")
          ]);
          
          // Combine personal and team goals
          const personalGoals = personalGoalsResponse.data || [];
          const teamGoals = teamGoalsResponse.data || [];
          const allGoals = [...personalGoals, ...teamGoals];
          
          // Remove duplicates (in case a goal appears in both personal and team goals)
          const uniqueGoals = [];
          const seenIds = new Set();
          
          for (const goal of allGoals) {
            if (goal && goal.id && !seenIds.has(goal.id)) {
              seenIds.add(goal.id);
              uniqueGoals.push(goal);
            }
          }
          
          this.goals = uniqueGoals;
        } else {
          // Fetch goals for a specific team
          response = await axios.get(`/api/goals/${this.selectedTeam}`);
          this.goals = response.data || [];
        }
      } catch (err) {
        console.error("Failed to fetch goals", err);
        this.error = "Failed to load goals";
        this.goals = []; // Reset goals on error
      } finally {
        this.loading = false;
      }
    },
    
    // Helper to sort goals based on the selected sort option
    sortGoals(goals) {
      const sortedGoals = [...goals]; // Create a new array to avoid mutating the original
      
      if (this.sortOption === "az") {
        sortedGoals.sort((a, b) => {
          const titleA = a.title || "";
          const titleB = b.title || "";
          return titleA.localeCompare(titleB);
        });
      } else if (this.sortOption === "za") {
        sortedGoals.sort((a, b) => {
          const titleA = a.title || "";
          const titleB = b.title || "";
          return titleB.localeCompare(titleA);
        });
      } else if (this.sortOption === "oldest") {
        sortedGoals.sort((a, b) => {
          const dateA = a.start_time ? new Date(a.start_time) : new Date(0);
          const dateB = b.start_time ? new Date(b.start_time) : new Date(0);
          return dateA - dateB;
        });
      } else if (this.sortOption === "youngest") {
        sortedGoals.sort((a, b) => {
          const dateA = a.start_time ? new Date(a.start_time) : new Date(0);
          const dateB = b.start_time ? new Date(b.start_time) : new Date(0);
          return dateB - dateA;
        });
      }
      
      return sortedGoals;
    },

    openGoalModal(goal) {
      this.selectedGoal = { ...goal };
      this.isModalVisible = true;
      this.isEditing = false;
    },

    closeModal() {
      this.isModalVisible = false;
      this.selectedGoal = null;
      this.isEditing = false;
    },

    startEdit() {
      this.isEditing = true;
    },

    cancelEdit() {
      // Revert to original goal data
      if (this.selectedGoal) {
        const originalGoal = this.findOriginalGoal(this.selectedGoal.id);
        if (originalGoal) {
          this.selectedGoal = { ...originalGoal };
        }
      }
      this.isEditing = false;
    },
    
    // Helper to find the original goal in our data
    findOriginalGoal(goalId) {
      return this.goals.find(g => g.id === goalId);
    },

    async deleteGoal(goalId) {
      if (!confirm("Are you sure you want to delete this goal?")) return;
      try {
        await axios.delete(`/api/goals/${goalId}`);
        
        // Remove the goal from the goals array
        this.goals = this.goals.filter(goal => goal.id !== goalId);
        
        this.closeModal();
      } catch (err) {
        console.error("Failed to delete goal", err);
        alert("Error deleting goal. Please try again.");
      }
    },

    async saveGoal() {
      if (!this.selectedGoal) return;
      
      try {
        const updatedGoal = await axios.put(`/api/goals/${this.selectedGoal.id}`, this.selectedGoal);
        
        // Update the goal in the goals array
        const index = this.goals.findIndex(g => g.id === updatedGoal.data.id);
        if (index !== -1) {
          this.goals[index] = updatedGoal.data;
        }
        
        this.isEditing = false;
      } catch (err) {
        console.error("Failed to save goal", err);
        alert("Error saving goal. Please try again.");
      }
    },

    changePage(page) {
      // Change the current page if the new page is valid
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString();
    },
    
    formatDateTime(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleString();
    },

    togglePriorityFilter(priority) {
      this.priorityFilter = priority;
    },
  },
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