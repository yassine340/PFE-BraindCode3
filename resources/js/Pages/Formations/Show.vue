<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50 py-6">
      <div class="container mx-auto px-4 md:px-6">
        <!-- Back button and header -->
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center">
          </div>
          <div class="flex items-center space-x-4">
            <div class="bg-white rounded-lg shadow px-3 py-2 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="font-bold">10€</span>
            </div>
            <button class="p-2 rounded-lg bg-white shadow">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </button>
            <button class="p-2 rounded-lg bg-white shadow">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Formation Content -->
        <div v-if="!showContent" class="mb-8">
          <!-- Using the FormationDetails component -->
          <FormationDetails 
            :formation="formationWithDefaults" 
            @start-formation="startFormation"
          />
        </div>
        
        <!-- Course Content (After clicking "Inscrire") -->
        <template v-if="showContent && hasAccess">
          <!-- Afficher le compteur d'expiration si la formation a été achetée -->
          <ExpirationCountdown 
  v-if="isAchetee" 
  :dateExpiration="dateExpiration"
  :tempsRestantTexte="tempsRestantTexte"
  :compteurInitial="compteurTemps"
  :formateur="formation?.user"
  @renew-access="handleRenewAccess"
/>
          
          <!-- Current Module -->
          <CurrentModule 
            :currentModule="currentModule" 
            :currentModuleIndex="currentModuleIndex.value" 
            :currentLecon="currentLecon"
            :currentLeconIndex="currentLeconIndex.value"
            :quizValidationStatus="quizValidationStatus"
            :userAnswers="userAnswers"
            @submit-answers="submitAnswers"
          />
          
          <!-- Navigation Controls -->
          <NavigationControls 
            :currentLeconIndex="currentLeconIndex.value"
            :totalLecons="totalLecons"
            :currentModuleIndex="currentModuleIndex.value"
            :totalModules="totalModules"
            :canNavigateToNextLecon="canNavigateToNextLecon"
            :currentLecon="currentLecon"
            :currentLeconHasQuiz="!!currentLecon?.quiz"
            @prev-lecon="prevLecon"
            @next-lecon="nextLecon"
            @prev-module="prevModule"
            @next-module="nextModule"
          />
        </template>
        
        <!-- Access Denied or Payment Required Message -->
        <template v-else-if="showContent && !hasAccess">
          <AccessDenied :formation="formationWithDefaults" />
        </template>
      </div>
    </div>
    
    <!-- Badge Modal -->
    <BadgeModal 
      :show="showBadgeModal" 
      :badgeImage="badgeImage"
      @close="showBadgeModal = false"
    />
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { defineProps, reactive, computed, ref, watch, onMounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FormationDetails from '@/Components/Show/FormationDetails.vue';
import RecommendedCourses from '@/Components/Show/RecommendedCourses.vue';
import CurrentModule from '@/Components/Show/CurrentModule.vue';
import NavigationControls from '@/Components/Show/NavigationControls.vue';
import BadgeModal from '@/Components/Show/BadgeModal.vue';
import AccessDenied from '@/Components/Show/AccessDenied.vue';
import ExpirationCountdown from '@/Components/Show/ExpirationCountdown.vue';

// Définition des types
interface Reponse {
  id: number;
  contenu: string;
  est_correcte: boolean;
}

interface Question {
  id: number;
  contenu: string;
  reponses?: Reponse[];
}

interface Quiz {
  id: number;
  titre: string;
  noteFinale: number;
  dureeMaximale: number;
  questions?: Question[];
}

interface Video {
  id: number;
  titre: string;
  url: string;
}

interface Document {
  id: number;
  titre: string;
  url: string;
}

interface Lecon {
  id: number;
  titre: string;
  contenu: string;
  videos?: Video[];
  documents?: Document[];
  quiz?: Quiz;
}

interface Module {
  id: number;
  titre: string;
  description?: string;
  ordre: number;
  duree_estimee: number;
  lecons?: Lecon[];
}

export interface Formation {
  user: any;
  id: number;
  titre: string;
  prix: number;
  estcertifiante: boolean;
  image_formation: string | null;
  category: { id: number; name: string };
  duree?: string; 
  formateur?: string;
  participants?: string;
  description: string;
  modules?: Module[];
}

interface RecommendedCourse {
  title: string;
  description: string;
  image: string;
}

interface FormattedRecommendedCourse {
  id?: number;
  title: string;
  description: string;
  image: string;
}

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
}

interface PageProps {
  auth: {
    user: User | null;
  };
  [key: string]: any;
}

// Interface pour le compteur de temps
interface CompteurTemps {
  expire: boolean;
  jours: number;
  heures: number;
  minutes: number;
  secondes: number;
}

// Récupération de l'utilisateur connecté
const page = usePage<PageProps>();
const userId = computed(() => page.props.auth.user?.id);
const user = computed(() => page.props.auth.user);
const role = computed(() => user.value?.role || 'user'); 

// Propriétés reçues depuis Laravel (Inertia.js)
const props = defineProps<{ 
  formation?: Formation,
  isAchetee?: boolean,
  dateExpiration?: string,
  tempsRestantTexte?: string,
  compteurTemps?: CompteurTemps
}>();

// Provide default values for required fields in FormationDetails component
const formationWithDefaults = computed(() => {
  if (!props.formation) return null;
  return {
    ...props.formation,
    duree: props.formation.duree || 'N/A',
    formateur: props.formation.formateur || 'Non spécifié',
    participants: props.formation.participants || 'Tous niveaux'
  };
});

// Toggle between formation info and content view
const showContent = ref(false);

// Start the formation (show content)
const startFormation = () => {
  showContent.value = true;
};

// Access control
const hasAccess = computed(() => {
  // L'utilisateur a accès s'il est propriétaire, admin, ou a acheté la formation
  return (
    role.value === 'admin' ||
    (props.formation && props.formation.user?.id === userId.value) ||
    props.isAchetee
  );
});

// Fonction pour gérer le renouvellement d'accès
const handleRenewAccess = () => {
  if (props.formation) {
    // Rediriger vers la page de paiement avec le paramètre de renouvellement
    router.visit(`/formations/${props.formation.id}/payment?renew=true`);
  }
};

// État de validation des quiz
const quizValidationStatus = reactive<Record<number, boolean>>({});
const lastSubmittedResults = ref<any>(null);

// Indices réactifs pour la navigation
const currentModuleIndex = reactive({ value: 0 });
const currentLeconIndex = reactive({ value: 0 });
const currentQuestionIndex = reactive({ value: 0 });

// Réponses utilisateur pour les questions
const userAnswers = reactive<Record<number, number | null>>({});

// Score utilisateur pour les quiz
const score = reactive({ value: 0 });

// État pour le modal de badge
const showBadgeModal = ref(false);
const badgeImage = ref('');

// Modules, leçons et questions actuels
const currentModule = computed(() => props.formation?.modules?.[currentModuleIndex.value]);
const currentLecon = computed(() => currentModule.value?.lecons?.[currentLeconIndex.value]);
const currentQuestion = computed(() => currentLecon.value?.quiz?.questions?.[currentQuestionIndex.value]);

// Totaux
const totalModules = computed(() => props.formation?.modules?.length || 0);
const totalLecons = computed(() => currentModule.value?.lecons?.length || 0);
const totalQuestions = computed(() => currentLecon.value?.quiz?.questions?.length || 0);

// Vérification si on peut naviguer à la leçon suivante
const canNavigateToNextLecon = computed(() => {
  // Si pas de quiz dans la leçon actuelle, on peut passer
  if (!currentLecon.value?.quiz) return true;
  
  // Si le quiz est validé, on peut passer
  return quizValidationStatus[currentLecon.value.quiz.id] === true;
});

// Navigation entre les modules
const nextModule = () => {
  if (currentModuleIndex.value < totalModules.value - 1) {
    currentModuleIndex.value++;
    currentLeconIndex.value = 0; // Réinitialiser l'index des leçons
  }
};

const prevModule = () => {
  if (currentModuleIndex.value > 0) {
    currentModuleIndex.value--;
    currentLeconIndex.value = 0; // Réinitialiser l'index des leçons
  }
};

// Navigation entre les leçons
const nextLecon = () => {
  if (currentLeconIndex.value < totalLecons.value - 1 && canNavigateToNextLecon.value) {
    currentLeconIndex.value++;
    currentQuestionIndex.value = 0; // Réinitialiser l'index des questions
  }
};

const prevLecon = () => {
  if (currentLeconIndex.value > 0) {
    currentLeconIndex.value--;
    currentQuestionIndex.value = 0; // Réinitialiser l'index des questions
  }
};

// Fonction pour soumettre les réponses
const submitAnswers = async () => {
  // Implementation details remain the same...
  // ...
};
</script>

<style scoped>
/* Global styles for the page */
.formation-card {
  transition: all 0.3s ease;
}

.formation-card:hover {
  transform: translateY(-5px);
}
</style>