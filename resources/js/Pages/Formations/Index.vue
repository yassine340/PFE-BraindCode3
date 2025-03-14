<template>
    <Head title="Afficher toutes les formations" />
    <AuthenticatedLayout>
      <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold text-gray-800 mb-12 text-center">🌟 Liste des Formations</h1>
  
        <!-- Button to add a new formation -->
        <div class="text-center mb-6">
          <Link href="/formations/create" 
                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
            Ajouter une formation
          </Link>
        </div>
  
        <!-- Formations Grid -->
        <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">
          <div v-for="formation in formations" :key="formation.id" 
              class="bg-white shadow-lg rounded-xl overflow-hidden transition-all transform hover:scale-105 hover:shadow-xl hover:transition-all p-6">
            
            <Link :href="`/formations/${formation.id}`" class="block text-center">
              <!-- Image de la formation -->
              <img v-if="formation.image_formation" 
                  :src="`/storage/${formation.image_formation}`" 
                  alt="Image formation" 
                  class="w-full h-56 object-cover rounded-lg transition-transform duration-300 hover:scale-110">
              
              <!-- Titre de la formation -->
              <h2 class="mt-4 text-xl font-semibold text-gray-900 hover:text-blue-600 transition-all">{{ formation.titre }}</h2>
            </Link>
  
            <!-- Description ou bouton 'Voir plus' -->
            <div class="mt-6 text-center">
              <Link :href="`/formations/${formation.id}`"
                  class="inline-block text-blue-600 hover:text-blue-800 font-medium text-sm transition duration-300 border-b-2 border-transparent hover:border-blue-600">
                Voir plus
              </Link>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup lang="ts">
  import { Link, usePage } from '@inertiajs/vue3';
  import { Head } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  // Define the expected props, specifying the correct types
  interface Formation {
    id: number;
    titre: string;
    image_formation: string | null;
  }
  
  defineProps<{
    formations: Formation[];
  }>();
  
  // Accéder à l'objet route() d'Inertia
  const page = usePage();
  </script>
  