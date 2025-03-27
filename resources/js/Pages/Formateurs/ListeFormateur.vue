<script setup>
import { defineProps, ref, computed } from "vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  formateurs: {
    type: Array,
    default: () => [] // Provide a default empty array
  }
});

// Reactive list for updates after deletion
const formateursList = ref(props.formateurs || []);
const searchQuery = ref('');

// Computed filtered and sorted list
const filteredFormateurs = computed(() => {
  // Ensure formateursList is an array
  const list = Array.isArray(formateursList.value) ? formateursList.value : [];
  const query = searchQuery.value.toLowerCase().trim();
  return list.filter(formateur => 
    formateur &&
    formateur.first_name &&
    (
      formateur.first_name.toLowerCase().includes(query) ||
      (formateur.last_name && formateur.last_name.toLowerCase().includes(query)) ||
      (formateur.email && formateur.email.toLowerCase().includes(query))
    )
  );
});

// Functions for formateur actions
const detailsFormateur = (id) => {
  router.visit(`/formateurs/${id}`);
};

const modifierFormateur = (id) => {
  router.visit(`/formateurs/${id}/edit`);
};

const deleteFormateur = async (id) => {
  if (confirm("Voulez-vous vraiment supprimer ce formateur ?")) {
    try {
      await axios.delete(`/formateurs/${id}`);
      formateursList.value = formateursList.value.filter(f => f.id !== id);
      
      // More sophisticated notification
      const notification = document.createElement('div');
      notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
      notification.textContent = 'Formateur supprimé avec succès !';
      document.body.appendChild(notification);
      
      setTimeout(() => {
        document.body.removeChild(notification);
      }, 3000);
    } catch (error) {
      console.error("Erreur lors de la suppression :", error);
      alert("Une erreur s'est produite lors de la suppression.");
    }
  }
};

// Pagination
const currentPage = ref(1);
const itemsPerPage = ref(10);

const paginatedFormateurs = computed(() => {
  // Ensure filteredFormateurs is an array
  const list = Array.isArray(filteredFormateurs.value) ? filteredFormateurs.value : [];
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return list.slice(start, end);
});

const totalPages = computed(() => {
  // Ensure filteredFormateurs is an array
  const list = Array.isArray(filteredFormateurs.value) ? filteredFormateurs.value : [];
  return Math.max(1, Math.ceil(list.length / itemsPerPage.value));
});
</script>

<template>
  <Head title="Liste des formateurs" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-4">
          <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Gestion des Formateurs
          </h2>
        </div>
        <div class="flex space-x-4 w-full md:w-auto">
          <div class="relative flex-grow">
            <input 
              v-model="searchQuery" 
              placeholder="Rechercher un formateur..." 
              class="w-full px-4 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:text-white"
            />
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
      </div>
    </template>
    
    <div class="container mx-auto px- py-1 max-w-6x6">
      <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-blue-600 text-white">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Prénom</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nom</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="formateur in paginatedFormateurs" 
                :key="formateur.id" 
                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group"
              >
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ formateur.id }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 font-medium">{{ formateur.first_name }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 font-medium">{{ formateur.last_name }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ formateur.email }}</td>
                <td class="px-6 py-4">
                  <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button 
                      @click="detailsFormateur(formateur.id)"
                      class="text-blue-500 hover:text-blue-600 bg-blue-50 hover:bg-blue-100 p-2 rounded-full transition-all"
                      title="Détails"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </button>
                    <button 
                      @click="modifierFormateur(formateur.id)"
                      class="text-green-500 hover:text-green-600 bg-green-50 hover:bg-green-100 p-2 rounded-full transition-all"
                      title="Modifier"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                      </svg>
                    </button>
                    <button 
                      @click="deleteFormateur(formateur.id)"
                      class="text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-full transition-all"
                      title="Supprimer"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="paginatedFormateurs.length === 0">
                <td colspan="5" class="text-center py-6 text-gray-500 dark:text-gray-400">
                  Aucun formateur trouvé
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex flex-col md:flex-row justify-between items-center">
          <span class="text-sm text-gray-600 dark:text-gray-400 mb-4 md:mb-0">
            Total: {{ filteredFormateurs.length }} formateurs
          </span>
          <div class="flex space-x-2">
            <button 
              @click="currentPage = Math.max(1, currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Précédent
            </button>
            <span class="px-4 py-2 bg-blue-500 text-white rounded-lg">
              {{ currentPage }} / {{ totalPages }}
            </span>
            <button 
              @click="currentPage = Math.min(totalPages, currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Suivant
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Previous custom scrollbar styles remain the same */
</style>