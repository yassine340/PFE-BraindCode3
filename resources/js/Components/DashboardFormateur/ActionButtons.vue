<template>
    <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-3">
      <ActionButton 
        :href="route('formations.create')" 
        icon="plus" 
        text="Créer une nouvelle formation" 
      />
      
      <ActionButton 
        :href="route('document-upload')" 
        icon="upload" 
        text="Télécharger des documents" 
      />
      
      <ActionButton 
        :href="route('document-list')" 
        icon="document" 
        text="Liste des documents" 
      />
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  import ActionButton from './ActionButton.vue';
  </script>