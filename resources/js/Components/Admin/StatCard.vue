<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    required: true
  },
  iconHtml: {
    type: String,
    required: true
  },
  color: {
    type: String,
    default: 'blue'
  },
  trend: {
    type: String,
    default: ''
  }
});

// Map color names to tailwind classes
const colorClasses = {
  blue: {
    bg: 'bg-blue-100',
    text: 'text-blue-600',
    graphBg: 'bg-blue-50',
    barBg: 'bg-blue-400',
    barHighlight: 'bg-blue-500'
  },
  purple: {
    bg: 'bg-purple-100',
    text: 'text-purple-600',
    graphBg: 'bg-purple-50',
    barBg: 'bg-purple-400',
    barHighlight: 'bg-purple-500'
  },
  amber: {
    bg: 'bg-amber-100',
    text: 'text-amber-600',
    graphBg: 'bg-amber-50',
    barBg: 'bg-amber-400',
    barHighlight: 'bg-amber-500'
  },
  green: {
    bg: 'bg-green-100',
    text: 'text-green-600',
    graphBg: 'bg-green-50',
    barBg: 'bg-green-400',
    barHighlight: 'bg-green-500'
  }
};
</script>

<template>
  <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-gray-500 text-sm font-medium">{{ title }}</h3>
      <div :class="[colorClasses[color].bg, 'p-2 rounded-lg']">
        <div v-html="iconHtml"></div>
      </div>
    </div>
    <div class="flex items-end justify-between">
      <div>
        <span class="text-3xl font-bold text-gray-900">{{ value }}</span>
        <p v-if="trend" class="text-green-500 text-sm flex items-center mt-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
          </svg>
          {{ trend }}
        </p>
        <slot name="extra"></slot>
      </div>
      <div :class="[colorClasses[color].graphBg, 'h-12 w-24 rounded-lg overflow-hidden']">
        <!-- Mini chart -->
        <div class="flex items-end justify-between h-full px-1">
          <div :class="[colorClasses[color].barBg, 'w-1 rounded-t h-6']"></div>
          <div :class="[colorClasses[color].barBg, 'w-1 rounded-t h-8']"></div>
          <div :class="[colorClasses[color].barBg, 'w-1 rounded-t h-5']"></div>
          <div :class="[colorClasses[color].barBg, 'w-1 rounded-t h-9']"></div>
          <div :class="[colorClasses[color].barBg, 'w-1 rounded-t h-7']"></div>
          <div :class="[colorClasses[color].barBg, 'w-1 rounded-t h-8']"></div>
          <div :class="[colorClasses[color].barHighlight, 'w-1 rounded-t h-10']"></div>
        </div>
      </div>
    </div>
  </div>
</template>