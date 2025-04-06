<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <SideViewDashboard></SideViewDashboard>
    <div class="p-6 lg:p-8">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
          <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Goal Calendar</h3>
            <p class="text-gray-600 dark:text-gray-400">Manage and track your goals efficiently</p>
          </div>
          <div class="mt-4 md:mt-0 w-full md:w-auto">
            <label for="team-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Team</label>
            <select
              id="team-select"
              v-model="selectedTeam"
              class="w-full md:w-64 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="none">Personal</option>
              <option v-for="team in teams" :key="team.id" :value="team">
                {{ team.name }}
              </option>
            </select>
          </div>
        </div>

        <!-- FullCalendar Component -->
        <div class="calendar-container bg-white dark:bg-gray-800 rounded-lg shadow">
          <FullCalendar :options="calendarOptions" class="p-4" />
        </div>
      </div>
    </div>

    <!-- Modal for Adding New Goal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full p-6 relative">
        <button
          @click="showModal = false"
          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Add New Goal</h2>
        <form @submit.prevent="submitGoal" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="goal-title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
              <input
                id="goal-title"
                v-model="newGoal.title"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              />
            </div>
            <div>
              <label for="goal-priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
              <select 
                id="goal-priority" 
                v-model="newGoal.priority" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              >
                <option value="high">🔴 High</option>
                <option value="medium">🟡 Medium</option>
                <option value="low">🟢 Low</option>
              </select>
            </div>
          </div>

          <div>
            <label for="goal-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
            <textarea
              id="goal-description"
              v-model="newGoal.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="goal-start-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Time</label>
              <input
                id="goal-start-time"
                v-model="newGoal.start_time"
                type="datetime-local"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              />
            </div>
            <div>
              <label for="goal-end-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Time</label>
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
              Add Goal
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

// ... existing code ...

<style scoped>
.calendar-container {
  overflow: hidden;
  border-radius: 0.5rem;
}

:deep(.fc) {
  font-family: inherit;
}

:deep(.fc-toolbar-title) {
  font-size: 1.25rem !important;
  font-weight: 600 !important;
}

:deep(.fc-button-primary) {
  background-color: #3b82f6 !important;
  border-color: #3b82f6 !important;
}

:deep(.fc-button-primary:hover) {
  background-color: #2563eb !important;
  border-color: #2563eb !important;
}

:deep(.fc-daygrid-day) {
  min-height: 120px;
}

:deep(.fc-event) {
  border-radius: 0.375rem;
  padding: 0.25rem;
  margin: 0.125rem 0;
  border: none;
  background-color: #3b82f6;
  color: white;
}

:deep(.fc-event-title) {
  font-weight: 500;
}

:deep(.fc-daygrid-day-number) {
  font-weight: 500;
  color: #6b7280;
}

:deep(.fc-day-today) {
  background-color: #eff6ff !important;
}

:deep(.fc-day-today .fc-daygrid-day-number) {
  background-color: #3b82f6;
  color: white;
  border-radius: 9999px;
  padding: 0.25rem 0.5rem;
}

/* Dark mode styles */
:deep(.dark .fc) {
  background-color: #1f2937;
  color: #f3f4f6;
}

:deep(.dark .fc-theme-standard td),
:deep(.dark .fc-theme-standard th) {
  border-color: #374151;
}

:deep(.dark .fc-daygrid-day) {
  background-color: #1f2937;
}

:deep(.dark .fc-day-today) {
  background-color: #1e40af !important;
}

:deep(.dark .fc-daygrid-day-number) {
  color: #9ca3af;
}

:deep(.dark .fc-toolbar-title) {
  color: #f3f4f6;
}

:deep(.dark .fc-button-primary) {
  background-color: #3b82f6 !important;
  border-color: #3b82f6 !important;
}

:deep(.dark .fc-button-primary:hover) {
  background-color: #2563eb !important;
  border-color: #2563eb !important;
}
</style>
  <script>
  import axios from "axios";
  import FullCalendar from "@fullcalendar/vue3";
  import dayGridPlugin from "@fullcalendar/daygrid";
  import interactionPlugin from "@fullcalendar/interaction";
  import FullscreenGoalModal from "../Goal/FullScreenGoal.vue"; // Import the new component
  import SideViewDashboard from "../Goal/SideViewDashboard.vue";
  export default {
    components: {
      FullCalendar,
      FullscreenGoalModal,
      SideViewDashboard
    },
    data() {
      return {
        teams: [],
        selectedTeam: JSON.parse(localStorage.getItem("selectedTeam")) || "none",
        goals: [],
        calendarOptions: {
          plugins: [dayGridPlugin, interactionPlugin],
          initialView: "dayGridMonth",
  
    
          dateClick: this.handleDateClick,
          eventClick: this.handleEventClick,
          eventContent: this.renderEventContent, // Custom event rendering
          events: [],
        },
        showModal: false,
        fullscreenModalVisible: false,
        selectedGoal: null,
        newGoal: {
          title: "",
          description: "",
          start_time: "",
          end_time: "",
        },
        selectedDate: null,
      };
    },
    mounted() {
      this.fetchTeams();
      if (this.selectedTeam) {
        this.fetchGoals();
      }
    },
    methods: {
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
            this.updateCalendarEvents();
          })
          .catch((error) => {
            console.error("Error fetching goals:", error);
          });
      },

      updateCalendarEvents() {
        this.calendarOptions.events = this.goals.map((goal) => ({
          id: goal.id,
          title: goal.title,
          description: goal.description,
          start: goal.start_time,  // Ensure this is passed as the event's start time
          end: goal.end_time,      // Ensure this is passed as the event's end time
          goalId: goal.id,
          done: goal.done,         // Include goal completion status
        }));
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
            this.updateCalendarEvents(); // Refresh calendar events with updated status
          })
          .catch((error) => {
            console.error("Error updating goal status:", error);
          });
      },
      handleDateClick(info) {
    // Extract clicked date and ensure correct time zone handling
    const clickedDate = new Date(info.dateStr + "T00:00:00Z"); // Forces UTC interpretation

    // Convert to local date string without shifting the day
    const localDateStr = clickedDate.toISOString().split("T")[0];

    // Set start and end times explicitly
    this.newGoal.start_time = `${localDateStr}T00:00`;
    this.newGoal.end_time = `${localDateStr}T23:59`;

    this.selectedDate = localDateStr;
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
      alert("Error: Start date cannot be after the end date.");
      return;
    }

    if (startDate.getFullYear() > endDate.getFullYear()) {
      alert("Error: The start year cannot be after the end year.");
      return;
    }

    if (startDate.getFullYear() < currentDate.getFullYear() - 1) {
      alert("Error: Start date is too far in the past.");
      return;
    }

    const teamId =
      this.selectedTeam && this.selectedTeam !== "none"
        ? this.selectedTeam.id
        : null;

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

    axios
      .post("/api/goals", goalData)
      .then((response) => {
        this.goals.push(response.data);
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
        };
        this.selectedDate = null;
      },

      closeFullscreenModal() {
        this.fullscreenModalVisible = false;
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
  };
  </script>

  <style scoped>

  .dashboard-container {
    display: flex;
    align-items: flex-start; /* Align items to top */
  }

  .side-view {
    width: 250px; /* Adjust as needed */
    min-height: 100vh; /* Full height */
    background: #f3f4f6; /* Light gray background */
  }
  .goal-dashboard {
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .fullcalendar {
    max-width: 100%;
    margin: 0 auto;
  }

  select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  /* Modal styles */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99;
  }

  .modal {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    position: relative;
  }

  </style>
