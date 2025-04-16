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
    <div class="py-12">
      <div class="container mx-auto px-4 max-w-6xl">
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
          <div class="p-6 bg-gradient-to-br from-blue-50 to-blue-100">
            <div class="overflow-x-auto">
              <table class="w-full divide-y divide-gray-200">
                <thead class="bg-blue-100">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-200 transition-colors">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-200 transition-colors">Prénom</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-200 transition-colors">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-200 transition-colors">Email</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-blue-700 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr 
                    v-for="formateur in paginatedFormateurs" 
                    :key="formateur.id" 
                    class="hover:bg-blue-50 transition-colors group"
                  >
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formateur.id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ formateur.first_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ formateur.last_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 group-hover:underline">{{ formateur.email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex justify-center space-x-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button 
                          @click="detailsFormateur(formateur.id)"
                          class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white hover:bg-blue-600 transition-colors"
                          title="Détails"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </button>
                        <button 
                          @click="modifierFormateur(formateur.id)"
                          class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors"
                          title="Modifier"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                          </svg>
                        </button>
                        <button 
                          @click="deleteFormateur(formateur.id)"
                          class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors"
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
                    <td colspan="5" class="text-center py-8 text-gray-500">
                      <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xl">Aucun formateur trouvé</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-6 py-4 flex flex-col md:flex-row justify-between items-center">
            <span class="text-sm text-gray-600 mb-4 md:mb-0">
              Total: {{ filteredFormateurs.length }} formateurs
            </span>
            <div class="flex space-x-4">
              <button 
                @click="currentPage = Math.max(1, currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-4 py-2 bg-white text-blue-700 rounded-lg hover:bg-blue-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Précédent
              </button>
              <span class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                {{ currentPage }} / {{ totalPages }}
              </span>
              <button 
                @click="currentPage = Math.min(totalPages, currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-white text-blue-700 rounded-lg hover:bg-blue-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Suivant
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Add custom styles for better spacing */
table {
  border-collapse: separate;
  border-spacing: 0;
}

th, td {
  padding: 1rem 1.5rem;
}

/* Make header sticky if desired */
thead {
  position: sticky;
  top: 0;
  z-index: 10;
}
</style>