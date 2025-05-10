<template>
  <div class="relative w-full h-full">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script>
import { ref, onMounted, watch, onBeforeUnmount } from 'vue';
import Chart from 'chart.js/auto';

export default {
  props: {
    activityData: {
      type: Array,
      required: true
    }
  },
  setup(props) {
    const chartCanvas = ref(null);
    let chart = null;

    const processData = (data) => {
      const last7Days = Array.from({ length: 7 }, (_, i) => {
        const date = new Date();
        date.setDate(date.getDate() - i);
        return date.toISOString().split('T')[0];
      }).reverse();

      const activityCounts = last7Days.map(date => {
        return data.filter(activity => 
          activity.timestamp.split('T')[0] === date
        ).length;
      });

      return {
        labels: last7Days.map(date => new Date(date).toLocaleDateString('en-US', { weekday: 'short' })),
        data: activityCounts
      };
    };

    const createChart = (data) => {
      if (chart) {
        chart.destroy();
      }

      const processedData = processData(data);
      
      const ctx = chartCanvas.value.getContext('2d');
      chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: processedData.labels,
          datasets: [{
            label: 'Activities',
            data: processedData.data,
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });
    };

    onMounted(() => {
      if (chartCanvas.value) {
        createChart(props.activityData);
      }
    });

    watch(() => props.activityData, (newData) => {
      if (chartCanvas.value) {
        createChart(newData);
      }
    });

    onBeforeUnmount(() => {
      if (chart) {
        chart.destroy();
      }
    });

    return {
      chartCanvas
    };
  }
};
</script>

<style scoped>
.chart-container {
  height: 100%;
  width: 100%;
}
</style> 