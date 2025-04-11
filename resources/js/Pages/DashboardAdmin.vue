<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const user = usePage().props.auth.user;
const stats = ref({
  totalUsers: 0,
  totalFormations: 0,
  formationsEnAttente: 0,
  revenus: 0
});

const pendingFormations = ref([]);
const recentActivities = ref([]);

// Charger les statistiques et données
onMounted(async () => {
  try {
    // Simulé - à remplacer par de vraies requêtes API
    stats.value = {
      totalUsers: 328,
      totalFormations: 47,
      formationsEnAttente: 12,
      revenus: 8750
    };
    
    // Formations en attente de validation
    pendingFormations.value = [
      { id: 1, titre: 'Introduction au Machine Learning', auteur: 'Marie Laurent', date: '2025-03-28', category: 'IA' },
      { id: 2, titre: 'React.js Avancé', auteur: 'Thomas Dubois', date: '2025-03-27', category: 'Développement Web' },
      { id: 3, titre: 'UX Design pour Débutants', auteur: 'Sophie Martin', date: '2025-03-26', category: 'Design' },
    ];
    
    // Activités récentes
    recentActivities.value = [
      { type: 'inscription', user: 'Ahmed Benali', date: '2025-04-03', details: 's\'est inscrit à la plateforme' },
      { type: 'formation', user: 'Laura Petit', date: '2025-04-03', details: 'a créé une nouvelle formation "Cybersécurité 101"' },
      { type: 'achat', user: 'Marc Dupont', date: '2025-04-02', details: 'a acheté la formation "Marketing Digital"' },
      { type: 'validation', user: 'Admin', date: '2025-04-01', details: 'a validé la formation "Développement Mobile avec Flutter"' },
    ];
  } catch (error) {
    console.error('Erreur lors du chargement des données', error);
  }
});

// Fonction pour valider une formation
const validateFormation = async (id, isValid) => {
  try {
    // À implémenter avec votre API
    await axios.put(`/formations/${id}/validate`, { est_valide: isValid });
    
    // Mettre à jour la liste des formations en attente
    pendingFormations.value = pendingFormations.value.filter(f => f.id !== id);
    
    // Mettre à jour les statistiques
    stats.value.formationsEnAttente--;
    
    // Ajouter une notification
    alert(`Formation ${isValid ? 'validée' : 'rejetée'} avec succès!`);
  } catch (error) {
    console.error('Erreur lors de la validation', error);
    alert('Une erreur est survenue lors de la validation');
  }
};
</script>

<template>
    <Head title="Dashboard Administrateur" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Tableau de bord administrateur
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Dernière connexion: Aujourd'hui, 09:45</span>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Bienvenue et date -->
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ user.first_name }} 👋</h1>
                        <p class="text-gray-600">Voici un aperçu de votre plateforme aujourd'hui</p>
                    </div>
                    <div class="bg-indigo-50 px-4 py-2 rounded-lg">
                        <span class="text-indigo-700 font-medium">{{ new Date().toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                    </div>
                </div>

                <!-- Cartes de statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Utilisateurs -->
                    <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 text-sm font-medium">Utilisateurs</h3>
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <span class="text-3xl font-bold text-gray-900">{{ stats.totalUsers }}</span>
                                <p class="text-green-500 text-sm flex items-center mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    +12% ce mois
                                </p>
                            </div>
                            <div class="h-12 w-24 bg-blue-50 rounded-lg overflow-hidden">
                                <!-- Graphique (simulé) -->
                                <div class="flex items-end justify-between h-full px-1">
                                    <div class="w-1 bg-blue-400 rounded-t h-6"></div>
                                    <div class="w-1 bg-blue-400 rounded-t h-8"></div>
                                    <div class="w-1 bg-blue-400 rounded-t h-5"></div>
                                    <div class="w-1 bg-blue-400 rounded-t h-9"></div>
                                    <div class="w-1 bg-blue-400 rounded-t h-7"></div>
                                    <div class="w-1 bg-blue-400 rounded-t h-8"></div>
                                    <div class="w-1 bg-blue-500 rounded-t h-10"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formations -->
                    <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 text-sm font-medium">Formations</h3>
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <span class="text-3xl font-bold text-gray-900">{{ stats.totalFormations }}</span>
                                <p class="text-green-500 text-sm flex items-center mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    +5 nouvelles
                                </p>
                            </div>
                            <div class="h-12 w-24 bg-purple-50 rounded-lg overflow-hidden">
                                <!-- Graphique (simulé) -->
                                <div class="flex items-end justify-between h-full px-1">
                                    <div class="w-1 bg-purple-400 rounded-t h-5"></div>
                                    <div class="w-1 bg-purple-400 rounded-t h-6"></div>
                                    <div class="w-1 bg-purple-400 rounded-t h-8"></div>
                                    <div class="w-1 bg-purple-400 rounded-t h-7"></div>
                                    <div class="w-1 bg-purple-400 rounded-t h-6"></div>
                                    <div class="w-1 bg-purple-400 rounded-t h-9"></div>
                                    <div class="w-1 bg-purple-500 rounded-t h-10"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formations en attente -->
                    <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 text-sm font-medium">En attente</h3>
                            <div class="bg-amber-100 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <span class="text-3xl font-bold text-gray-900">{{ stats.formationsEnAttente }}</span>
                            <Link href="/formations?filter=pending" class="mt-2 block text-amber-600 text-sm font-medium hover:underline">
                                Voir les formations en attente →
                            </Link>
                        </div>
                    </div>

                    <!-- Revenus -->
                    <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 text-sm font-medium">Revenus</h3>
                            <div class="bg-green-100 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <span class="text-3xl font-bold text-gray-900">{{ stats.revenus }}€</span>
                                <p class="text-green-500 text-sm flex items-center mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    +23% ce mois
                                </p>
                            </div>
                            <div class="h-12 w-24 bg-green-50 rounded-lg overflow-hidden">
                                <!-- Graphique (simulé) -->
                                <div class="flex items-end justify-between h-full px-1">
                                    <div class="w-1 bg-green-400 rounded-t h-5"></div>
                                    <div class="w-1 bg-green-400 rounded-t h-7"></div>
                                    <div class="w-1 bg-green-400 rounded-t h-6"></div>
                                    <div class="w-1 bg-green-400 rounded-t h-8"></div>
                                    <div class="w-1 bg-green-400 rounded-t h-7"></div>
                                    <div class="w-1 bg-green-400 rounded-t h-9"></div>
                                    <div class="w-1 bg-green-500 rounded-t h-10"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Actions administratives -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                            <h3 class="text-white font-semibold text-lg">Actions rapides</h3>
                        </div>
                        <div class="p-6 grid grid-cols-2 gap-4">
                            <Link href="/formations/create" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Nouvelle formation</span>
                            </Link>
                            
                            <Link href="/users" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Gérer utilisateurs</span>
                            </Link>
                            
                            <Link href="/categories" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Catégories</span>
                            </Link>
                            
                            <Link href="/analytics" class="flex flex-col items-center justify-center p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Statistiques</span>
                            </Link>
                        </div>
                    </div>
                    
                    <!-- Formations en attente de validation -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden lg:col-span-2">
                        <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600">
                            <h3 class="text-white font-semibold text-lg">Formations en attente de validation</h3>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formation</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="formation in pendingFormations" :key="formation.id">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <Link :href="`/formations/${formation.id}`" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                    {{ formation.titre }}
                                                </Link>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ formation.auteur }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                    {{ formation.category }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ new Date(formation.date).toLocaleDateString('fr-FR') }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button @click="validateFormation(formation.id, true)" 
                                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200">
                                                        Valider
                                                    </button>
                                                    <button @click="validateFormation(formation.id, false)" 
                                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                                        Rejeter
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="pendingFormations.length === 0">
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                Aucune formation en attente de validation.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-right">
                                <Link href="/formations?filter=pending" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    Voir toutes les formations en attente →
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activités récentes -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-teal-600">
                        <h3 class="text-white font-semibold text-lg">Activités récentes</h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li v-for="(activity, activityIdx) in recentActivities" :key="activityIdx">
                                    <div class="relative pb-8">
                                        <span v-if="activityIdx !== recentActivities.length - 1" class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex items-start space-x-3">
                                            <!-- Icône d'activité -->
                                            <div class="relative">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center bg-indigo-100" 
                                                     :class="{
                                                         'bg-blue-100': activity.type === 'inscription',
                                                         'bg-purple-100': activity.type === 'formation',
                                                         'bg-green-100': activity.type === 'achat',
                                                         'bg-amber-100': activity.type === 'validation'
                                                     }">
                                                    <svg v-if="activity.type === 'inscription'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                    </svg>
                                                    <svg v-if="activity.type === 'formation'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                    <svg v-if="activity.type === 'achat'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <svg v-if="activity.type === 'validation'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            <!-- Contenu de l'activité -->
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ activity.user }}</div>
                                                    <p class="mt-0.5 text-sm text-gray-500">{{ activity.date }}</p>
                                                </div>
                                                <div class="mt-2 text-sm text-gray-700">
                                                    <p>{{ activity.details }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-4 text-right">
                            <Link href="/activities" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                Voir toutes les activités →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Animation pour les cartes statistiques */
.hover\:scale-105:hover {
  transition-duration: 0.3s;
}

/* Barre latérale de l'historique des activités */
.flow-root ul li:last-child .absolute.h-full {
  display: none;
}
</style>