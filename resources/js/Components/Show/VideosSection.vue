<template>
  <div class="mb-8">
    <h4 class="flex items-center text-lg font-bold text-gray-800 mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
      </svg>
      Vidéos
    </h4>
    
    <div class="grid gap-6">
      <div v-for="video in videos" :key="video.id"
          class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100">
        <div class="p-4 border-b border-gray-100">
          <h5 class="font-semibold text-gray-800">{{ video.titre || 'Vidéo' }}</h5>
        </div>
        <video controls class="w-full">
          <source :src="getVideoUrl(video)" type="video/mp4">
          Votre navigateur ne supporte pas la lecture de vidéos.
        </video>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  videos: { id: number; titre?: string; url: string; video_url?: string }[];
}>();

// Fonction utilitaire pour formater les URLs des vidéos
const getVideoUrl = (video) => {
  if (!video || !video.url) {
    console.error('Vidéo ou URL manquante:', video);
    return '';
  }
  
  // NOUVEAU: Utiliser l'URL S3 temporaire si disponible
  if (video.video_url) {
    console.log('Utilisation URL S3:', video.video_url);
    return video.video_url;
  }
  
  let finalUrl = '';
  
  // Logique existante comme fallback pour les anciens fichiers
  if (video.url.startsWith('http://localhost/')) {
    const baseUrl = window.location.origin + '/';
    finalUrl = video.url.replace('http://localhost/', baseUrl);
  }
  else if (video.url.startsWith('http')) {
    finalUrl = video.url;
  } 
  else if (video.url.includes('videos/')) {
    finalUrl = `/storage/${video.url}`;
  } 
  else {
    finalUrl = `/storage/videos/${video.url}`;
  }
  
  console.log('URL vidéo générée (fallback):', finalUrl);
  
  return finalUrl;
};
</script>