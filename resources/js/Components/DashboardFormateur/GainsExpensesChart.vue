<template>
    <div class="p-6 bg-white rounded-lg shadow ">
      <div class="mb-6">
        <h4 class="text-lg font-medium text-blue-500">Évolution des Gains et Dépenses</h4>
      </div>
      <div class="flex items-center mb-4 space-x-4">
        <div class="flex items-center">
          <div class="w-3 h-3 mr-2 bg-orange-500 rounded-full"></div>
          <span class="text-sm text-gray-500">Dépenses (k€)</span>
        </div>
        <div class="flex items-center">
          <div class="w-3 h-3 mr-2 bg-blue-500 rounded-full"></div>
          <span class="text-sm text-gray-500">Gains (k€)</span>
        </div>
      </div>
      <div class="h-64">
        <canvas ref="chartCanvas"></canvas>
      </div>
    </div>
  </template>
  
  <script>
  import { Chart, LineController, LineElement, PointElement, CategoryScale, LinearScale, Title, Tooltip, Legend } from 'chart.js';
  
  Chart.register(LineController, LineElement, PointElement, CategoryScale, LinearScale, Title, Tooltip, Legend);
  
  export default {
    data() {
      return {
        chart: null
      };
    },
    mounted() {
      this.createChart();
    },
    methods: {
      createChart() {
        const ctx = this.$refs.chartCanvas.getContext('2d');
        this.chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [
              {
                label: 'Dépenses',
                data: [1000, 800, 1200, 1000, 700, 500],
                fill: false,
                borderColor: '#f97316',
                tension: 0.4
              },
              {
                label: 'Gains',
                data: [500, 800, 600, 1000, 1200, 1500],
                fill: false,
                borderColor: '#0ea5e9',
                tension: 0.4
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false
          }
        });
      }
    },
    beforeUnmount() {
      if (this.chart) {
        this.chart.destroy();
      }
    }
  };
  </script>