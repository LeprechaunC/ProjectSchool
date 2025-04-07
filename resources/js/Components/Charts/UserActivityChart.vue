<template>
  <div class="chart-container">
    <Line v-if="chartData" :data="chartData" :options="chartOptions" />
    <div v-else class="flex items-center justify-center h-full">
      <p class="text-gray-500 dark:text-gray-400">Loading chart data...</p>
    </div>
  </div>
</template>

<script>
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

export default {
  name: 'UserActivityChart',
  components: { Line },
  props: {
    activityData: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    chartData() {
      if (!this.activityData || this.activityData.length === 0) {
        return null;
      }

      // Process the activity data to get daily counts
      const dailyCounts = {};
      
      this.activityData.forEach(activity => {
        const date = new Date(activity.timestamp).toLocaleDateString();
        if (!dailyCounts[date]) {
          dailyCounts[date] = 0;
        }
        dailyCounts[date]++;
      });

      // Sort dates chronologically
      const sortedDates = Object.keys(dailyCounts).sort((a, b) => {
        return new Date(a) - new Date(b);
      });

      // Get the last 7 days of data
      const last7Days = sortedDates.slice(-7);

      return {
        labels: last7Days,
        datasets: [
          {
            label: 'User Activity',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 2,
            data: last7Days.map(date => dailyCounts[date]),
            tension: 0.3,
            fill: true
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
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        }
      };
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