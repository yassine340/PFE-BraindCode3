<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-8 max-w-md w-full text-center transform transition-all animate-fadeIn">
        <h2 class="text-2xl font-bold text-purple-800 mb-4">🎉 Félicitations ! 🎉</h2>
        <p class="text-lg mb-6">Vous avez obtenu un nouveau badge !</p>
        
        <div class="flex justify-center mb-6">
          <img :src="badgeImage" alt="Badge obtenu" class="h-40 w-40 object-contain animate-bounce">
        </div>
        
        <p class="text-gray-700 mb-6">Continuez à apprendre pour débloquer plus de badges !</p>
        
        <button @click="closeModal" 
                class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-lg">
          Super !
        </button>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps, defineEmits } from 'vue';
  
  const props = defineProps<{
    show: boolean;
    badgeImage: string;
  }>();
  
  const emit = defineEmits(['close']);
  
  const closeModal = () => {
    emit('close');
  };
  </script>
  
  <style scoped>
  .animate-fadeIn {
    animation: fadeIn 0.5s ease-out;
  }
  
  .animate-bounce {
    animation: bounce 1s infinite;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
  }
  
  @keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
  }
  </style>