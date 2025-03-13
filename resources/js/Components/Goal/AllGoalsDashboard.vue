<template>
  <div>
    <!-- Loading Indicator -->
    <div v-if="loading">
      <p>Loading...</p>
    </div>

    <!-- Team Selection -->
    <div v-else>
      <label for="team-select">Select Team:</label>
      <select v-model="selectedTeam" @change="fetchGoals" id="team-select">
        <option :value="null">ALL</option> <!-- Option for All Teams -->
        <option v-for="team in teams" :key="team.id" :value="team.id">
          {{ team.name }}
        </option>
      </select>
    </div>

    <!-- Display Goals -->
    <div v-if="goals.length > 0">
      <h3>Goals for Team: {{ selectedTeamName || 'All Teams' }}</h3>
      <ul>
        <li v-for="goal in goals" :key="goal.id">
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      teams: [],            // List of teams
      selectedTeam: null,   // Team ID or null for "ALL"
      goals: [],            // Goals for the selected team
      selectedTeamName: '', // Selected team's name
      loading: true         // Loading state
    };
  },
  created() {
    // Load the selected team from localStorage if it exists
    const savedTeam = localStorage.getItem('selectedTeam');
    if (savedTeam) {
      this.selectedTeam = JSON.parse(savedTeam);
    } else {
      // If no saved team, default to ALL (null)
      this.selectedTeam = null;
    }

    // Fetch teams when the component is created
    this.fetchTeams().then(() => {
      // Fetch goals after teams have been loaded
      this.fetchGoals();
    });
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
        this.loading = false; // Stop loading once teams are fetched
      }
    },
    async fetchGoals() {
      this.loading = true; // Set loading state to true while fetching goals
      try {
        if (this.selectedTeam === null) {
          // If "ALL" is selected, fetch goals for all teams
          const response = await axios.get('/api/goals'); // Endpoint for all goals
          console.log('Fetched All Goals:', response.data);
          this.goals = response.data;
          this.selectedTeamName = ''; // Show 'ALL' if no team is selected
        } else if (this.selectedTeam) {
          // Fetch goals for the selected team
          const response = await axios.get(`/api/goals/${this.selectedTeam}`);
          console.log('Fetched Goals for Team:', response.data);
          this.goals = response.data;
          const team = this.teams.find((team) => team.id === this.selectedTeam);
          this.selectedTeamName = team ? team.name : '';
        }
      } catch (error) {
        console.error('Error fetching goals:', error);
      } finally {
        this.loading = false; // Stop loading once goals are fetched
      }
    }
  },
  watch: {
    selectedTeam(newValue) {
      // Save the selected team to localStorage whenever it changes
      localStorage.setItem('selectedTeam', JSON.stringify(newValue));
      this.fetchGoals(); // Fetch goals whenever the selected team changes
    }
  }
};
</script>


<style scoped>
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
