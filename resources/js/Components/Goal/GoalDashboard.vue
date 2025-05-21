<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="flex">
      <!-- SideViewDashboard with proper integration -->
      <SideViewDashboard 
        @filter-applied="applyFilters" 
        ref="sideView"
      />
      
      <div class="flex-1 p-6 lg:p-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Mērķu Kalendārs</h3>
              <p class="text-gray-600 dark:text-gray-400">Efektīvi pārvaldiet un izsekojiet savus mērķus</p>
              <div v-if="upcomingGoalsCount > 0" class="mt-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ upcomingGoalsCount }} mērķ{{ upcomingGoalsCount === 1 ? 'is' : 'i' }} jāizpilda nākamo 24 stundu laikā
                </span>
              </div>
            </div>
            <div class="mt-4 md:mt-0 w-full md:w-auto">
              <label for="team-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Izvēlieties Komandu</label>
              <select
                id="team-select"
                v-model="selectedTeam"
                class="w-full md:w-64 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              >
                <option value="none">Personīgi</option>
                <option v-for="team in teams" :key="team.id" :value="team">
                  {{ team.name }}
                </option>
              </select>
            </div>
          </div>

          <!-- FullCalendar Component -->
          <div class="calendar-container bg-white dark:bg-gray-800 rounded-lg shadow">
            <!-- Priority Legend -->
            <div class="p-4 flex flex-wrap gap-4 border-b border-gray-200 dark:border-gray-700">
              <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                <span class="text-sm font-medium">Augsta Prioritāte</span>
              </div>
              <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-amber-500 mr-2"></div>
                <span class="text-sm font-medium">Vidēja Prioritāte</span>
              </div>
              <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-emerald-500 mr-2"></div>
                <span class="text-sm font-medium">Zema Prioritāte</span>
              </div>
              
            </div>
            <FullCalendar ref="fullCalendar" :options="calendarOptions" class="p-4" />
          </div>

          <!-- Add this after the calendar container div -->
          <div class="mt-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">Komandas Čats</h3>
              <button 
                @click="showChat = !showChat" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                {{ showChat ? 'Slēpt Čatu' : 'Rādīt Čatu' }}
              </button>
            </div>
            
            <div v-if="showChat && selectedTeam !== 'none'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden h-[400px]">
              <TeamChat :team="selectedTeam" />
            </div>
            <div v-else-if="showChat && selectedTeam === 'none'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center">
              <p class="text-gray-600 dark:text-gray-400">Izvēlieties komandu, lai skatītu čatu.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for Adding New Goal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" @click.self="showModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full p-6 relative">
        <button
          @click="showModal = false"
          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Pievienot Jaunu Mērķi</h2>
        <form @submit.prevent="submitGoal" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="goal-title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nosaukums</label>
              <input
                id="goal-title"
                v-model="newGoal.title"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              />
            </div>
            <div>
              <label for="goal-priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prioritāte</label>
              <select 
                id="goal-priority" 
                v-model="newGoal.priority" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              >
                <option value="high">🔴 Augsta</option>
                <option value="medium">🟡 Vidēja</option>
                <option value="low">🟢 Zema</option>
              </select>
            </div>
          </div>

          <div>
            <label for="goal-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Apraksts</label>
            <textarea
              id="goal-description"
              v-model="newGoal.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="goal-start-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sākuma Laiks</label>
              <input
                id="goal-start-time"
                v-model="newGoal.start_time"
                type="datetime-local"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              />
            </div>
            <div>
              <label for="goal-end-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Beigu Laiks</label>
              <input
                id="goal-end-time"
                v-model="newGoal.end_time"
                type="datetime-local"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              />
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
            >
              Pievienot Mērķi
            </button>
          </div>
        </form>
      </div>
    </div>

    <FullscreenGoalModal
      :isVisible="fullscreenModalVisible"
      :selectedGoal="selectedGoal"
      @closeModal="closeFullscreenModal"        
    />
  </div>
</template>

<script>
import axios from "axios";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import FullscreenGoalModal from "../Goal/FullScreenGoal.vue"; // Import the new component
import SideViewDashboard from "../Goal/SideViewDashboard.vue";
import TeamChat from "./TeamChat.vue";

export default {
  components: {
    FullCalendar,
    FullscreenGoalModal,
    SideViewDashboard,
    TeamChat
  },
  data() {
    return {
      teams: [],
      selectedTeam: JSON.parse(localStorage.getItem("selectedTeam")) || "none",
      goals: [],
      filteredGoals: [], // New array to store filtered goals
      calendarOptions: {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,dayGridWeek,timeGridDay'
        },
        buttonText: {
          today: 'Šodien',
          month: 'Mēnesis',
          week: 'Nedēļa',
          day: 'Diena'
        },
        firstDay: 1, // Start week on Monday
        height: 'auto',
        aspectRatio: 1.8,
        expandRows: true,
        stickyHeaderDates: true,
        dayMaxEvents: true,
        dateClick: this.handleDateClick,
        eventClick: this.handleEventClick,
        eventContent: this.renderEventContent,
        events: [],
        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          meridiem: false
        },
        eventDisplay: 'block',
        slotMinTime: '00:00:00',
        slotMaxTime: '23:59:59',
        slotDuration: '00:30:00',
        slotLabelInterval: '01:00',
        slotLabelFormat: {
          hour: '2-digit',
          minute: '2-digit',
          meridiem: false
        },
        eventDidMount: (info) => {
          // Debug logging
          console.log('Event mounted:', {
            title: info.event.title,
            priority: info.event.extendedProps.priority,
            rawPriority: info.event.extendedProps
          });
          
          // Add tooltip
          const tooltip = document.createElement('div');
          tooltip.className = 'fc-tooltip';
          tooltip.innerHTML = `
            <div class="p-2">
              <div class="font-semibold">${info.event.title}</div>
              <div class="text-sm">${info.event.extendedProps.description || ''}</div>
            </div>
          `;
          info.el.appendChild(tooltip);
          
          // Apply priority-based styling
          const priority = info.event.extendedProps.priority || 'medium';
          console.log('Using priority:', priority); // Debug log
          
          const priorityColors = {
            high: {
              backgroundColor: '#ef4444', // red-500
              borderColor: '#dc2626', // red-600
              textColor: '#ffffff'
            },
            medium: {
              backgroundColor: '#f59e0b', // amber-500
              borderColor: '#d97706', // amber-600
              textColor: '#ffffff'
            },
            low: {
              backgroundColor: '#10b981', // emerald-500
              borderColor: '#059669', // emerald-600
              textColor: '#ffffff'
            }
          };
          
          const colors = priorityColors[priority] || priorityColors.medium;
          console.log('Selected colors:', colors); // Debug log
          
          // Apply colors to the event element
          info.el.style.backgroundColor = colors.backgroundColor;
          info.el.style.borderColor = colors.borderColor;
          info.el.style.color = colors.textColor;
          
          // Add priority indicator dot - with safety check
          const titleElement = info.el.querySelector('.fc-event-title');
          if (titleElement) {
            const dot = document.createElement('div');
            dot.className = 'priority-dot';
            dot.style.width = '8px';
            dot.style.height = '8px';
            dot.style.borderRadius = '50%';
            dot.style.marginRight = '4px';
            dot.style.display = 'inline-block';
            dot.style.backgroundColor = colors.backgroundColor;
            titleElement.prepend(dot);
          }
        }
      },
      showModal: false,
      fullscreenModalVisible: false,
      selectedGoal: null,
      newGoal: {
        title: "",
        description: "",
        start_time: "",
        end_time: "",
        priority: "medium", // Add default priority
      },
      selectedDate: null,
      showChat: false,
      // Filter state
      activeFilters: {
        priorities: [],
        dateRange: { start: "", end: "" },
        labels: []
      },
      isDayView: false,
      calendarApi: null,
      resizeTimeout: null
    };
  },
  mounted() {
    this.fetchTeams();
    if (this.selectedTeam) {
      this.fetchGoals();
    }
    
    // Configure calendar for mobile if needed
    this.configureCalendarForDevice();
    
    // Add window resize listener for orientation changes
    window.addEventListener('resize', this.handleResize);
    
    // Get the calendar API after the component is mounted
    this.$nextTick(() => {
      // Allow more time for the calendar to fully initialize
      setTimeout(() => {
        this.initializeCalendarApi();
        // Configure calendar again with API
        this.configureCalendarForDevice();
      }, 500);
    });
  },
  beforeUnmount() {
    // Clean up resize listener
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {
    handleResize() {
      // Debounce resize events
      if (this.resizeTimeout) {
        clearTimeout(this.resizeTimeout);
      }
      
      this.resizeTimeout = setTimeout(() => {
        this.configureCalendarForDevice();
        
        // Update the calendar if the API is available
        if (this.calendarApi) {
          this.calendarApi.updateSize();
        }
      }, 250);
    },
    configureCalendarForDevice() {
      // Check if we're on a mobile device
      const isMobile = window.innerWidth < 768;
      
      if (isMobile) {
        // Simplified header for mobile
        this.calendarOptions.headerToolbar = {
          left: 'prev,next',
          center: 'title',
          right: 'dayGridMonth,timeGridDay'
        };
        
        // Shorter button text for mobile
        this.calendarOptions.buttonText = {
          today: 'Šodien',
          month: 'Mēnesis',
          day: 'Diena'
        };
      } else {
        // Restore full header for desktop
        this.calendarOptions.headerToolbar = {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,dayGridWeek,timeGridDay'
        };
        
        // Restore full button text
        this.calendarOptions.buttonText = {
          today: 'Šodien',
          month: 'Mēnesis',
          week: 'Nedēļa',
          day: 'Diena'
        };
      }
      
      // Apply changes to calendar if API is available
      if (this.calendarApi) {
        this.calendarApi.setOption('headerToolbar', this.calendarOptions.headerToolbar);
        this.calendarApi.setOption('buttonText', this.calendarOptions.buttonText);
      }
    },
    
    fetchTeams() {
      axios
        .post("/teams")
        .then((response) => {
          this.teams = response.data;
        })
        .catch((error) => {
          console.error("Error fetching teams:", error);
        });
    },

    fetchGoals() {
      const url =
        this.selectedTeam === "none"
          ? "/api/goals/user/allusergoals"
          : `/api/goals/${this.selectedTeam.id}`;

      axios
        .get(url)
        .then((response) => {
          this.goals = response.data;
          
          // Check if we have active filters to apply
          const hasActiveFilters = 
            (this.activeFilters.priorities && this.activeFilters.priorities.length > 0) ||
            this.activeFilters.dateRange.start || 
            this.activeFilters.dateRange.end ||
            (this.activeFilters.labels && this.activeFilters.labels.length > 0);
            
          if (hasActiveFilters) {
            // Apply existing filters to the new team's goals
            this.filterGoalsLocally(this.activeFilters);
          } else {
            // If no filters, just show all goals
            this.filteredGoals = [...this.goals];
            this.updateCalendarEvents();
          }
        })
        .catch((error) => {
          console.error("Error fetching goals:", error);
        });
    },

    updateCalendarEvents() {
      console.log('Raw goals data:', this.filteredGoals); // Debug log
      this.calendarOptions.events = this.filteredGoals.map((goal) => {
        console.log('Processing goal:', {
          id: goal.id,
          title: goal.title,
          priority: goal.priority,
          rawPriority: goal
        });
        return {
          id: goal.id,
          title: goal.title,
          description: goal.description,
          start: goal.start_time,
          end: goal.end_time,
          goalId: goal.id,
          done: goal.done,
          priority: goal.priority || 'medium',
        };
      });
      console.log('Final calendar events:', this.calendarOptions.events); // Debug log
    },

    // Apply filters from SideViewDashboard
    applyFilters(filters) {
      console.log('Applying filters:', filters);
      this.activeFilters = filters;
      
      // If all filters are empty, reset to show all goals
      const hasNoFilters = (!filters.priorities || filters.priorities.length === 0) && 
                          !filters.dateRange.start && 
                          !filters.dateRange.end && 
                          (!filters.labels || filters.labels.length === 0);
      
      if (hasNoFilters) {
        console.log('No filters active, showing all goals');
        this.filteredGoals = [...this.goals];
        this.updateCalendarEvents();
        return;
      }
      
      // Filter goals locally for better performance and reliability
      this.filterGoalsLocally(filters);
    },
    
    // Client-side filtering for all goals
    filterGoalsLocally(filters) {
      console.log('Filtering goals locally with filters:', filters);
      // Start with all goals
      let filtered = [...this.goals];
      
      // Apply priority filter (if any priorities are selected)
      if (filters.priorities && filters.priorities.length > 0) {
        filtered = filtered.filter(goal => {
          // If goal has no priority, default to medium
          const goalPriority = goal.priority || 'medium';
          return filters.priorities.includes(goalPriority);
        });
      }
      
      // Apply date range filter
      if (filters.dateRange.start || filters.dateRange.end) {
        const startDate = filters.dateRange.start ? new Date(filters.dateRange.start) : null;
        const endDate = filters.dateRange.end ? new Date(filters.dateRange.end) : null;
        
        if (startDate) startDate.setHours(0, 0, 0, 0); // Start of day
        if (endDate) endDate.setHours(23, 59, 59, 999); // End of day
        
        filtered = filtered.filter(goal => {
          const goalStartDate = new Date(goal.start_time);
          const goalEndDate = new Date(goal.end_time);
          
          // Start date filter (if provided)
          if (startDate && goalEndDate < startDate) {
            return false; // Goal ends before filter start date
          }
          
          // End date filter (if provided)
          if (endDate && goalStartDate > endDate) {
            return false; // Goal starts after filter end date
          }
          
          return true;
        });
      }
      
      // Apply labels filter (if implemented and any labels selected)
      if (filters.labels && filters.labels.length > 0) {
        filtered = filtered.filter(goal => {
          // This implementation assumes goals might have a labels array
          // If you don't have labels implemented yet, this won't filter anything
          if (!goal.labels) return false;
          
          // Check if any of the selected labels match the goal's labels
          return filters.labels.some(label => 
            goal.labels.includes(label)
          );
        });
      }
      
      console.log(`Filtered from ${this.goals.length} to ${filtered.length} goals`);
      
      // Update filtered goals
      this.filteredGoals = filtered;
      
      // Update calendar events
      this.updateCalendarEvents();
    },

    renderEventContent(arg) {
      const goalId = arg.event.extendedProps.goalId;
      const isDone = arg.event.extendedProps.done;

      // Create container div
      const container = document.createElement("div");
      container.style.display = "flex";
      container.style.alignItems = "center"; // Ensures vertical alignment
      container.style.justifyContent = "space-between"; // Aligns items to the edges
      container.style.width = "100%"; // Ensures full width

      // Create text node for event title
      const title = document.createElement("span");
      title.innerText = arg.event.title;

      // Create button element
      const button = document.createElement("button");
      button.innerText = isDone ? "✔" : "✖";
      button.style.padding = "2px 6px";
      button.style.border = "none";
      button.style.cursor = "pointer";
      button.style.color = isDone ? "green" : "red";
      button.onclick = (e) => {
        e.stopPropagation();
        this.toggleGoalStatus(goalId, !isDone);
      };

      // Append elements to container
      container.appendChild(title);
      container.appendChild(button);

      return { domNodes: [container] };
    },

    toggleGoalStatus(goalId, newStatus) {
      axios
        .patch(`/api/goals/${goalId}/done`, { done: newStatus }) // Send PATCH request to toggle status
        .then(() => {
          // Update the local goals array with the new status
          this.goals = this.goals.map((goal) =>
            goal.id === goalId ? { ...goal, done: newStatus } : goal
          );
          
          // Also update filtered goals
          this.filteredGoals = this.filteredGoals.map((goal) =>
            goal.id === goalId ? { ...goal, done: newStatus } : goal
          );
          
          this.updateCalendarEvents(); // Refresh calendar events with updated status
        })
        .catch((error) => {
          console.error("Error updating goal status:", error);
        });
    },
    handleDateClick(info) {
      // Extract clicked date with better handling for different view types
      let dateStr, timeStr;
      
      // Handle different date formats based on view type
      if (info.view.type === 'timeGridDay' || info.view.type === 'timeGridWeek') {
        // For day/week view, we have allDay and date (a Date object)
        const clickedDate = info.date;
        dateStr = clickedDate.toISOString().split('T')[0];
        
        // Extract time from the clicked slot
        const hours = clickedDate.getHours().toString().padStart(2, '0');
        const minutes = clickedDate.getMinutes().toString().padStart(2, '0');
        timeStr = `${hours}:${minutes}`;
        
        // Calculate end time (1 hour later)
        const endDate = new Date(clickedDate);
        endDate.setHours(endDate.getHours() + 1);
        const endHours = endDate.getHours().toString().padStart(2, '0');
        const endMinutes = endDate.getMinutes().toString().padStart(2, '0');
        const endTimeStr = `${endHours}:${endMinutes}`;
        
        // Set start and end times for the new goal
        this.newGoal.start_time = `${dateStr}T${timeStr}`;
        this.newGoal.end_time = `${dateStr}T${endTimeStr}`;
      } else {
        // For month view (dayGrid)
        const clickedDate = new Date(info.dateStr + "T00:00:00Z"); // Forces UTC interpretation
        dateStr = clickedDate.toISOString().split("T")[0];
        
        // Set start and end times explicitly
        this.newGoal.start_time = `${dateStr}T00:00`;
        this.newGoal.end_time = `${dateStr}T23:59`;
      }

      this.selectedDate = dateStr;
      this.showModal = true;
    },

    handleEventClick(info) {
      const goalId = info.event.extendedProps.goalId;
      const clickedGoal = this.goals.find((goal) => goal.id === goalId);

      if (clickedGoal) {
        this.selectedGoal = clickedGoal;
        this.fullscreenModalVisible = true;
      }
    },

    submitGoal() {
      if (
        !this.selectedTeam ||
        !this.newGoal.title ||
        !this.newGoal.start_time ||
        !this.newGoal.end_time
      ) {
        alert("Please fill in all fields.");
        return;
      }

      const startDate = new Date(this.newGoal.start_time);
      const endDate = new Date(this.newGoal.end_time);
      const currentDate = new Date();

      // Validation checks
      if (startDate > endDate) {
        this.showNotification('Start date cannot be after the end date.', 'error');
        return;
      }

      if (startDate.getFullYear() > endDate.getFullYear()) {
        this.showNotification('The start year cannot be after the end year.', 'error');
        return;
      }

      if (startDate.getFullYear() < currentDate.getFullYear() - 1) {
        this.showNotification('Start date is too far in the past.', 'error');
        return;
      }

      const teamId =
        this.selectedTeam && this.selectedTeam !== "none"
          ? this.selectedTeam.id
          : null;

      console.log('Submitting goal with priority:', this.newGoal.priority); // Debug log

      const goalData = {
        title: this.newGoal.title,
        description: this.newGoal.description,
        priority: this.newGoal.priority,  
        start_time: this.newGoal.start_time,
        end_time: this.newGoal.end_time,
        team_id: teamId,
        user_id: null,
        done: false
      };

      console.log('Goal data being sent:', goalData); // Debug log

      axios
        .post("/api/goals", goalData)
        .then((response) => {
          this.goals.push(response.data);
          this.filteredGoals.push(response.data); // Add to filtered goals too
          this.fetchGoals();
          this.resetGoalForm();
          this.showModal = false;
        })
        .catch((error) => {
          console.error("Error submitting goal:", error);
        });
    },

    resetGoalForm() {
      this.newGoal = {
        title: "",
        description: "",
        start_time: "",
        end_time: "",
        priority: "medium", // Reset to default priority
      };
      this.selectedDate = null;
    },

    closeFullscreenModal() {
      this.fullscreenModalVisible = false;
    },

    goToToday() {
      if (this.calendarApi) {
        this.calendarApi.today();
      } else {
        console.error('Calendar API not available');
      }
    },

    toggleDayView() {
      this.isDayView = !this.isDayView;
      if (this.calendarApi) {
        if (this.isDayView) {
          this.calendarApi.changeView('timeGridDay');
        } else {
          this.calendarApi.changeView('dayGridMonth');
        }
      } else {
        console.error('Calendar API not available');
      }
    },

    initializeCalendarApi() {
      // Modern way to access FullCalendar API in Vue 3
      try {
        // Direct reference approach (most reliable)
        const fcComponent = this.$refs.fullCalendar;
        if (fcComponent) {
          this.calendarApi = fcComponent.getApi();
          console.log('Calendar API initialized successfully via ref');
          return;
        }
        
        // Fallback to DOM approach if ref not available
        const calendarEl = document.querySelector('.fc');
        if (calendarEl) {
          // Try Vue 3 approach
          const fcInstance = calendarEl.__vnode;
          if (fcInstance) {
            console.log('Found calendar element but could not get API directly');
            
            // Schedule another attempt
            setTimeout(() => {
              const fc = this.$refs.fullCalendar;
              if (fc) {
                this.calendarApi = fc.getApi();
                console.log('Calendar API initialized successfully on retry');
              }
            }, 500);
          }
        } else {
          console.warn('Calendar element not found in DOM');
          
          // Final fallback - try again later
          setTimeout(() => this.initializeCalendarApi(), 1000);
        }
      } catch (err) {
        console.error('Error initializing calendar API:', err);
        
        // One last retry with a longer delay
        setTimeout(() => {
          try {
            const fc = this.$refs.fullCalendar;
            if (fc) {
              this.calendarApi = fc.getApi();
              console.log('Calendar API initialized on final retry');
            }
          } catch (e) {
            console.error('Failed to initialize calendar API after retries');
          }
        }, 2000);
      }
    },

    showNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 ${
        type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 'bg-green-100 border border-green-400 text-green-700'
      }`;
      notification.innerHTML = `
        <div class="flex items-center">
          <span class="mr-2">${type === 'error' ? '✕' : '✓'}</span>
          <span>${message}</span>
        </div>
      `;
      document.body.appendChild(notification);
      setTimeout(() => {
        notification.remove();
      }, 3000);
    },
  },
  watch: {
    selectedTeam(newTeam) {
      if (newTeam) {
        localStorage.setItem("selectedTeam", JSON.stringify(newTeam));
        this.fetchGoals();
      }
    },
  },
  computed: {
    upcomingGoalsCount() {
      const now = new Date();
      const twentyFourHoursFromNow = new Date(now.getTime() + 24 * 60 * 60 * 1000);
      
      return this.filteredGoals.filter(goal => {
        const endTime = new Date(goal.end_time);
        return endTime >= now && endTime <= twentyFourHoursFromNow;
      }).length;
    }
  },
};
</script>

<style scoped>
.dark .fc-scrollgrid-sync-inner {
  background-color: red;
}
.dark .p-4 {
  background-color: #1f2937;
}
.dark  thead {
 
background-color: #1f2937;
 
}
 .dark .fc {
    background-color: #1f2937 !important; /* Tailwind's gray-800 */
    color: white;
  }

  .dark .fc .fc-daygrid-day-number {
    color: white;
  }

  .dark .fc .fc-toolbar-title {
    color: white;
  }
  
.calendar-container {
  overflow: hidden;
  border-radius: 0.5rem;
  background: white;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  touch-action: pan-y pinch-zoom; /* Improve touch handling */
}

/* Add Goal Modal Styles */
.fixed.inset-0 {
  cursor: pointer;
}

.fixed.inset-0 > div {
  cursor: default;
}

 
 
</style>
