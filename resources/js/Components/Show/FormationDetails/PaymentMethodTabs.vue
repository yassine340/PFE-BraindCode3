<template>
    <div class="border-b border-gray-200 mb-4">
      <div class="flex">
        <button 
          @click="$emit('change-method', 'stripe')" 
          :class="[
            'py-2 px-4 border-b-2 font-medium text-sm',
            paymentMethod === 'stripe' 
              ? 'border-indigo-500 text-indigo-600' 
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 3H6C4.346 3 3 4.346 3 6V18C3 19.654 4.346 21 6 21H18C19.654 21 21 19.654 21 18V6C21 4.346 19.654 3 18 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M3 10H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Carte de crédit
          </div>
        </button>
        <button 
          @click="$emit('change-method', 'paypal')" 
          :class="[
            'py-2 px-4 border-b-2 font-medium text-sm',
            paymentMethod === 'paypal' 
              ? 'border-indigo-500 text-indigo-600' 
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 7C19 10.866 15.866 14 12 14C8.134 14 5 10.866 5 7C5 3.134 8.134 0 12 0C15.866 0 19 3.134 19 7Z" fill="#0070E0"/>
              <path d="M7 14H17C18.657 14 20 15.343 20 17V21C20 22.657 18.657 24 17 24H7C5.343 24 4 22.657 4 21V17C4 15.343 5.343 14 7 14Z" fill="#003087"/>
            </svg>
            PayPal
          </div>
        </button>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  defineProps<{
    paymentMethod: string;
  }>();
  
  defineEmits<{
    (e: 'change-method', method: string): void;
  }>();
  </script>