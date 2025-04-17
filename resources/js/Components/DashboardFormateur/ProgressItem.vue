<template>
    <div class="flex items-center justify-between">
      <div class="flex items-center">
        <div v-if="avatar !== 'person'" class="flex items-center justify-center w-8 h-8 mr-3 text-xs font-bold text-white bg-blue-500 rounded-full">
          {{ avatar }}
        </div>
        <div v-else class="w-8 h-8 mr-3 overflow-hidden bg-gray-200 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
          </svg>
        </div>
        <span class="font-medium text-blue-500">{{ title }}</span>
      </div>
      <div class="flex items-center">
        <div class="flex items-center">
          <span class="mr-2 text-sm text-gray-500">{{ progress }}%</span>
          <div class="w-24 h-2 bg-gray-200 rounded-full">
            <div 
              class="h-2 rounded-full" 
              :class="`bg-${color}-500`"
              :style="`width: ${progress}%`"
            ></div>
          </div>
        </div>
        <span 
          class="px-2 py-1 ml-4 text-xs text-white rounded-full"
          :class="`bg-${color}-500`"
        >
          {{ date }}
        </span>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: {
      type: String,
      required: true
    },
    progress: {
      type: Number,
      required: true
    },
    date: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: 'blue'
    },
    avatar: {
      type: String,
      default: 'T'
    }
  });
  </script>