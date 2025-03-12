<template>
   
       
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: {
      team_id: Number,  // team_id passed via Inertia
    },
    data() {
      return {
        goals: [],
        loading: true,
        error: null,
      };
    },
    created() {
      // Fetch the goals for the authenticated user's team
      this.fetchGoals();
    },
    methods: {
      fetchGoals() {
        if (!this.team_id) {
          this.error = 'No team found for this user.';
          this.loading = false;
          return;
        }
  
        axios
          .get(`/api/goals/${this.team_id}`)
          .then((response) => {
            this.goals = response.data;
            this.loading = false;
          })
          .catch((error) => {
            this.error = 'Error fetching goals.';
            this.loading = false;
          });
      },
      formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', options);
      },
    },
  };
  </script>
  
  <style scoped>
  h1 {
    font-size: 24px;
    font-weight: bold;
  }
  
  ul {
    list-style-type: none;
    padding: 0;
  }
  
  li {
    margin: 20px 0;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 5px;
  }
  
  .error {
    color: red;
  }
  </style>
  