<template>
  <Head title="Découvrir nos formations" />
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-10">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <!-- En-tête avec animation -->
        <div class="text-center mb-12">
          <h1 class="text-4xl md:text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
            Découvrez nos formations
          </h1>
          <p class="text-gray-600 max-w-2xl mx-auto">
            Développez vos compétences et perfectionnez votre expertise avec notre sélection de formations de qualité.
          </p>
        </div>

        <!-- Barre de recherche -->
        <div class="mb-10 max-w-md mx-auto">
          <div class="relative flex items-center w-full h-12 rounded-lg focus-within:shadow-lg bg-white overflow-hidden border border-gray-200 shadow-sm">
            <div class="grid place-items-center h-full w-12 text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              v-model="searchQuery"
              @input="searchFormations"
              class="peer h-full w-full outline-none text-sm text-gray-700 pr-2 border-0 focus:ring-0"
              type="text"
              placeholder="Rechercher une formation..."
            />
          </div>
        </div>
        
        <!-- Filtre validée/en attente (Admin) -->
        <div v-if="role !== 'user' && role !== 'startup' && role !== 'formateur'" class="mb-8 flex justify-center">
          <div class="inline-flex rounded-md shadow-sm" role="group">
            <button @click="filterStatus = 'all'"
                    :class="[
                      'px-4 py-2 text-sm font-medium border',
                      filterStatus === 'all' 
                        ? 'bg-indigo-600 text-white border-indigo-600' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                    ]"
                    class="rounded-l-lg">
              Toutes
            </button>
            <button @click="filterStatus = 'validated'"
                    :class="[
                      'px-4 py-2 text-sm font-medium border-t border-b border-r',
                      filterStatus === 'validated' 
                        ? 'bg-green-600 text-white border-green-600' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                    ]">
              Validées
            </button>
            <button @click="filterStatus = 'pending'"
                    :class="[
                      'px-4 py-2 text-sm font-medium border-t border-b border-r',
                      filterStatus === 'pending' 
                        ? 'bg-yellow-500 text-white border-yellow-500' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                    ]"
                    class="rounded-r-lg">
              En attente
            </button>
          </div>
        </div>

        <!-- Filtre publié/non publié (Admin et Formateur) -->
        <div v-if="role !== 'user' && role !== 'startup'" class="mb-8 flex justify-center mt-4">
          <div class="inline-flex rounded-md shadow-sm" role="group">
            <button @click="filterPublication = 'all'"
                    :class="[
                      'px-4 py-2 text-sm font-medium border',
                      filterPublication === 'all' 
                        ? 'bg-indigo-600 text-white border-indigo-600' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                    ]"
                    class="rounded-l-lg">
              Toutes
            </button>
            <button @click="filterPublication = 'published'"
                    :class="[
                      'px-4 py-2 text-sm font-medium border-t border-b border-r',
                      filterPublication === 'published' 
                        ? 'bg-green-600 text-white border-green-600' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                    ]">
              Publiées
            </button>
            <button @click="filterPublication = 'unpublished'"
                    :class="[
                      'px-4 py-2 text-sm font-medium border-t border-b border-r',
                      filterPublication === 'unpublished' 
                        ? 'bg-orange-500 text-white border-orange-500' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                    ]"
                    class="rounded-r-lg">
              Non publiées
            </button>
          </div>
        </div>

        <!-- Ajouter une formation (Admin et Formateur) -->
        <div v-if="role !== 'user' && role !== 'startup'" class="mb-10 text-center">
          <Link href="/formations/create" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-800 transition duration-300 transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Ajouter une formation
          </Link>
        </div>

        <!-- Grille des formations -->
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-8">
          <div v-for="formation in filteredFormations" :key="formation.id" 
              class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 transform hover:shadow-xl hover:-translate-y-2 flex flex-col h-full">
            
            <!-- Image avec overlay -->
            <div class="relative overflow-hidden h-48 md:h-56 lg:h-64">
              <img v-if="formation.image_formation" 
                  :src="`/storage/${formation.image_formation}`" 
                  alt="Image formation" 
                  class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
              <div v-else class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              
              <!-- Badge prix/statut (optionnel) -->
              <div class="absolute top-3 right-3 bg-white bg-opacity-90 rounded-full py-1 px-3 text-sm font-semibold text-indigo-600 shadow-sm">
                {{ formation.prix ? formation.prix + ' €' : 'Gratuit' }}
              </div>
              
              <!-- Badge de statut de validation (pour admin) -->
              <div v-if="role !== 'user' && role !== 'startup'" 
                   class="absolute top-3 left-3 py-1 px-3 rounded-full text-sm font-semibold shadow-sm"
                   :class="formation.est_valide 
                           ? 'bg-green-100 text-green-700 border border-green-200' 
                           : 'bg-yellow-100 text-yellow-700 border border-yellow-200'">
                {{ formation.est_valide ? 'Validée' : 'En attente' }}
              </div>
              
              <!-- Badge de statut de publication (pour admin et formateur) -->
              <div v-if="role !== 'user' && role !== 'startup'" 
                   class="absolute top-12 left-3 py-1 px-3 rounded-full text-sm font-semibold shadow-sm"
                   :class="formation.est_publiee 
                           ? 'bg-green-100 text-green-700 border border-green-200' 
                           : 'bg-orange-100 text-orange-700 border border-orange-200'">
                {{ formation.est_publiee ? 'Publiée' : 'Non publiée' }}
              </div>
            </div>
            
            <div class="flex-1 p-6 flex flex-col">
              <!-- Titre et infos -->
              <Link :href="`/formations/${formation.id}`" class="block">
                <h2 class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-all mb-2 line-clamp-2">
                  {{ formation.titre }}
                </h2>
              </Link>
              
              <!-- Description tronquée (si disponible) -->
              <p class="text-gray-600 text-sm mb-4 flex-1 line-clamp-3">
                {{ formation.description || "Découvrez cette formation et développez vos compétences professionnelles." }}
              </p>
              
              <!-- Métadonnées -->
              <div class="flex items-center text-sm text-gray-500 mb-4">
                <div class="flex items-center mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>{{ formation.duree || "À votre rythme" }}</span>
                </div>
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                  <span>{{ formation.modules?.length || "0" }} modules</span>
                </div>
              </div>

              <!-- Afficher le formateur si disponible -->
              <div v-if="formation.user" class="flex items-center text-sm text-gray-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Par: {{ formateurName(formation.user) }}</span>
              </div>
              
              <!-- Bouton de validation (admin seulement) -->
              <div v-if="role !== 'user' && role !== 'startup' && role !== 'formateur'" class="mb-4">
                <button @click.prevent="toggleValidation(formation)"
                        class="w-full py-2 px-4 rounded-lg text-sm font-medium transition duration-300"
                        :class="formation.est_valide 
                                ? 'bg-red-50 text-red-600 hover:bg-red-100' 
                                : 'bg-green-50 text-green-600 hover:bg-green-100'">
                  {{ formation.est_valide ? 'Invalider la formation' : 'Valider la formation' }}
                </button>
              </div>
              
              <!-- Message d'info si formateur et formation non validée -->
              <div v-if="role === 'formateur' && !formation.est_valide" 
                  class="text-xs text-gray-500 italic text-center mb-2">
                Cette formation doit être validée par un administrateur avant de pouvoir être publiée.
              </div>
              
              <!-- Bouton de publication (admin et formateur) -->
              <div v-if="role !== 'user' && role !== 'startup'" class="mb-4">
                <button @click.prevent="togglePublication(formation)"
                        :disabled="role === 'formateur' && !formation.est_valide"
                        class="w-full py-2 px-4 rounded-lg text-sm font-medium transition duration-300"
                        :class="[
                          formation.est_publiee 
                            ? 'bg-orange-50 text-orange-600 hover:bg-orange-100' 
                            : 'bg-blue-50 text-blue-600 hover:bg-blue-100',
                          (role === 'formateur' && !formation.est_valide) 
                            ? 'opacity-50 cursor-not-allowed' : ''
                        ]">
                  <span v-if="role === 'formateur' && !formation.est_valide">
                    Validation requise pour publier
                  </span>
                  <span v-else>
                    {{ formation.est_publiee ? 'Dépublier la formation' : 'Publier la formation' }}
                  </span>
                </button>
              </div>
              
              <!-- Bouton d'action -->
              <Link :href="`/formations/${formation.id}`"
                    class="text-center py-2 px-4 bg-indigo-50 text-indigo-600 font-medium rounded-lg hover:bg-indigo-100 transition duration-300 mt-auto">
                Découvrir la formation
              </Link>
            </div>
          </div>
        </div>
        
        <!-- Pas de formations disponibles -->
        <div v-if="filteredFormations.length === 0" class="text-center py-12">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune formation disponible</h3>
          <p class="text-gray-500">Revenez plus tard pour découvrir nos nouvelles formations.</p>
        </div>
        
        <!-- Pagination (si nécessaire) -->
        <div class="mt-12 flex justify-center">
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
              <span class="sr-only">Précédent</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </a>
            <a href="#" aria-current="page" class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
              1
            </a>
            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
              2
            </a>
            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium">
              3
            </a>
            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
              <span class="sr-only">Suivant</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </a>
          </nav>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  
<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';
import axios from 'axios';

// Définir le type User
interface User {
  id: number;
  first_name: string;  // Remplacé "name" par "first_name"
  last_name: string;   // Ajouté "last_name"
  email: string;
  role: string;
}

// Définir les propriétés de la page
interface PageProps {
  auth: {
    user: User | null;
  };
  [key: string]: unknown;
}

// Définir le type Formation
interface Formation {
  id: number;
  titre: string;
  description?: string;
  image_formation: string | null;
  duree?: string;
  modules?: any[];
  est_valide: boolean;
  est_publiee: boolean;
  user?: User;  // Ajouté la référence à l'utilisateur
  prix?: number; // Ajouté la propriété prix
}

// Helper pour obtenir le nom complet du formateur
const formateurName = (user) => {
  if (!user) return 'Formateur inconnu';
  return `${user.first_name} ${user.last_name}`;
};

// État pour la recherche
const searchQuery = ref("");
const searchFormations = async () => {
  await router.get("/formations", {
    search: searchQuery.value,
  }, { preserveState: true });
};

// État pour le filtre de statut de validation
const filterStatus = ref('all');

// État pour le filtre de publication
const filterPublication = ref('all');

// Propriétés reçues
const props = defineProps<{
  formations: Formation[];
}>();

// Accéder à l'objet route() d'Inertia
const page = usePage<PageProps>();

// Récupérer le rôle de l'utilisateur
const role = computed(() => page.props.auth.user?.role || 'user');

// Fonction pour filtrer les formations selon le statut et le rôle
const filteredFormations = computed(() => {
  let formations = props.formations;
  
  // Si l'utilisateur est "user" ou "startup", ne montrer que les formations validées ET publiées
  if (role.value === 'user' || role.value === 'startup') {
    return formations.filter(f => f.est_valide === true && f.est_publiee === true);
  }
  
  // Pour les formateurs ou admin, appliquer les filtres sélectionnés
  
  // Filtre par statut de validation
  if (filterStatus.value !== 'all') {
    formations = formations.filter(f => {
      if (filterStatus.value === 'validated') return f.est_valide === true;
      if (filterStatus.value === 'pending') return f.est_valide === false;
      return true;
    });
  }
  
  // Filtre par statut de publication
  if (filterPublication.value !== 'all') {
    formations = formations.filter(f => {
      if (filterPublication.value === 'published') return f.est_publiee === true;
      if (filterPublication.value === 'unpublished') return f.est_publiee === false;
      return true;
    });
  }
  
  return formations;
});

// Fonction pour valider/invalider une formation
const toggleValidation = async (formation) => {
  try {
    const response = await axios.put(`/formations/${formation.id}/validate`, {
      est_valide: !formation.est_valide
    });
    
    // Mettre à jour la formation localement
    formation.est_valide = !formation.est_valide;
    
    // Afficher un message de succès
    const message = formation.est_valide 
      ? 'Formation validée avec succès!' 
      : 'Formation invalidée avec succès!';
      
    // Vous pouvez ajouter une notification ici
    alert(message);
    
  } catch (error) {
    console.error('Erreur lors de la validation:', error);
    
    // Afficher le message d'erreur du serveur si disponible
    if (error.response && error.response.data && error.response.data.error) {
      alert('Erreur: ' + error.response.data.error);
    } else {
      alert('Une erreur est survenue pendant la validation.');
    }
  }
};

// Fonction pour publier/dépublier une formation
const togglePublication = async (formation) => {
  // Ne pas traiter si le formateur tente de publier une formation non validée
  if (role.value === 'formateur' && !formation.est_valide) {
    alert('Cette formation doit être validée par un administrateur avant de pouvoir être publiée.');
    return;
  }
  
  try {
    const response = await axios.put(`/formations/${formation.id}/publish`, {
      est_publiee: !formation.est_publiee
    });
    
    // Mettre à jour la formation localement
    formation.est_publiee = !formation.est_publiee;
    
    // Afficher un message de succès
    const message = formation.est_publiee 
      ? 'Formation publiée avec succès!' 
      : 'Formation dépubliée avec succès!';
      
    alert(message);
    
  } catch (error) {
    console.error('Erreur lors de la publication:', error);
    
    // Afficher le message d'erreur du serveur si disponible
    if (error.response && error.response.data && error.response.data.error) {
      alert('Erreur: ' + error.response.data.error);
    } else if (error.response && error.response.data && error.response.data.message) {
      alert('Erreur: ' + error.response.data.message);
    } else {
      alert('Une erreur est survenue pendant la publication.');
    }
  }
};

</script>

<style>
/* Effet de dégradé pour le texte du titre */
.bg-clip-text {
  -webkit-background-clip: text;
  background-clip: text;
}

/* Pour limiter le nombre de lignes (description) */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>