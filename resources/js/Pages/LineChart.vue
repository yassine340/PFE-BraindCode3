<script setup>
import { defineProps } from 'vue';
import { Line } from 'vue-chartjs';

// Define props for the chart data and options
const props = defineProps({
  data: Object,
  options: Object,
});
</script>

<template>
  <!-- Use the Line component from vue-chartjs and bind the data and options -->
  <Line :data="props.data" :options="props.options" />
</template>
