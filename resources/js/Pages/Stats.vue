<template>
    <AuthenticatedLayout>
      <div class="min-h-screen py-12 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Header -->
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-10">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 py-6 px-6">
              <h1 class="text-3xl font-bold text-white text-center">
                Mes statistiques d'apprentissage
              </h1>
            </div>
            
            <div class="p-6">
              <div v-if="isLoading" class="text-center py-10">
                <div class="spinner-border text-indigo-500" role="status">
                  <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-gray-600">Chargement de vos statistiques...</p>
              </div>
              
              <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-4">
                <span class="block sm:inline">{{ error }}</span>
              </div>
              
              <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Vue d'ensemble -->
                <div class="lg:col-span-1 space-y-6">
                  <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <h3 class="text-xl font-bold mb-4">Score global</h3>
                    <div class="flex items-center space-x-2">
                      <span class="text-4xl font-bold">{{ userStats.total_points }}</span>
                      <span class="text-lg">points</span>
                    </div>
                    <div class="mt-4">
                      <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        Niveau: {{ userStats.global_level }}
                      </span>
                    </div>
                  </div>
                  
                  <!-- Progression -->
                  <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Votre progression</h3>
                    <div class="space-y-4">
                      <div>
                        <div class="flex justify-between text-sm font-medium text-gray-500 mb-1">
                          <span>Débutant</span>
                          <span>Expert</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                          <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full"
                              :style="{ width: calculateProgressPercent() + '%' }"></div>
                        </div>
                      </div>
                      <div class="text-sm text-gray-500">
                        <p>
                          <span class="font-medium text-indigo-600">{{ calculateNextLevelPoints() }}</span> 
                          points nécessaires pour atteindre le niveau suivant
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Badges -->
                <div class="lg:col-span-2">
                  <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Mes badges ({{ userStats.badges_count }})</h3>
                    
                    <div v-if="userStats.badges.length === 0" class="text-center py-8 text-gray-500">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      </svg>
                      <p class="text-lg font-medium">Vous n'avez pas encore obtenu de badges</p>
                      <p class="mt-1">Complétez des quiz pour gagner vos premiers badges !</p>
                    </div>
                    
                    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div v-for="badge in userStats.badges" :key="badge.id" 
                           class="flex flex-col items-center p-4 bg-gradient-to-b from-indigo-50 to-purple-50 rounded-lg shadow-sm">
                        <div class="h-24 w-24 mb-3 relative">
                          <img :src="badge.image" :alt="badge.name" class="h-full w-full object-contain">
                          <div class="absolute -right-2 -bottom-2 bg-green-500 h-6 w-6 rounded-full flex items-center justify-center border-2 border-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                          </div>
                        </div>
                        <h4 class="text-base font-semibold text-indigo-800">{{ badge.name }}</h4>
                        <p class="text-sm text-gray-600 text-center mt-2">{{ badge.description }}</p>
                        <div class="mt-3 text-xs text-gray-500">
                          Obtenu le {{ formatDate(badge.obtained_at) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Historique des quiz -->
              <div class="mt-8 bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Historique des quiz</h3>
                
                <div v-if="userStats.quiz_history.length === 0" class="text-center py-8 text-gray-500">
                  <p class="text-lg">Vous n'avez pas encore complété de quiz.</p>
                </div>
                
                <div v-else class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Quiz
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Formation
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Score
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Date
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="quiz in userStats.quiz_history" :key="quiz.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          {{ quiz.quiz?.titre || 'Quiz inconnu' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ getFormationTitle(quiz) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {{ quiz.score }} points
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="quiz.is_valid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            {{ quiz.is_valid ? 'Validé' : 'Non validé' }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ formatDate(quiz.updated_at) }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import axios from 'axios';
  
  const userStats = ref({
    total_points: 0,
    global_level: 'Débutant',
    badges_count: 0,
    badges: [],
    quiz_history: []
  });
  
  const isLoading = ref(true);
  const error = ref(null);
  
  onMounted(async () => {
    try {
      const response = await axios.get('/gamification/stats');
      userStats.value = response.data.data;
      isLoading.value = false;
    } catch (err) {
      error.value = "Erreur lors du chargement de vos statistiques. Veuillez réessayer.";
      console.error('Erreur lors du chargement des statistiques:', err);
      isLoading.value = false;
    }
  });
  
  const formatDate = (dateString) => {
    if (!dateString) return 'Date inconnue';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('fr-FR', { 
      day: 'numeric', 
      month: 'long', 
      year: 'numeric'
    }).format(date);
  };
  
  const getFormationTitle = (quiz) => {
    try {
      return quiz.quiz?.lecon?.module?.formation?.titre || 'Formation inconnue';
    } catch (e) {
      return 'Formation inconnue';
    }
  };
  
  const calculateProgressPercent = () => {
    // Calcul approximatif basé sur les niveaux définis dans le contrôleur
    const totalPoints = userStats.value.total_points;
    
    if (userStats.value.global_level === 'Débutant') {
      // Progression vers Intermédiaire (1000 points)
      return Math.min(100, (totalPoints / 1000) * 100);
    } else if (userStats.value.global_level === 'Intermédiaire') {
      // Progression vers Expert (2000 points)
      return Math.min(100, 25 + ((totalPoints - 1000) / 1000) * 25);
    } else if (userStats.value.global_level === 'Expert') {
      // Progression vers Maître (5000 points)
      return Math.min(100, 50 + ((totalPoints - 2000) / 3000) * 25);
    } else {
      // Maître (max level)
      return 100;
    }
  };
  
  const calculateNextLevelPoints = () => {
    const totalPoints = userStats.value.total_points;
    
    if (userStats.value.global_level === 'Débutant') {
      return 1000 - totalPoints;
    } else if (userStats.value.global_level === 'Intermédiaire') {
      return 2000 - totalPoints;
    } else if (userStats.value.global_level === 'Expert') {
      return 5000 - totalPoints;
    } else {
      return 'Niveau maximum atteint';
    }
  };
  </script>
  
  <style scoped>
  .spinner-border {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border .75s linear infinite;
  }
  
  @keyframes spinner-border {
    to { transform: rotate(360deg); }
  }
  </style>
  