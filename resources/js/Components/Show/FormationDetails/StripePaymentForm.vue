<template>
    <div class="space-y-4">
      <!-- Adresse de facturation -->
      <div class="space-y-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
            <input 
              id="address" 
              v-model="paymentInfo.address" 
              type="text" 
              class="w-full p-2 border border-gray-300 rounded" 
              placeholder="Adresse"
            />
          </div>
          
          <div>
            <label for="postal_code" class="block text-sm font-medium text-gray-700">Code postal</label>
            <input 
              id="postal_code" 
              v-model="paymentInfo.postal_code" 
              type="text" 
              class="w-full p-2 border border-gray-300 rounded" 
              placeholder="Code postal"
            />
          </div>
          <!-- Pays -->
          <div>
            <label for="country" class="block text-sm font-medium text-gray-700">Pays</label>
            <select
              id="country"
              v-model="paymentInfo.country"
              class="w-full p-2 border border-gray-300 rounded"
              :disabled="loadingCountries"
            >
              <option value="" disabled>Sélectionnez un pays</option>
              <option v-for="country in countries" :key="country.code" :value="country.code">
                {{ country.name }}
              </option>
            </select>
            <p v-if="loadingCountries" class="text-sm text-gray-500">Chargement des pays...</p>
          </div>
  
          <!-- Ville -->
          <div>
            <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
            <select
              id="city"
              v-model="paymentInfo.city"
              class="w-full p-2 border border-gray-300 rounded"
              :disabled="loadingCities || !paymentInfo.country"
            >
              <option value="" disabled>Sélectionnez une ville</option>
              <option v-for="city in cities" :key="city.id" :value="city.name">
                {{ city.name }}
              </option>
            </select>
            <p v-if="loadingCities" class="text-sm text-gray-500">Chargement des villes...</p>
            <p v-else-if="!paymentInfo.country" class="text-sm text-gray-500">Veuillez d'abord sélectionner un pays</p>
          </div>
        </div>
      </div>
      <!-- Stripe Card Element -->
          
      <div class="space-y-2">
        <label for="card-element" class="block text-sm font-medium text-gray-700">Informations de carte</label>
        <div id="card-element" class="p-2 border border-gray-300 rounded"></div>
      </div>
      
      <button 
        @click="$emit('process-payment')" 
        :disabled="paymentProcessing || !cardElementComplete" 
        class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded"
        :class="{ 'opacity-50 cursor-not-allowed': paymentProcessing || !cardElementComplete }"
      >
        <span v-if="paymentProcessing">Traitement en cours...</span>
        <span v-else>Payer</span>
      </button>
    </div>
  </template>
  
  <script setup lang="ts">
  defineProps<{
    paymentInfo: {
      name: string;
      address: string;
      postal_code: string;
      city: string;
      country: string;
    };
    paymentProcessing: boolean;
    cardElementComplete: boolean;
    countries: Array<{ code: string; name: string }>;
    cities: Array<{ id: number; name: string }>;
    loadingCountries: boolean;
    loadingCities: boolean;
  }>();
  
  defineEmits<{
    (e: 'process-payment'): void;
  }>();
  </script>