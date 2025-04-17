<template>
    <div class="p-6 bg-white rounded-lg shadow ">
      <div class="flex items-center justify-between mb-4">
        <div class="p-3 bg-blue-100 rounded-lg">
          <CalendarIcon v-if="icon === 'calendar'" class="w-6 h-6 text-blue-500" />
          <BookOpenIcon v-else-if="icon === 'book'" class="w-6 h-6 text-blue-500" />
          <TrophyIcon v-else-if="icon === 'award'" class="w-6 h-6 text-blue-500" />
        </div>
      </div>
      <p class="text-sm font-medium text-blue-500">{{ title }}</p>
      <h3 class="mt-2 text-2xl font-bold text-[#005183]">{{ value }}</h3>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: {
      type: String,
      required: true
    },
    value: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      default: 'calendar'
    }
  });
  
  // Updated Heroicons v2 imports
  import { CalendarIcon, BookOpenIcon, TrophyIcon } from '@heroicons/vue/24/outline';
  </script>