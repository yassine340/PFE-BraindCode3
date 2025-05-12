<template>
    <div class="mt-8 space-y-4">
      <!-- Message d'avertissement si quiz non validé -->
      <div v-if="showQuizWarning" 
          class="p-3 text-sm text-center text-amber-700 bg-amber-100 rounded-lg border border-amber-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        Vous devez valider ce quiz pour continuer à la leçon suivante
      </div>
      
      <!-- Navigation des leçons -->
      <div class="flex justify-between">
        <button
          @click="prevLecon"
          :disabled="currentLeconIndex === 0"
          class="flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Leçon précédente
        </button>
        <button
          @click="nextLecon"
          :disabled="!canNavigateToNextLecon || currentLeconIndex >= totalLecons - 1"
          class="flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-all"
        >
          Leçon suivante
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>
      
      <!-- Navigation des modules -->
      <div class="flex justify-between">
        <button
          @click="prevModule"
          :disabled="currentModuleIndex === 0"
          class="flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Module précédent
        </button>
        <button
          @click="nextModule"
          :disabled="currentModuleIndex >= totalModules - 1"
          class="flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-all"
        >
          Module suivant
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps, defineEmits, computed } from 'vue';
  
  const props = defineProps<{
    currentLeconIndex: number;
    currentModuleIndex: number;
    totalLecons: number;
    totalModules: number;
    canNavigateToNextLecon: boolean;
    currentLeconHasQuiz: boolean;
  }>();
  
  const emit = defineEmits(['prev-lecon', 'next-lecon', 'prev-module', 'next-module']);
  
  // Computed property for warning message
  const showQuizWarning = computed(() => {
    return props.currentLeconHasQuiz && 
           !props.canNavigateToNextLecon && 
           props.currentLeconIndex < props.totalLecons - 1;
  });
  
  // Navigation handlers
  const prevLecon = () => {
    emit('prev-lecon');
  };
  
  const nextLecon = () => {
    emit('next-lecon');
  };
  
  const prevModule = () => {
    emit('prev-module');
  };
  
  const nextModule = () => {
    emit('next-module');
  };
  </script>