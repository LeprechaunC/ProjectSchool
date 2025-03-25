<template>
  <SideViewDashboard></SideViewDashboard>
  <div class="goal-dashboard">
    <h3 class="text-lg font-semibold mb-4">Goal Calendar</h3>

    <div class="mb-4">
      <label for="team-select" class="block text-sm font-medium text-gray-700">Select Team</label>
      <select
        id="team-select"
        v-model="selectedTeam"
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
      >
        <option value="none">Personal</option>
        <option v-for="team in teams" :key="team.id" :value="team">
          {{ team.name }}
        </option>
      </select>
    </div>
  </div>

  <!-- FullCalendar Component -->
  <FullCalendar :options="calendarOptions" />

  <!-- Modal for Adding New Goal -->
  <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
    <div class="modal">
      <h2 class="text-xl font-semibold mb-4">Add New Goal</h2>
      <form @submit.prevent="submitGoal">
        <div class="mb-4">
          <label for="goal-title" class="block text-sm font-medium text-gray-700">Title</label>
          <input
            id="goal-title"
            v-model="newGoal.title"
            type="text"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <div class="mb-4">
          <label for="goal-description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            id="goal-description"
            v-model="newGoal.description"
            rows="3"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          ></textarea>
        </div>
        <div class="mb-4">
          <label for="goal-priority" class="block text-sm font-medium text-gray-700">Priority</label>
          <select id="goal-priority" v-model="newGoal.priority" class="input-field">
            <option value="high">🔴 High </option>
            <option value="medium">🟡 Medium</option>
            <option value="low">🟢 Low</option>
          </select>
        </div>
        <div class="mb-4">
          <label for="goal-start-time" class="block text-sm font-medium text-gray-700">Start Time</label>
          <input
            id="goal-start-time"
            v-model="newGoal.start_time"
            type="datetime-local"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <div class="mb-4">
          <label for="goal-end-time" class="block text-sm font-medium text-gray-700">End Time</label>
          <input
            id="goal-end-time"
            v-model="newGoal.end_time"
            type="datetime-local"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <button
          type="submit"
          class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
          Add Goal
        </button>
      </form>
      <button
        @click="showModal = false"
        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
      >
        ×
      </button>
    </div>
  </div>

  <FullscreenGoalModal
    :isVisible="fullscreenModalVisible"
    :selectedGoal="selectedGoal"
    @closeModal="closeFullscreenModal"        
  />
</template>

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
