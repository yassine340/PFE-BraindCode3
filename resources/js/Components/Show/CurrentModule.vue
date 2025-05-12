<template>
    <div v-if="currentModule" class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 transform transition-all duration-200 hover:shadow-2xl">
      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 py-4 px-6">
        <h2 class="text-2xl font-bold text-white">
          Module {{ currentModuleIndex + 1 }} : {{ currentModule.titre }}
        </h2>
        <div class="flex items-center mt-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="ml-2 text-indigo-100">{{ currentModule.duree_estimee || 0 }} minutes</span>
        </div>
      </div>
      
      <div class="p-6">
        <p class="text-gray-700 mb-4">{{ currentModule.description }}</p>
        
        <!-- Current Lesson -->
        <LeconContent 
          v-if="currentLecon" 
          :currentLecon="currentLecon" 
          :currentLeconIndex="currentLeconIndex"
          :quizValidationStatus="quizValidationStatus"
          :userAnswers="userAnswers"
          @submit-answers="$emit('submit-answers')"
        />
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps, defineEmits } from 'vue';
  import LeconContent from './LeconContent.vue';
  
  defineProps({
    currentModule: {
      type: Object,
      required: true
    },
    currentModuleIndex: {
      type: Number,
      required: true
    },
    currentLecon: {
      type: Object,
      required: false,
      default: null
    },
    currentLeconIndex: {
      type: Number,
      required: true
    },
    quizValidationStatus: {
      type: Object,
      required: true
    },
    userAnswers: {
      type: Object,
      required: true
    }
  });
  
  defineEmits(['submit-answers']);
  </script>