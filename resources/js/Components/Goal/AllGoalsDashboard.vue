<template>
  <div>
    <div v-if="loading">
      <p>Loading...</p>
    </div>

    <div v-else>
      <label for="team-select">Select Team:</label>
      <select v-model="selectedTeam" @change="fetchGoals" id="team-select">
        <option v-for="team in teams" :key="team.id" :value="team.id">
          {{ team.name }}
        </option>
      </select>
    </div>
    <input
      v-model="searchQuery"
      type="text"
      placeholder="Search goals..."
      class="border p-2 mb-2 w-full"
    />
    <div v-if="filteredGoals.length > 0">
      <h3>Goals for Team: {{ selectedTeamName || 'All Teams' }}</h3>
      <ul>
        <li v-for="goal in filteredGoals" :key="goal.id" @click="openGoalModal(goal)">
          <h4>{{ goal.title }}</h4>
          <p>{{ goal.description }}</p>
          <p><strong>Start Time:</strong> {{ goal.start_time }}</p>
          <p><strong>End Time:</strong> {{ goal.end_time }}</p>
        </li>
      </ul>
    </div>
    <div v-else>
      <p>No goals available for the selected team.</p>
    </div>


    <div v-if="isModalVisible" class="fullscreen-modal-overlay" @click.self="closeModal">
      <div class="fullscreen-modal">
        <button @click="closeModal" class="close-button">&times;</button>
        
        <h2 class="text-xl font-semibold mb-4">{{ selectedGoal.title }}</h2>
        
        <div v-if="isEditing">
          <input v-model="selectedGoal.title" class="border p-2 mb-2 w-full" placeholder="Goal Title" />
          <textarea v-model="selectedGoal.description" class="border p-2 mb-2 w-full" placeholder="Goal Description"></textarea>
          <input v-model="selectedGoal.start_time" type="datetime-local" class="border p-2 mb-2 w-full" />
          <input v-model="selectedGoal.end_time" type="datetime-local" class="border p-2 mb-2 w-full" />
          
          <div class="mt-4 flex justify-end space-x-2">
            <button @click="saveGoal" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
            <button @click="cancelEdit" class="px-4 py-2 bg-gray-600 text-white rounded">Cancel</button>
          </div>
        </div>
        
        <div v-else>
          <p><strong>Description:</strong> {{ selectedGoal.description }}</p>
          <p><strong>Start Time:</strong> {{ formatDate(selectedGoal.start_time) }}</p>
          <p><strong>End Time:</strong> {{ formatDate(selectedGoal.end_time) }}</p>
          
          <div class="mt-4 flex justify-end space-x-2">
            <button @click="startEdit" class="px-4 py-2 bg-blue-600 text-white rounded">Edit</button>
            <button @click="deleteGoal" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
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
      selectedTeam: null,   
      goals: [],            
      selectedTeamName: '', 
      loading: true,       
      isModalVisible: false, 
      selectedGoal: null,   
      isEditing: false,     
      searchQuery: '', 
    };
  },
  created() {
    const savedTeam = localStorage.getItem('selectedTeam');
    if (savedTeam) {
      this.selectedTeam = JSON.parse(savedTeam);
    } else {
      this.selectedTeam = null;
    }

   
    this.fetchTeams().then(() => {
      this.fetchGoals();
    });
  },
  computed: {
    filteredGoals() {
      if (!this.searchQuery.trim()) {
        return this.goals; 
      }
      const lowerCaseQuery = this.searchQuery.toLowerCase();
      return this.goals.filter(goal => 
        goal.title.toLowerCase().includes(lowerCaseQuery) || 
        goal.description.toLowerCase().includes(lowerCaseQuery)
      );
    }
  },
  methods: {
    async fetchTeams() {
      try {
        const response = await axios.get('/api/teams');
        console.log('Fetched Teams:', response.data);
        this.teams = response.data;
      } catch (error) {
        console.error('Error fetching teams:', error);
      } finally {
        this.loading = false;
      }
    },
    async fetchGoals() {
      this.loading = true; 
      try {
        if (this.selectedTeam === null) {
          const response = await axios.get('/api/goals'); // Fetch all goals
          console.log('Fetched All Goals:', response.data);
          this.goals = response.data;
          this.selectedTeamName = ''; 
        } else if (this.selectedTeam) {
          const response = await axios.get(`/api/goals/${this.selectedTeam}`);
          console.log('Fetched Goals for Team:', response.data);
          this.goals = response.data;
          const team = this.teams.find((team) => team.id === this.selectedTeam);
          this.selectedTeamName = team ? team.name : '';
        }
      } catch (error) {
        console.error('Error fetching goals:', error);
      } finally {
        this.loading = false;
      }
    },
    openGoalModal(goal) {
      this.selectedGoal = goal;  
      this.isModalVisible = true; 
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
      this.selectedGoal = { ...this.selectedGoal };
    },
    async saveGoal() {
      try {
        await axios.patch(`/api/goals/${this.selectedGoal.id}`, this.selectedGoal);
        this.isEditing = false;
        alert('Goal updated successfully!');
      } catch (error) {
        console.error('Error updating goal:', error);
      }
    },
    async deleteGoal() {
      if (confirm('Are you sure you want to delete this goal?')) {
        try {
          await axios.delete(`/api/goals/${this.selectedGoal.id}`);
          alert('Goal deleted successfully!');
          this.fetchGoals();  
          this.closeModal(); 
        } catch (error) {
          console.error('Error deleting goal:', error);
        }
      }
    },
    formatDate(date) {
      const d = new Date(date);
      return `${d.getMonth() + 1}/${d.getDate()}/${d.getFullYear()} ${d.getHours()}:${d.getMinutes()}`;
    }
  },
  watch: {
    selectedTeam(newValue) {
      localStorage.setItem('selectedTeam', JSON.stringify(newValue));
      this.fetchGoals();
    }
  }
};
</script>



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
