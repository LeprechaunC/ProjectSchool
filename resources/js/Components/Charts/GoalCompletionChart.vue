<template>
  <div class="chart-container">
    <Doughnut v-if="chartData" :data="chartData" :options="chartOptions" />
    <div v-else class="flex items-center justify-center h-full">
      <p class="text-gray-500 dark:text-gray-400">Loading chart data...</p>
    </div>
  </div>
</template>

<script>
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend);

export default {
  name: 'GoalCompletionChart',
  components: { Doughnut },
  props: {
    totalGoals: {
      type: Number,
      default: 0
    },
    completedGoals: {
      type: Number,
      default: 0
    }
  },
  computed: {
    chartData() {
      if (this.totalGoals === 0) {
        return null;
      }

      const remainingGoals = this.totalGoals - this.completedGoals;
      const completionPercentage = (this.completedGoals / this.totalGoals) * 100;

      return {
        labels: ['Completed', 'In Progress'],
        datasets: [
          {
            data: [this.completedGoals, remainingGoals],
            backgroundColor: [
              'rgba(34, 197, 94, 0.8)', // Green for completed
              'rgba(209, 213, 219, 0.8)' // Gray for in progress
            ],
            borderColor: [
              'rgba(34, 197, 94, 1)',
              'rgba(209, 213, 219, 1)'
            ],
            borderWidth: 1
          }
        ]
      };
    },
    chartOptions() {
      return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: this.isDarkMode ? '#e5e7eb' : '#374151',
              font: {
                size: 12
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = Math.round((value / total) * 100);
                return `${label}: ${value} (${percentage}%)`;
              }
            }
          }
        },
        cutout: '70%'
      };
    },
    isDarkMode() {
      return document.documentElement.classList.contains('dark');
    }
  }
};
</script>

<style scoped>
.chart-container {
  height: 100%;
  width: 100%;
}
</style> 