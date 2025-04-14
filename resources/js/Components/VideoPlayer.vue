<template>
  <div class="video-player-container">
    <video
      ref="videoPlayer"
      class="video-player"


      
   
      controls
    >
      <source :src="videoUrl" :type="videoType">
      Votre navigateur ne supporte pas la lecture de vidéos HTML5.
    </video>
    

    

    
    <!-- Feedback pour la sauvegarde (optionnel, pour débogage) -->
    <div v-if="saveFeedback" class="save-feedback" :class="saveFeedbackType">
      {{ saveFeedback }}
    </div>
  </div>
</template>

<script>
import axios from 'axios';

// Configuration globale d'Axios pour le CSRF
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content;
axios.defaults.headers.common['Accept'] = 'application/json';

export default {
  name: 'VideoPlayer',
  
  props: {
    videoId: {
      type: [String, Number], // Accepte les deux types
      required: true
    },
    videoUrl: {
      type: String,
      required: true
    },
    videoType: {
      type: String,
      default: 'video/mp4'
    },
    debug: {
      type: Boolean,
      default: false
    }
  },
  
  data() {
    return {
      loading: true,
      player: null,
      savedProgress: {
        current_time: 0,
        duration: 0
      },
      hasProgress: false,
      showResumePrompt: false,
      lastSavedTime: 0,
      saveInterval: null,
      saveIntervalTime: 5000, // 5 secondes
      minTimeDifference: 1, // 1 seconde
      saveFeedback: '', // Retour d'info sur les sauvegardes
      saveFeedbackType: 'success', // Type de feedback (success/error)
      videoDuration: 0,
      startTime: 3, // seconde de départ
      hasSeeked: false,
    };
  },
  
  mounted() {
    this.player = this.$refs.videoPlayer;
    this.player.currentTime=3; // Définir le temps de départ à 3 secondes
    console.log('Player:', this.player.currentTime);
    this.player.play().catch(error => {
      console.error('Lecture bloquée, besoin d\'une interaction :', error);
    });
    this.fetchSavedProgress();
    
    // Sauvegarder la progression quand l'utilisateur quitte la page
    window.addEventListener('beforeunload', this.saveProgressBeforeUnload);
    
    if (this.debug) {
      console.log('VideoPlayer monté. ID vidéo:', this.videoId);
    }
  },
  
  beforeUnmount() {
    // Nettoyer l'intervalle et l'écouteur d'événement
    this.clearSaveInterval();
    window.removeEventListener('beforeunload', this.saveProgressBeforeUnload);
    
    // Sauvegarde finale
    const currentTime = this.player ? this.player.currentTime : 0;
    if (currentTime > 0) {
      this.saveProgress(currentTime);
    }
  },
  
  methods: {
    // Quand les métadonnées sont chargées
    onVideoLoaded() {
      this.videoDuration = this.player.duration;
      if (this.debug) {
        console.log('Vidéo chargée. Durée:', this.formatTime(this.videoDuration));
      }
    },
    
    // Récupérer la progression sauvegardée
    async fetchSavedProgress() {
      try {
        if (this.debug) {
          console.log('Récupération de la progression pour video_id:', this.videoId);
        }
        
        const response = await axios.get('/video/get-progress', {
          params: { video_id: this.videoId.toString() } // Convertir explicitement en chaîne
        });
        
        if (this.debug) {
          console.log('Réponse de get-progress:', response.data);
        }
        
        this.savedProgress = response.data;
        this.hasProgress = this.savedProgress.current_time > 0;
        this.lastSavedTime = this.savedProgress.current_time;
        
        if (this.hasProgress) {
          this.showResumePrompt = true;
        } else {
          // Pas de progression, commencer la lecture normalement
          this.loading = false;
        }
      } catch (error) {
        this.handleError('récupération de la progression', error);
        this.loading = false;
      }
    },
    onLoadedMetadata() {
      const video = this.$refs.videoPlayer;
      if (!this.hasSeeked) {
        video.currentTime = this.startTime;
        this.hasSeeked = true;
        this.tryPlay();
      }
    },
    forcePlay() {
      // Forcer la lecture en cas de blocage par l'autoplay policy
      this.tryPlay();
    },
    tryPlay() {
      const video = this.$refs.videoPlayer;
      video.play().catch((error) => {
        console.warn("Lecture bloquée, besoin d'une interaction :", error);
        // L'utilisateur doit cliquer pour autoriser la lecture
      });
    },
    // Gérer les erreurs de façon unifiée
    handleError(action, error) {
      console.error(`Erreur lors de la ${action}:`, error);
      
      let errorMessage = error.message || 'Erreur inconnue';
      
      if (error.response) {
        // Le serveur a répondu avec un code d'erreur
        errorMessage = `Erreur ${error.response.status}: `;
        
        if (error.response.data.message) {
          errorMessage += error.response.data.message;
        } else if (error.response.data.error) {
          errorMessage += error.response.data.error;
        } else if (typeof error.response.data === 'string') {
          errorMessage += error.response.data;
        } else {
          errorMessage += JSON.stringify(error.response.data);
        }
        
        console.error('Détails de la réponse d\'erreur:', error.response.data);
      } else if (error.request) {
        // La requête a été faite mais pas de réponse
        errorMessage = 'Pas de réponse du serveur';
      }
      
      this.showFeedback(errorMessage, 'error');
    },
    
    // Afficher un feedback temporaire
    showFeedback(message, type = 'success') {
      this.saveFeedback = message;
      this.saveFeedbackType = type;
      
      setTimeout(() => {
        this.saveFeedback = '';
      }, 3000);
    },
    
    // Reprendre la lecture depuis la position sauvegardée
    resumeFromSaved() {
      const video = this.$refs.videoPlayer;

// Attendre que les métadonnées soient chargées
video.addEventListener('loadedmetadata', () => {
  video.currentTime = 3; // Aller à la 3e seconde
  video.play().catch(error => {
    console.error('Erreur de lecture :', error);
    // Ajouter feedback à l’utilisateur si besoin
     });
    });
  this.showResumePrompt = false;
  this.loading = false;

  const tryToResume = () => {
    this.player.currentTime = this.savedProgress.current_time;
    this.player.play().catch(error => {
      console.error('Erreur lors du démarrage de la lecture:', error);
      this.showFeedback('Cliquez pour démarrer la lecture', 'info');
    });
  };

  if (this.player.readyState >= 1) {
    // Métadonnées déjà chargées, on peut définir currentTime
    tryToResume();
  } else {
    // Attendre que les métadonnées soient chargées
    this.player.addEventListener('loadedmetadata', tryToResume, { once: true });
  }
},
    
    // Commencer la lecture depuis le début
    startFromBeginning() {
      this.player.currentTime = 0;
      this.showResumePrompt = false;
      this.loading = false;
      this.player.play()
        .catch(error => {
          console.error('Erreur lors du démarrage de la lecture:', error);
          this.showFeedback('Cliquez pour démarrer la lecture', 'info');
        });
    },
    
    // Lorsque la position de la vidéo change
    onTimeUpdate() {
      // La sauvegarde périodique est gérée par saveInterval
    },
    
    // Lorsque la vidéo est mise en pause
    onVideoPause() {
      const currentTime = this.player.currentTime;
      
      // Sauvegarder si suffisamment de temps s'est écoulé depuis la dernière sauvegarde
      if (Math.abs(currentTime - this.lastSavedTime) >= this.minTimeDifference) {
        this.saveProgress(currentTime);
      }
      
      if (this.debug) {
        console.log('Vidéo en pause à:', this.formatTime(currentTime));
      }
    },
    
    // Lorsque la vidéo commence à jouer
    onVideoPlay() {
      // Configurer l'intervalle de sauvegarde périodique
      this.setupSaveInterval();
      
      if (this.debug) {
        console.log('Lecture démarrée à:', this.formatTime(this.player.currentTime));
      }
    },
    
    // Lorsque la vidéo se termine
    onVideoEnded() {
      this.clearSaveInterval();
      // Sauvegarde finale
      this.saveProgress(this.player.duration);
      
      if (this.debug) {
        console.log('Vidéo terminée, progression enregistrée');
      }
    },
    
    // Configurer l'intervalle de sauvegarde périodique
    setupSaveInterval() {
      // S'assurer qu'il n'y a pas d'intervalle existant
      this.clearSaveInterval();
      
      // Créer un nouvel intervalle
      this.saveInterval = setInterval(() => {
        if (this.player && !this.player.paused) {
          const currentTime = this.player.currentTime;
          
          // Sauvegarder si suffisamment de temps s'est écoulé depuis la dernière sauvegarde
          if (Math.abs(currentTime - this.lastSavedTime) >= this.minTimeDifference) {
            this.saveProgress(currentTime);
          }
        }
      }, this.saveIntervalTime);
    },
    
    // Nettoyer l'intervalle de sauvegarde
    clearSaveInterval() {
      if (this.saveInterval) {
        clearInterval(this.saveInterval);
        this.saveInterval = null;
      }
    },
    
    // Sauvegarder la progression
    async saveProgress(currentTime) {
      try {
        // Valider que currentTime est un nombre valide
        if (isNaN(currentTime) || currentTime < 0) {
          console.warn('Tentative de sauvegarde avec une position invalide:', currentTime);
          return;
        }
        
        // Préparer les données en s'assurant qu'elles sont au bon format
        const payload = {
          video_id: this.videoId.toString(), // Utiliser toString() pour s'assurer que c'est une chaîne
          current_time: Math.round(currentTime * 10) / 10, // Arrondir à 1 décimale
          duration: Math.round(this.videoDuration * 10) / 10 // Arrondir à 1 décimale
        };
        
        if (this.debug) {
          console.log('Sauvegarde de la progression:', payload);
        }
        
        const response = await axios.post('/video/save-progress', payload);
        
        if (response.data.success) {
          this.lastSavedTime = currentTime;
          
          if (this.debug) {
            console.log('Progression sauvegardée avec succès à', this.formatTime(currentTime));
          }
        } else {
          console.warn('La sauvegarde a retourné une réponse non réussie:', response.data);
        }
      } catch (error) {
        this.handleError('sauvegarde de la progression', error);
      }
    },
    
    // Sauvegarder la progression avant de quitter la page
    saveProgressBeforeUnload() {
      const currentTime = this.player ? this.player.currentTime : 0;
      
      if (this.player && Math.abs(currentTime - this.lastSavedTime) >= this.minTimeDifference) {
        // Utiliser sendBeacon pour garantir l'envoi avant la fermeture
        const data = new FormData();
        data.append('video_id', this.videoId.toString());
        data.append('current_time', Math.round(currentTime * 10) / 10);
        data.append('duration', Math.round(this.videoDuration * 10) / 10);
        
        // Ajouter le token CSRF
        const tokenElement = document.querySelector('meta[name="csrf-token"]');
        if (tokenElement) {
          data.append('_token', tokenElement.getAttribute('content'));
        }
        
        navigator.sendBeacon('/video/save-progress', data);
        
        if (this.debug) {
          console.log('Progression sauvegardée avant fermeture à', this.formatTime(currentTime));
        }
      }
    },
    
    // Formater le temps en minutes:secondes
    formatTime(seconds) {
      if (isNaN(seconds) || seconds === null) return '0:00';
      
      const minutes = Math.floor(seconds / 60);
      const remainingSeconds = Math.floor(seconds % 60);
      return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }
  }
};
</script>

<style scoped>
.video-player-container {
  position: relative;
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
}

.video-player {
  width: 100%;
  display: block;
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
}

.spinner {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top: 4px solid #ffffff;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

.resume-prompt {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: rgba(0, 0, 0, 0.85);
  color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  width: 80%;
  max-width: 400px;
  z-index: 10;
}

.resume-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 15px;
}

.btn-resume, .btn-restart {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}

.btn-resume {
  background-color: #4CAF50;
  color: white;
}

.btn-restart {
  background-color: #f44336;
  color: white;
}

.save-feedback {
  margin-top: 10px;
  padding: 8px 12px;
  border-radius: 4px;
  text-align: center;
  font-size: 14px;
}

.save-feedback.success {
  background-color: #e8f5e9;
  color: #2e7d32;
  border: 1px solid #a5d6a7;
}

.save-feedback.error {
  background-color: #ffebee;
  color: #c62828;
  border: 1px solid #ef9a9a;
}

.save-feedback.info {
  background-color: #e3f2fd;
  color: #1565c0;
  border: 1px solid #90caf9;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>