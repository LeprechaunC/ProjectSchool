<template>
  <div>
    <div v-if="loading">
      <p>Loading...</p>
    </div>

    <div v-else>
      <label for="team-select">Select Team:</label>
      <select v-model="selectedTeam" @change="fetchGoals" id="team-select" class="border p-2 mb-2 w-full">
        <option value="personal">Personal</option>
        <option v-for="team in teams" :key="team.id" :value="team.id">
          {{ team.name }}
        </option>
      </select>

      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search goals..."
        class="border p-2 mb-2 w-full"
      />

      <!-- Sorting Options -->
      <label for="sort-select">Sort By:</label>
      <select v-model="sortOption" id="sort-select" class="border p-2 mb-2 w-full">
        <option value="az">Title (A-Z)</option>
        <option value="za">Title (Z-A)</option>
        <option value="oldest">Start Date (Oldest First)</option>
        <option value="youngest">Start Date (Youngest First)</option>
      </select>

      <div v-if="filteredGoals.length > 0">
        <h3>Goals for: {{ selectedTeamName }}</h3>
        <ul>
          <li v-for="goal in paginatedGoals" :key="goal.id" @click="openGoalModal(goal)">
            <h4>{{ goal.title }}</h4>
            <p>{{ goal.description }}</p>
            <p><strong>Start Time:</strong> {{ goal.start_time }}</p>
            <p><strong>End Time:</strong> {{ goal.end_time }}</p>
          </li>
        </ul>

        <!-- Pagination Controls -->
        <div v-if="totalPages > 1" class="pagination">
          <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1">Previous</button>
          <span>Page {{ currentPage }} of {{ totalPages }}</span>
          <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">Next</button>
        </div>
      </div>

      <div v-else>
        <p>No goals available for the selected option.</p>
      </div>
    </div>

    <!-- Fullscreen Modal -->
    <div v-if="isModalVisible" class="fullscreen-modal-overlay" @click.self="closeModal">
      <div class="fullscreen-modal">
        <button @click="closeModal" class="close-button">&times;</button>

        <h2 class="text-xl font-semibold mb-4">{{ selectedGoal.title }}</h2>

        <div v-if="isEditing">
          <input v-model="selectedGoal.title" class="border p-2 mb-2 w-full" placeholder="Goal Title" />
          <textarea v-model="selectedGoal.description" class="border p-2 mb-2 w-full" placeholder="Goal Description"></textarea>
          <input v-model="selectedGoal.start_time" type="datetime-local" class="border p-2 mb-2 w-full" />
          <input v-model="selectedGoal.end_time" type="datetime-local" class="border p-2 mb-2 w-full" />

          <div class="mt-4 flex justify-between space-x-2">
            <button @click="saveGoal" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
            <button @click="cancelEdit" class="px-4 py-2 bg-gray-600 text-white rounded">Cancel</button>
          </div>
        </div>

        <div v-else>
          <p>{{ selectedGoal.description }}</p>
          <p><strong>Start Time:</strong> {{ selectedGoal.start_time }}</p>
          <p><strong>End Time:</strong> {{ selectedGoal.end_time }}</p>

          <div class="mt-4 flex justify-end space-x-2">
            <button @click="startEdit" class="px-4 py-2 bg-blue-600 text-white rounded">Edit</button>
            <button @click="deleteGoal(selectedGoal.id)" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            <button @click="closeModal" class="px-4 py-2 bg-gray-600 text-white rounded">Back</button>
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
      teams: [],
      goals: [],
      selectedTeam: "personal",
      searchQuery: "",
      loading: true,
      error: null,
      sortOption: "az", // Default sorting option
      isModalVisible: false,
      isEditing: false,
      selectedGoal: null,
      currentPage: 1,  // Start on page 1
      goalsPerPage: 5, // Show 5 goals per page
    };
  },

  computed: {
    filteredGoals() {
      // First, filter by search query
      let filtered = this.goals.filter(
        (goal) =>
          goal.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          goal.description.toLowerCase().includes(this.searchQuery.toLowerCase())
      );

      // Then, apply sorting based on the selected option
      if (this.sortOption === "az") {
        filtered = filtered.sort((a, b) => a.title.localeCompare(b.title));
      } else if (this.sortOption === "za") {
        filtered = filtered.sort((a, b) => b.title.localeCompare(a.title));
      } else if (this.sortOption === "oldest") {
        filtered = filtered.sort((a, b) => new Date(a.start_time) - new Date(b.start_time));
      } else if (this.sortOption === "youngest") {
        filtered = filtered.sort((a, b) => new Date(b.start_time) - new Date(a.start_time));
      }

      return filtered;
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
      const team = this.teams.find((t) => t.id === this.selectedTeam);
      return team ? team.name : "All Teams";
    },
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
      try {
        const response = this.selectedTeam === "personal"
          ? await axios.get("/api/goals/user/allusergoals")
          : await axios.get(`/api/goals/${this.selectedTeam}`);

        this.goals = response.data;
      } catch (err) {
        console.error("Failed to fetch goals", err);
        this.error = "Failed to load goals";
      } finally {
        this.loading = false;
      }
    },

    openGoalModal(goal) {
      this.selectedGoal = { ...goal };
      this.isModalVisible = true;
      this.isEditing = false;
    },

    closeModal() {
      this.isModalVisible = false;
      this.selectedGoal = null;
    },

    startEdit() {
      this.isEditing = true;
    },

    cancelEdit() {
      this.isEditing = false;
    },

    async deleteGoal(goalId) {
      if (!confirm("Are you sure you want to delete this goal?")) return;
      try {
        await axios.delete(`/api/goals/${goalId}`);
        this.goals = this.goals.filter((goal) => goal.id !== goalId);
        this.closeModal();
      } catch (err) {
        console.error("Failed to delete goal", err);
        alert("Error deleting goal. Please try again.");
      }
    },

    saveGoal() {
      // Implement API call to save the edited goal
      alert("Goal saved! (Implement API call here)");
      this.isEditing = false;
    },

    changePage(page) {
      // Change the current page if the new page is valid
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
  },
};
</script>

<style scoped>
.pagination {
  display: flex;
  justify-content: center;
  margin-top: 10px;
}
.pagination button {
  padding: 5px 10px;
  margin: 0 5px;
}
</style>




<style scoped>
.fullscreen-modal-overlay {
  position: fixed; /* Fix position to the screen */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
  display: flex;
  justify-content: center;
  align-items: center; /* Center content vertically */
  z-index: 9999; /* Ensure it's on top of other content */
}

.fullscreen-modal {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 80%;
  max-width: 600px; /* You can adjust this value to control the width */
  position: relative;
  overflow-y: auto;
  
  z-index: 10000; /* Ensure the modal itself is above the overlay */
}


/* Close Button */
.close-button {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  font-size: 24px;
  color: #333;
  cursor: pointer;
}
/* General Styles */
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f4f7f6;
}

h3 {
  font-size: 24px;
  color: #333;
}

h4 {
  font-size: 18px;
  margin: 10px 0;
  color: #555;
}

p {
  font-size: 14px;
  color: #666;
}

/* Team Selection Styles */
#team-select {
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin: 10px 0;
  width: 100%;
  max-width: 300px;
  background-color: #fff;
}

label {
  font-size: 16px;
  color: #333;
  display: block;
  margin-bottom: 5px;
}

/* Goal List Styles */
ul {
  list-style-type: none;
  padding: 0;
}

li {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  margin: 10px 0;
  padding: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s;
}

li:hover {
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

/* Goal Information Styles */
strong {
  font-weight: bold;
  color: #333;
}

/* No Goals Message */
div.v-else {
  text-align: center;
  font-size: 16px;
  color: #999;
  margin-top: 20px;
}

/* Responsive Design */
@media (max-width: 600px) {
  #team-select {
    width: 100%;
  }

  .container {
    padding: 15px;
  }
}
</style>