<template>
    <div class="expiration-counter">
      <div v-if="!isExpired" class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-lg">
        <h3 class="font-bold">Temps d'accès restant:</h3>
        
        <!-- Mode texte -->
        <p v-if="!showCounter" class="mt-2">{{ tempsRestantTexte }}</p>
        
        <!-- Mode compteur dynamique -->
        <div v-else class="mt-2 grid grid-cols-4 gap-2 text-center">
          <div class="bg-blue-200 p-2 rounded">
            <div class="text-xl font-bold">{{ compteur.jours || 0 }}</div>
            <div class="text-xs">Jours</div>
          </div>
          <div class="bg-blue-200 p-2 rounded">
            <div class="text-xl font-bold">{{ compteur.heures || 0 }}</div>
            <div class="text-xs">Heures</div>
          </div>
          <div class="bg-blue-200 p-2 rounded">
            <div class="text-xl font-bold">{{ compteur.minutes || 0 }}</div>
            <div class="text-xs">Minutes</div>
          </div>
          <div class="bg-blue-200 p-2 rounded">
            <div class="text-xl font-bold">{{ compteur.secondes || 0 }}</div>
            <div class="text-xs">Secondes</div>
          </div>
        </div>
        
        <button 
          @click="toggleCounter" 
          class="mt-2 text-blue-600 underline text-sm"
        >
          {{ showCounter ? "Afficher en texte" : "Afficher le compteur" }}
        </button>
      </div>
      
      <!-- Section d'expiration améliorée avec informations du formateur -->
      <div v-else class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-4 rounded-lg">
        <h3 class="font-bold text-lg">Accès expiré</h3>
        <p class="mt-2">La période d'accès à cette formation est terminée. Vous pouvez renouveler votre accès pour continuer votre apprentissage.</p>
        
        <div class="mt-4 border-t border-red-200 pt-3">
          <p class="font-medium">Si vous rencontrez des difficultés ou avez des questions, n'hésitez pas à contacter le formateur :</p>
          
          <div v-if="formateur" class="mt-2 bg-white p-3 rounded-lg shadow-sm">
            <div class="flex items-center">
              <div class="bg-red-100 p-2 rounded-full mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <p class="font-bold">{{ formateur.name || formateur.first_name + ' ' + formateur.last_name || 'N/A' }}</p>
                <p class="text-sm text-gray-600">Formateur</p>
              </div>
            </div>
            
            <div class="mt-3 space-y-2">
              <div v-if="formateur.email" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <a href="mailto:{{ formateur.email }}" class="text-red-700 hover:underline">{{ formateur.email }}</a>
              </div>
              <div v-if="formateur.phone" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>{{ formateur.phone }}</span>
              </div>
            </div>
          </div>
          <div v-else class="mt-2 text-gray-600 italic">
            Informations du formateur non disponibles.
          </div>
        </div>
        
 
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted, computed } from 'vue';
  
  const props = defineProps({
    dateExpiration: {
      type: String,
      required: true
    },
    tempsRestantTexte: {
      type: String,
      required: true
    },
    compteurInitial: {
      type: Object,
      required: true
    },
    formateur: {
      type: Object,
      required: false,
      default: () => null
    }
  });
  
  defineEmits(['renew-access']);
  
  // Changer la valeur par défaut à true pour afficher le compteur directement
  const showCounter = ref(true);
  const isExpired = ref(false);
  
  // Vérification et initialisation sécurisée du compteur
  const compteur = ref({
    jours: 0,
    heures: 0,
    minutes: 0,
    secondes: 0
  });
  
  // Initialiser le compteur de façon sécurisée
  const initCompteur = () => {
    if (!props.compteurInitial) {
      return;
    }
    
    // Vérifier si les données sont valides et les assigner avec des valeurs par défaut
    isExpired.value = props.compteurInitial.expire === true;
    compteur.value = {
      jours: props.compteurInitial.jours || 0,
      heures: props.compteurInitial.heures || 0,
      minutes: props.compteurInitial.minutes || 0,
      secondes: props.compteurInitial.secondes || 0
    };
  }
  
  let timer = null;
  let updateCount = 0;
  
  const toggleCounter = () => {
    showCounter.value = !showCounter.value;
  };
  
  const updateCounter = () => {
    updateCount++;
    
    if (isExpired.value) {
      return;
    }
    
    compteur.value.secondes--;
    
    if (compteur.value.secondes < 0) {
      compteur.value.secondes = 59;
      compteur.value.minutes--;
      
      if (compteur.value.minutes < 0) {
        compteur.value.minutes = 59;
        compteur.value.heures--;
        
        if (compteur.value.heures < 0) {
          compteur.value.heures = 23;
          compteur.value.jours--;
          
          if (compteur.value.jours < 0) {
            // Le temps est écoulé
            isExpired.value = true;
            clearInterval(timer);
            return;
          }
        }
      }
    }
  };
  
  const formatDate = (dateString) => {
    try {
      const date = new Date(dateString);
      if (isNaN(date.getTime())) {
        return "Date invalide";
      }
      return date.toLocaleDateString('fr-FR');
    } catch (error) {
      return "Date non disponible";
    }
  };
  
  onMounted(() => {
    // Initialiser le compteur en vérifiant les valeurs
    initCompteur();
    
    // Mise à jour du compteur chaque seconde
    if (!isExpired.value) {
      timer = setInterval(updateCounter, 1000);
    }
  });
  
  onUnmounted(() => {
    if (timer) {
      clearInterval(timer);
    }
  });
  </script>