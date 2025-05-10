<template>
  <div class="relative w-full h-full">
    <canvas ref="chartCanvas"></canvas>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
      <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ completionRate }}%</div>
      <div class="text-sm text-gray-500 dark:text-gray-400">Completion Rate</div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch, onBeforeUnmount, computed } from 'vue';
import Chart from 'chart.js/auto';

export default {
  props: {
    totalGoals: {
      type: Number,
      required: true
    },
    completedGoals: {
      type: Number,
      required: true
    }
  },
  setup(props) {
    const chartCanvas = ref(null);
    let chart = null;

    const completionRate = computed(() => {
      return props.totalGoals > 0 
        ? Math.round((props.completedGoals / props.totalGoals) * 100) 
        : 0;
    });

    const createChart = () => {
      if (chart) {
        chart.destroy();
      }

      const remainingGoals = props.totalGoals - props.completedGoals;
      
      const ctx = chartCanvas.value.getContext('2d');
      chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Completed', 'Remaining'],
          datasets: [{
            data: [props.completedGoals, remainingGoals],
            backgroundColor: ['#10B981', '#E5E7EB'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 20,
                usePointStyle: true
              }
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || '';
                  const value = context.raw || 0;
                  return `${label}: ${value} goals`;
                }
              }
            }
          },
          cutout: '70%'
        }
      });
    };

    onMounted(() => {
      if (chartCanvas.value) {
        createChart();
      }
    });

    watch([() => props.totalGoals, () => props.completedGoals], () => {
      if (chartCanvas.value) {
        createChart();
      }
    });

    onBeforeUnmount(() => {
      if (chart) {
        chart.destroy();
      }
    });

    return {
      chartCanvas,
      completionRate
    };
  }
};
</script>

<style scoped>
.chart-container {
  position: relative;
}
</style> 