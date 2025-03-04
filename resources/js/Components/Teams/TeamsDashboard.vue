<template>
  <div>
    <h1>Your Teams</h1>

    <div v-if="loading" class="loader">Loading...</div>

 
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="teams.length === 0 && !loading">No teams found.</div>


    <div v-for="team in teams" :key="team.id" class="team">
      <h2>{{ team.name }}</h2>
      <h3>Members:</h3>
      <ul>
        <li v-for="user in team.users" :key="user.id">
        {{ user.name }}  
        </li>
       
      </ul>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      teams: [], 
      loading: true,
      error: null 
    };
  },
  mounted() {
    this.fetchTeams();
  },
  methods: {
    async fetchTeams() {
      try {
        // Make an API call to get the teams
        const response = await axios.get('/api/teams');
        
        // Assign the fetched teams data to the teams variable
        this.teams = response.data;
      } catch (err) {
        // Handle errors
        this.error = "Failed to load teams. Please try again.";
      } finally {
        // Set loading to false once data is fetched
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
div {
  font-family: 'Arial', sans-serif;
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

div h1 {
  font-weight: 700;
  font-size: 2rem;
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.loader {
  text-align: center;
  font-size: 20px;
  font-weight: bold;
  color: #555;
  margin-top: 20px;
}

.error {
  color: #d9534f;
  background-color: #f8d7da;
  padding: 10px;
  text-align: center;
  border-radius: 5px;
  margin-top: 10px;
  font-weight: bold;
}

.team {
  margin-bottom: 20px;
  padding: 1.5rem;
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
 
}

 
h2 {
  font-size: 1.5rem;
 
  margin-bottom: 10px;
}

h3 {
  font-size: 1.2rem;
  color: #555;
  margin-bottom: 5px;
}

ul {
  padding-left: 20px;
}

ul li {
  list-style-type: none;
  background: #f1f1f1;
  margin: 5px 0;
  padding: 8px;
  border-radius: 5px;
 
}

ul li:hover {
  background: #ddd;
}

</style>
