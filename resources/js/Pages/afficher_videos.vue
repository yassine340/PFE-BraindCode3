<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const videos = ref([]);

const fetchVideos = async () => {
  try {
    const response = await axios.get('/videos');
    console.log(response.data);
    videos.value = response.data;
  } catch (error) {
    console.error("Erreur lors de la récupération des vidéos :", error);
  }
};

onMounted(fetchVideos);
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Liste des vidéos" />
    
    <div class="max-w-7xl mx-auto p-6">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">🎥 Liste des vidéos</h2>
      
      <div v-if="videos.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="(video, index) in videos" :key="index" class="bg-white shadow-lg rounded-lg p-4">
          <video controls class="w-full h-auto rounded-lg">
            <source :src="video" type="video/mp4">
            Votre navigateur ne supporte pas la lecture de cette vidéo.
          </video>
          <p class="text-gray-700 text-center mt-2">Vidéo {{ index + 1 }}</p>
        </div>
      </div>
      
      <p v-else class="text-center text-gray-600 mt-6">🚫 Aucune vidéo trouvée.</p>
    </div>
  </AuthenticatedLayout>
</template>
