<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-10 flex items-center justify-center">
      <div class="container mx-auto p-6 max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
          <div class="bg-gradient-to-r from-red-500 to-pink-600 py-8 px-6">
            <div class="flex justify-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0h-2m-1-12.5v11.5a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-11.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5Z" />
              </svg>
            </div>
            <h1 class="text-3xl font-bold text-white text-center">Accès Restreint</h1>
          </div>
          
          <div class="p-8">
            <p class="text-gray-700 text-center mb-6">
              {{ message || "Vous n'avez pas accès à ce contenu. Veuillez vous inscrire ou acheter cette formation pour y accéder." }}
            </p>
            
            <div class="space-y-4">
              <button v-if="showPurchaseButton"
                     @click="purchaseFormation"
                     class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Acheter cette formation
              </button>
              
              <button @click="goBack"
                     class="w-full py-3 px-4 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Retour
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps, defineEmits } from 'vue';
  
  const props = defineProps<{
    message?: string;
    showPurchaseButton?: boolean;
  }>();
  
  const emit = defineEmits(['purchase', 'go-back']);
  
  const purchaseFormation = () => {
    emit('purchase');
  };
  
  const goBack = () => {
    emit('go-back');
  };
  </script>