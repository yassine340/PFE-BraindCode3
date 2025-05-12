<template>
  <div class="flex flex-col md:flex-row md:space-x-6">
    <!-- Left side - Formation details -->
    <div class="flex-1">
      <h1 class="text-2xl font-bold mb-4">{{ formation?.titre || 'Stratégie de levée de fonds' }}</h1>
      
      <p class="text-gray-700 mb-6">
        {{ formation?.description || 'Cette formation vous guide à travers les étapes essentielles pour préparer et réussir votre levée de fonds.' }}
      </p>
      
      <div class="mb-4">
        <span class="text-blue-500 font-bold">Durée :</span>
        <span class="ml-1">{{ formation?.duree || '3 heures' }}</span>
      </div>
      
      <div class="mb-4">
        <span class="text-blue-500 font-bold">Coach :</span>
        <span class="ml-1">{{ formation.user?.first_name }} {{ formation.user?.last_name }}</span>
      </div>
      
      <div class="mb-6">
        <span class="text-blue-500 font-bold">Participants :</span>
        <span class="ml-1">{{ formation?.participants || '25 inscrits' }}</span>
      </div>
      
      <button 
        v-if="!hasUserPaid"
        @click="$emit('show-payment')" 
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-10 rounded-lg transition-colors duration-200 mb-4"
      >
        S'inscrire - {{ formation.prix }}€
      </button>
      
      <button 
        v-else
        @click="$emit('start-formation')" 
        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-10 rounded-lg transition-colors duration-200 mb-4"
      >
        Commencer la formation
      </button>
    </div>
    
    <!-- Right side - Formation image -->
    <div class="mt-6 md:mt-0 md:w-1/2 relative">
      <!-- Loader pendant le chargement -->
      <div v-if="imageLoading" class="w-1/2 h-48 bg-gray-200 rounded-xl animate-pulse mx-auto"></div>
      
      <!-- Image avec gestion optimisée pour S3 -->
      <img 
        v-show="!imageLoading"
        :src="formation.image_url || '/images/placeholder-formation.jpg'"
        @error="handleImageError"
        @load="handleImageLoad"
        alt="Image de la formation" 
        class="w-1/2 h-auto rounded-xl object-cover shadow-md mx-auto"
      >
      
      <!-- Badge S3 - Visible uniquement si l'image vient de S3 -->
      <div 
        v-if="isS3Image" 
        class="absolute top-2 right-1/4 bg-blue-500 text-white text-xs font-bold py-1 px-2 rounded-full"
      >
        S3
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  role: string;
}

interface Formation {
  id: number;
  user?: User;
  titre: string;
  description: string;
  duree: string;
  participants: string;
  image_formation: string;
  image_url?: string; // Propriété pour l'URL présignée S3
  prix: number;
  modules?: any[];
}

defineProps<{
  formation: Formation;
  hasUserPaid: boolean;
}>();

defineEmits<{
  (e: 'show-payment'): void;
  (e: 'start-formation'): void;
}>();

// État pour le chargement de l'image
const imageLoading = ref(true);

// État pour suivre si l'image vient de S3
const isS3Image = ref(false);

// Fonction pour gérer le chargement de l'image
function handleImageLoad(event: Event) {
  // Masquer le loader
  imageLoading.value = false;
  
  // Récupérer l'URL de l'image chargée
  const imageUrl = (event.target as HTMLImageElement).src;
  
  // Vérifier si l'image provient de S3 (URL contient amazonaws.com)
  isS3Image.value = imageUrl.includes('amazonaws.com');
  
  if (isS3Image.value) {
    console.log('✅ Image récupérée depuis AWS S3:', imageUrl);
  } else {
    console.log('Image chargée depuis le stockage local:', imageUrl);
  }
}

// Fonction pour gérer l'erreur de chargement d'image
function handleImageError(event: Event) {
  // Masquer le loader
  imageLoading.value = false;
  isS3Image.value = false;
  
  // Remplacer par une image par défaut en cas d'erreur
  (event.target as HTMLImageElement).src = '/images/placeholder-formation.jpg';
  console.error('❌ Erreur de chargement de l\'image, utilisation de l\'image par défaut');
}
</script>