<template>
    <div class="p-6 bg-white rounded-lg shadow ">
      <div class="flex items-center justify-between mb-6">
        <h4 class="text-lg font-medium text-blue-500">Dernier avancement des startup</h4>
        <Link href="#" class="flex items-center text-sm text-blue-500 hover:underline">
          Voir tous
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </Link>
      </div>
      
      <!-- Tabs -->
      <div class="flex mb-4 border-b">
        <button 
          v-for="tab in tabs" 
          :key="tab.id"
          :class="[
            'px-8 py-2 font-medium',
            activeTab === tab.id 
              ? 'text-blue-600 border-b-2 border-blue-600' 
              : 'text-gray-500 hover:text-blue-600'
          ]"
          @click="activeTab = tab.id"
        >
          {{ tab.name }}
        </button>
      </div>
      
      <!-- Progress List -->
      <div class="space-y-4">
        <ProgressItem 
          v-for="item in progressItems" 
          :key="item.id"
          :title="item.title"
          :progress="item.progress"
          :date="item.date"
          :color="item.color"
          :avatar="item.avatar"
        />
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { Link } from '@inertiajs/vue3';
  import ProgressItem from './ProgressItem.vue';
  
  const activeTab = ref('startup');
  
  const tabs = [
    { id: 'startup', name: 'Startup' },
    { id: 'formation', name: 'Formation' },
    { id: 'performance', name: 'Performance' },
    { id: 'notes', name: 'Notes' }
  ];
  
  const progressItems = [
    { 
      id: 1, 
      title: 'Formation 1', 
      progress: 45, 
      date: '14 Déc', 
      color: 'blue', 
      avatar: 'T' 
    },
    { 
      id: 5, 
      title: 'Formation 5', 
      progress: 35, 
      date: '20 Jan', 
      color: 'orange', 
      avatar: 'T' 
    },
    { 
      id: 7, 
      title: 'Formation 7', 
      progress: 95, 
      date: '10 Fév', 
      color: 'yellow', 
      avatar: 'T' 
    },
    { 
      id: 15, 
      title: 'Formation 15', 
      progress: 65,
      date: '14 Déc', 
      color: 'yellow', 
      avatar: 'T' 
    },
    { 
      id: 32, 
      title: 'Formation 32', 
      progress: 50, 
      date: '15 Déc', 
      color: 'gray', 
      avatar: 'person' 
    }
  ];
  </script>