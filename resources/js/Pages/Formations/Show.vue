<template>
    <Head title="Afficher toutes les formations" />
    <AuthenticatedLayout>
      <div class="container mx-auto px-6 py-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">{{ formation.titre }}</h1>
  
        <!-- Image de formation -->
        <div class="mb-6">
          <img v-if="formation.image_formation" :src="`/storage/${formation.image_formation}`" alt="Image formation" class="rounded-lg shadow-lg w-full max-w-md mx-auto">
        </div>
  
        <div class="mb-8">
          <h2 class="text-2xl font-semibold text-gray-700 mb-4">Vidéos</h2>
          <ul v-if="formation.videos.length" class="space-y-4">
            <li v-for="video in formation.videos" :key="video.id" class="bg-gray-100 p-4 rounded-lg shadow-md hover:bg-gray-200 transition duration-200">
              <h3 class="text-xl font-semibold text-gray-800">{{ video.titre }}</h3>
              <video width="100%" class="mt-2 rounded-lg" controls>
                <source :src="video.url" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
              </video>
            </li>
          </ul>
          <p v-else class="text-gray-500">Aucune vidéo disponible.</p>
        </div>
  
        <!-- Documents -->
        <div class="mb-8">
          <h2 class="text-2xl font-semibold text-gray-700 mb-4">Documents</h2>
          <ul v-if="formation.documents && formation.documents.length" class="space-y-4">
            <li v-for="(document, index) in formation.documents" :key="index" class="bg-gray-100 p-4 rounded-lg shadow-md hover:bg-gray-200 transition duration-200">
              <h3 class="text-xl font-semibold text-gray-800">{{ document.titre }}</h3>
  
              <!-- Check if document.file is defined before creating the download link -->
              <a v-if="document.file" :href="`/storage/${document.file}`" target="_blank" class="text-blue-600 hover:text-blue-800 underline mt-2 inline-block">Télécharger le document</a>
              <p v-else class="text-red-500 mt-2">Document non disponible.</p>
            </li>
          </ul>
          <p v-else class="text-gray-500">Aucun document disponible.</p>
        </div>
  
        <div class="text-center">
          <Link href="/formations" class="text-lg font-semibold text-blue-600 hover:text-blue-800 underline">Retour aux formations</Link>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
<script setup lang="ts">
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Define the 'formation' prop with a type
interface Formation {
  titre: string;
  image_formation: string | null;
  videos: { id: number, titre: string, url: string }[];
  documents: { titre: string, file: string | null }[];
}

const props = defineProps<{
  formation: Formation;
}>();
</script>
  