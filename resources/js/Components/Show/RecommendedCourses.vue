<template>
    <div class="mt-8">
      <h3 class="text-xl font-bold mb-6">Tu pourrais aussi aimer</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="formation in formations" :key="formation.id" class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="relative overflow-hidden h-36">
            <img 
              v-if="formation.image_formation" 
              :src="`/storage/${formation.image_formation}`" 
              alt="Image formation" 
              class="w-full h-full object-cover"
            >
            <div v-else class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
          </div>
          <div class="p-4">
            <h4 class="font-bold text-gray-800 line-clamp-2">{{ formation.titre }}</h4>
            <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ formation.description }}</p>
            <Link 
              :href="`/formations/${formation.id}`" 
              class="mt-3 inline-block px-4 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition-colors"
            >
              Voir plus
            </Link>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { Link } from '@inertiajs/vue3';
  
  interface Formation {
    id: number;
    titre: string;
    description: string;
    duree: string;
    participants: string;
    image_formation: string;
    prix: number;
    modules?: any[];
  }
  
  defineProps<{
    formations: Formation[];
  }>();
  </script>
  
  <style scoped>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  </style>