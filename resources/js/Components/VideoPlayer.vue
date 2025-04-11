<template>
  <div class="video-player-container relative">
    <!-- Lecteur vidéo -->
    <video 
      ref="videoRef" 
      class="w-full rounded"
      controls
      preload="auto"
      @loadedmetadata="onLoaded"
      @timeupdate="onTimeUpdate"
      @pause="onPause"
      @ended="onEnded"
      @play="onPlay"
    >
      <source :src="videoUrl" type="video/mp4">
      Votre navigateur ne prend pas en charge les vidéos HTML5.
    </video>
    
    <!-- Overlay qui cache complètement la vidéo pendant les 10 premières secondes -->
    <div 
      v-if="showIntroOverlay" 
      class="absolute inset-0 bg-black bg-opacity-90 flex flex-col items-center justify-center text-white z-10"
    >
      <h3 class="text-xl mb-4">Introduction</h3>
      <p class="mb-6">Les 10 premières secondes sont une introduction.</p>
      
      <div v-if="videoIsPlaying" class="mb-6">
        <p class="text-sm text-gray-300 mb-2">L'introduction se termine dans {{ Math.ceil(DEFAULT_START_TIME - currentTime) }}s</p>
        <div class="w-64 h-2 bg-gray-700 rounded-full overflow-hidden">
          <div 
            class="h-full bg-blue-500 transition-all duration-200" 
            :style="{ width: `${(currentTime / DEFAULT_START_TIME) * 100}%` }"
          ></div>
        </div>
      </div>
      
      <button 
        @click="skipIntro" 
        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded transition-colors"
      >
        {{ videoIsPlaying ? 'Passer l\'introduction' : 'Commencer la vidéo' }}
      </button>
    </div>
    
    <!-- Notification de reprise qui s'affiche AVANT la vidéo -->
    <div v-if="showResumePrompt" class="mt-3 bg-blue-50 border border-blue-200 p-3 rounded text-blue-700">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <p>Reprendre la lecture à {{ formatTime(savedTime) }}?</p>
        <div class="flex space-x-2">
          <button 
            @click="resumePlayback" 
            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
          >
            Reprendre
          </button>
          <button 
            @click="startFromBeginning" 
            class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300"
          >
            Depuis le début
          </button>
        </div>
      </div>
    </div>
    
    <!-- Contrôles personnalisés simples -->
    <div class="mt-3 flex justify-between items-center bg-gray-100 p-2 rounded">
      <div class="flex space-x-2">
        <button 
          @click.stop.prevent="rewind"
          class="px-2 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600"
        >
          -5s
        </button>
        
        <button 
          @click.stop.prevent="forward"
          class="px-2 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600"
        >
          +5s
        </button>
      </div>
      
      <div class="text-sm text-gray-600">
        {{ formatTime(currentTime) }} / {{ formatTime(duration) }}
      </div>
    </div>
    
    <!-- Logs de debug -->
    <div v-if="debug" class="mt-3 p-3 bg-gray-100 text-xs font-mono overflow-auto max-h-32">
      <div v-for="(log, index) in debugLogs" :key="index" class="mb-1">
        {{ log }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  videoId: {
    type: Number,
    required: true
  },
  videoUrl: {
    type: String,
    required: true
  }
});

// Définir le temps de démarrage par défaut (10 secondes)
const DEFAULT_START_TIME = 10;

// Références et états
const videoRef = ref(null);
const currentTime = ref(0);
const duration = ref(0);
const savedTime = ref(0);
const showResumePrompt = ref(false);
const showIntroOverlay = ref(true); // Afficher l'overlay par défaut
const videoIsPlaying = ref(false);
const saveTimeout = ref(null);
const debug = ref(true); // Activer/désactiver les logs de debug
const debugLogs = ref([]);
const isVideoLoaded = ref(false);

// Fonctions de debug
const addLog = (message) => {
  const timestamp = new Date().toLocaleTimeString();
  debugLogs.value.unshift(`[${timestamp}] ${message}`);
  console.log(`[VideoPlayer] ${message}`);
  
  // Limiter le nombre de logs
  if (debugLogs.value.length > 50) {
    debugLogs.value = debugLogs.value.slice(0, 50);
  }
};

// Formatage du temps
const formatTime = (seconds) => {
  if (isNaN(seconds) || seconds === Infinity) return '0:00';
  
  const mins = Math.floor(seconds / 60);
  const secs = Math.floor(seconds % 60);
  return `${mins}:${secs < 10 ? '0' + secs : secs}`;
};

// Événement déclenché quand la vidéo commence à jouer
const onPlay = () => {
  if (!videoRef.value) return;
  
  videoIsPlaying.value = true;
  addLog(`Lecture démarrée à ${formatTime(videoRef.value.currentTime)}`);
};

// Passer l'introduction
const skipIntro = () => {
  if (!videoRef.value) return;
  
  if (!videoIsPlaying.value) {
    // Si la vidéo n'est pas en cours de lecture, la démarrer
    addLog('Démarrage de la vidéo (avec overlay)');
    videoRef.value.play()
      .then(() => addLog('Lecture démarrée avec overlay'))
      .catch(e => addLog(`Erreur de lecture: ${e.message}`));
  } else {
    // Si la vidéo est déjà en cours, simplement masquer l'overlay
    addLog('Masquage manuel de l\'overlay d\'introduction');
    showIntroOverlay.value = false;
  }
};

// Actions de navigation
const rewind = () => {
  if (!videoRef.value) return;
  const newPosition = Math.max(0, videoRef.value.currentTime - 5);
  addLog(`Rewind -5s de ${formatTime(videoRef.value.currentTime)} à ${formatTime(newPosition)}`);
  videoRef.value.currentTime = newPosition;
};

const forward = () => {
  if (!videoRef.value) return;
  const newPosition = Math.min(videoRef.value.duration, videoRef.value.currentTime + 5);
  addLog(`Forward +5s de ${formatTime(videoRef.value.currentTime)} à ${formatTime(newPosition)}`);
  videoRef.value.currentTime = newPosition;
};

// Masquer l'overlay quand on dépasse les 10 secondes
watch(() => currentTime.value, (newTime) => {
  if (newTime >= DEFAULT_START_TIME && showIntroOverlay.value) {
    showIntroOverlay.value = false;
    addLog(`Introduction automatiquement masquée à ${formatTime(newTime)}`);
  }
});

// Chargement de la progression
const loadProgress = async () => {
  if (!videoRef.value || !isVideoLoaded.value) return;
  
  try {
    addLog(`Chargement de la progression pour la vidéo ${props.videoId}...`);
    const response = await axios.get(`/video-progress/${props.videoId}`);
    
    if (response.data && response.data.current_time > DEFAULT_START_TIME) {
      savedTime.value = response.data.current_time;
      
      // Vérifier si on est proche de la fin
      const isNearEnd = savedTime.value >= videoRef.value.duration - 10;
      
      if (!isNearEnd && savedTime.value > DEFAULT_START_TIME) {
        addLog(`Progression sauvegardée trouvée: ${formatTime(savedTime.value)}`);
        showResumePrompt.value = true;
        // Masquer l'overlay d'intro si on va reprendre après l'intro
        showIntroOverlay.value = false;
      } else if (isNearEnd) {
        addLog(`Progression proche de la fin, laissant l'overlay d'intro visible`);
      } else {
        addLog(`Progression non significative, laissant l'overlay d'intro visible`);
      }
    } else {
      addLog(`Aucune progression significative trouvée, laissant l'overlay d'intro visible`);
    }
  } catch (error) {
    addLog(`Erreur de chargement de la progression: ${error.message}`);
    console.error('Erreur de chargement de la progression:', error);
  }
};

// Reprendre la lecture à la position sauvegardée
const resumePlayback = () => {
  if (!videoRef.value) return;
  
  addLog(`Reprendre la lecture à ${formatTime(savedTime.value)}`);
  showResumePrompt.value = false;
  showIntroOverlay.value = false; // S'assurer que l'overlay est masqué
  
  // Positionner puis démarrer la lecture
  videoRef.value.currentTime = savedTime.value;
  videoRef.value.play()
    .then(() => addLog(`Lecture démarrée à ${formatTime(videoRef.value.currentTime)}`))
    .catch(e => addLog(`Erreur de lecture: ${e.message}`));
};

// Commencer depuis le début (avec l'overlay)
const startFromBeginning = () => {
  if (!videoRef.value) return;
  
  addLog(`Démarrer depuis le début avec overlay d'intro`);
  videoRef.value.currentTime = 0;
  showResumePrompt.value = false;
  showIntroOverlay.value = true;
  
  // Démarrer la lecture
  videoRef.value.play()
    .then(() => addLog(`Lecture démarrée depuis le début`))
    .catch(e => addLog(`Erreur de lecture: ${e.message}`));
};

// Gestion des événements vidéo
const onLoaded = () => {
  if (!videoRef.value) return;
  
  duration.value = videoRef.value.duration;
  isVideoLoaded.value = true;
  addLog(`Vidéo chargée, durée: ${formatTime(duration.value)}`);
  
  // Charger la progression APRÈS que la vidéo soit chargée
  loadProgress();
};

const onTimeUpdate = () => {
  if (!videoRef.value) return;
  currentTime.value = videoRef.value.currentTime;
  
  // Programmer une sauvegarde après 5 secondes d'inactivité
  clearTimeout(saveTimeout.value);
  saveTimeout.value = setTimeout(() => {
    saveProgress();
  }, 5000);
};

const onPause = () => {
  if (!videoRef.value) return;
  videoIsPlaying.value = false;
  addLog(`Vidéo en pause à ${formatTime(videoRef.value.currentTime)}`);
  saveProgress();
};

const onEnded = () => {
  if (!videoRef.value) return;
  videoIsPlaying.value = false;
  addLog(`Vidéo terminée`);
  saveProgress(true);
};

// Sauvegarde de la progression
const saveProgress = async (completed = false) => {
  if (!videoRef.value || !videoRef.value.currentTime) return;
  
  const time = Math.floor(videoRef.value.currentTime);
  
  // Ne pas sauvegarder si on est encore à la position par défaut ou avant
  if (time <= DEFAULT_START_TIME && !completed) return;
  
  addLog(`Sauvegarde de la progression: ${time}s, terminé: ${completed}`);
  
  try {
    const response = await axios.post('/video-progress', {
      video_id: props.videoId,
      current_time: time,
      completed
    });
    
    addLog(`Progression sauvegardée: ${response.data.message}`);
    
    // Vérifier les détails de la réponse
    if (response.data.data) {
      addLog(`Données sauvegardées: ID=${response.data.data.id}, Temps=${response.data.data.current_time}`);
    }
  } catch (error) {
    let errorMsg = 'Erreur inconnue';
    
    if (error.response) {
      // Le serveur a répondu avec un code d'erreur
      errorMsg = `Erreur serveur: ${error.response.status} - ${error.response.data.message || 'Pas de message'}`;
    } else if (error.request) {
      // La requête a été faite mais pas de réponse
      errorMsg = 'Pas de réponse du serveur';
    } else {
      // Erreur lors de la configuration de la requête
      errorMsg = error.message;
    }
    
    addLog(`Erreur de sauvegarde: ${errorMsg}`);
    console.error('Erreur de sauvegarde:', error);
  }
};

// Nettoyage
onMounted(() => {
  addLog(`Composant monté, ID vidéo: ${props.videoId}`);
  videoRef.value?.setAttribute('disableRemotePlayback', '');

});

onUnmounted(() => {
  addLog('Composant démonté, sauvegarde finale de la progression');
  clearTimeout(saveTimeout.value);
  
  if (videoRef.value && videoRef.value.currentTime > DEFAULT_START_TIME) {
    saveProgress();
  }
});
</script>

<style scoped>
.video-player-container {
  width: 100%;
  position: relative;
}
</style>