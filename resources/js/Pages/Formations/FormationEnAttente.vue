<template>
    <Head title="Dashboard Administrateur" />
    <AuthenticatedLayout>
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Gestion des Formations</h1>
          </div>
          
          <!-- Filters -->
          <div class="mt-4 flex flex-col sm:flex-row gap-4">
            <div class="relative flex-grow">
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Rechercher une formation..."
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
              <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            
            <div class="sm:w-64">
              <select
                v-model="statusFilter"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="all">Tous les statuts</option>
                <option value="EnAttente">En attente</option>
                <option value="Validée">Validées</option>
                <option value="Rejetée">Rejetées</option>
              </select>
            </div>
          </div>
          
          <!-- Loading State -->
          <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
          </div>
          
          <!-- No Formations State -->
          <div v-else-if="filteredFormations.length === 0" class="bg-white shadow overflow-hidden sm:rounded-lg p-6 text-center mt-4">
            <p class="text-gray-500">Aucune formation trouvée correspondant à vos critères.</p>
          </div>
          
          <!-- Formations List -->
          <div v-else class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:pl-6">ID</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formation</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                      <tr v-for="formation in filteredFormations" :key="formation.id">
                        <td class="whitespace-nowrap px-3 py-4 text-sm sm:pl-6">
                          <div class="text-sm font-medium text-gray-900">#{{ formation.id }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                          <div class="flex-shrink-0 h-12 w-12">
                            <img 
                              v-if="formation.image_formation" 
                              :src="'/storage/' + formation.image_formation" 
                              class="h-12 w-12 object-cover rounded-md"
                              alt="Formation thumbnail"
                            >
                            <div v-else class="h-12 w-12 bg-gray-200 rounded-md flex items-center justify-center">
                              <span class="text-gray-500 text-xs">No Image</span>
                            </div>
                          </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                          <div class="text-sm font-medium text-gray-900">{{ formation.titre }}</div>
                          <div class="text-sm text-gray-500">{{ formation.sous_titre }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                          <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                              <span v-if="!formation.user?.profile_photo_path" 
                                    class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                {{ getInitials(formation.user?.first_name, formation.user?.last_name) }}
                              </span>
                              <img v-else class="h-10 w-10 rounded-full" :src="formation.user.profile_photo_path" alt="User Photo" />
                            </div>
                            <div class="ml-4">
                              <div class="font-medium text-gray-900">{{ formation.user?.first_name }} {{ formation.user?.last_name }}</div>
                              <div class="text-gray-500">{{ formation.user?.email }}</div>
                            </div>
                          </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          {{ formatDate(formation.created_at) }} à {{ formatTime(formation.created_at) }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          {{ formation.category?.name || 'Non catégorisée' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                          <span :class="[getStatusClass(formation.est_valide), 'px-2.5 py-0.5 rounded-full text-xs font-medium']">
                            {{ getStatusText(formation.est_valide) }}
                          </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium">
                        <div class="flex space-x-3">
                            <button 
                            @click="viewFormationDetails(formation.id)" 
                            class="text-blue-600 hover:text-blue-900"
                            title="Voir"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            </button>
                            <button 
                            v-if="formation.est_valide === 'EnAttente'"
                            @click="toggleModal(formation.id, 'validate')" 
                            class="text-green-600 hover:text-green-900"
                            title="Valider"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            </button>
                            <button 
                            v-if="formation.est_valide === 'EnAttente'"
                            @click="toggleModal(formation.id, 'reject')" 
                            class="text-red-600 hover:text-red-900"
                            title="Rejeter"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            </button>
                        </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Modal -->
        <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
          <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
              <div>
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ modalAction === 'validate' ? 'Valider la formation' : 'Rejeter la formation' }}
                  </h3>
                  <div class="mt-4">
                    <p class="text-sm text-gray-500">
                      {{ modalAction === 'validate' 
                        ? 'Êtes-vous sûr de vouloir valider cette formation ? Elle pourra ensuite être publiée par le formateur.' 
                        : 'Veuillez indiquer la raison du rejet' }}
                    </p>
                    
                    <div v-if="modalAction === 'reject'" class="mt-3">
                      <textarea
                        v-model="rejectionReason"
                        rows="4"
                        placeholder="Raison du rejet..."
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button
                  @click="submitAction"
                  :disabled="modalAction === 'reject' && !rejectionReason || processing"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:col-start-2 sm:text-sm"
                  :class="[
                    modalAction === 'validate' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
                    (modalAction === 'reject' && !rejectionReason) || processing ? 'opacity-50 cursor-not-allowed' : ''
                  ]"
                >
                  <span v-if="processing" class="mr-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                  </span>
                  {{ modalAction === 'validate' ? 'Valider' : 'Rejeter' }}
                </button>
                <button
                  @click="closeModal"
                  :disabled="processing"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                  :class="{ 'opacity-50 cursor-not-allowed': processing }"
                >
                  Annuler
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Notification -->
        <div v-if="showNotification" 
             class="fixed bottom-0 right-0 m-6 p-4 rounded-md shadow-lg"
             :class="{ 'bg-green-50': notificationType === 'success', 'bg-red-50': notificationType === 'error' }">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg v-if="notificationType === 'success'" class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <svg v-else class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium" :class="{ 'text-green-800': notificationType === 'success', 'text-red-800': notificationType === 'error' }">
                {{ notificationMessage }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script>
  import { ref, computed } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import axios from 'axios';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  
  export default {
    components: {
      AuthenticatedLayout,
      Head
    },
    
    // Receive formations as a prop from the controller
    props: {
      formations: Array,
    },
    
    setup(props) {
      const showModal = ref(false);
      const selectedFormationId = ref(null);
      const modalAction = ref('validate');
      const loading = ref(false);
      const processing = ref(false);
      const rejectionReason = ref('');
      const searchQuery = ref('');
      const statusFilter = ref('all');
      const showNotification = ref(false);
      const notificationMessage = ref('');
      const notificationType = ref('success');
  
      // Filter formations based on search and status filter
      const filteredFormations = computed(() => {
        let result = props.formations;
        
        // Apply search filter
        if (searchQuery.value) {
          const query = searchQuery.value.toLowerCase();
          result = result.filter(formation => 
            formation.titre?.toLowerCase().includes(query) ||
            formation.user?.first_name?.toLowerCase().includes(query) ||
            formation.user?.last_name?.toLowerCase().includes(query) ||
            formation.user?.email?.toLowerCase().includes(query) ||
            formation.category?.name?.toLowerCase().includes(query)
          );
        }
        
        // Apply status filter
        if (statusFilter.value !== 'all') {
          result = result.filter(formation => {
            if (statusFilter.value === 'EnAttente') return formation.est_valide === 'EnAttente';
            if (statusFilter.value === 'Validée') return formation.est_valide === 'Validée' || formation.est_valide === true;
            if (statusFilter.value === 'Rejetée') return formation.est_valide === 'Rejetée' || formation.est_valide === false;
            return true;
          });
        }
        
        return result;
      });
  
      const formatDate = (dateString) => {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('fr-FR', {
          year: 'numeric',
          month: '2-digit',
          day: '2-digit'
        }).format(date);
      };
  
      const formatTime = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('fr-FR', {
          hour: '2-digit',
          minute: '2-digit'
        }).format(date);
      };
  
      const getInitials = (firstName, lastName) => {
        if (!firstName && !lastName) return 'U';
        return `${firstName?.charAt(0) || ''}${lastName?.charAt(0) || ''}`.toUpperCase();
      };
      
      const toggleModal = (formationId, action) => {
        selectedFormationId.value = formationId;
        modalAction.value = action;
        rejectionReason.value = '';
        showModal.value = true;
      };
      
      const closeModal = () => {
        showModal.value = false;
        selectedFormationId.value = null;
        rejectionReason.value = '';
      };
      
      const viewFormationDetails = (id) => {
        Inertia.visit(`/formations/${id}`);
      };
      
      const getStatusClass = (status) => {
        if (status === 'Validée' || status === true) return 'bg-green-100 text-green-800';
        if (status === 'Rejetée' || status === false) return 'bg-red-100 text-red-800';
        return 'bg-yellow-100 text-yellow-800';
      };
      
      const getStatusText = (status) => {
        if (status === 'Validée' || status === true) return 'Validée';
        if (status === 'Rejetée' || status === false) return 'Rejetée';
        return 'En attente';
      };
      
      const submitAction = async () => {
        if (processing.value) return;
        
        processing.value = true;
        let url;
        
        if (modalAction.value === 'validate') {
          url = `/formations/${selectedFormationId.value}/valider`;
        } else if (modalAction.value === 'reject') {
          url = `/formations/${selectedFormationId.value}/rejeter`;
        } else {
          console.error('Unknown action');
          return;
        }
        
        try {
          const payload = modalAction.value === 'reject' ? { reason: rejectionReason.value } : {};
          const response = await axios.post(url, payload);
          
          // Show notification
          notificationMessage.value = modalAction.value === 'validate' 
            ? 'Formation validée avec succès!' 
            : 'Formation rejetée avec succès!';
          notificationType.value = 'success';
          showNotification.value = true;
          
          // Update the formation status in the UI without reloading
          const formationIndex = props.formations.findIndex(f => f.id === selectedFormationId.value);
          if (formationIndex !== -1) {
            props.formations[formationIndex].est_valide = modalAction.value === 'validate' ? 'Validée' : 'Rejetée';
          }
          
          // Hide notification after 3 seconds
          setTimeout(() => {
            showNotification.value = false;
          }, 3000);
          
          closeModal();
        } catch (error) {
          console.error('Error processing action:', error);
          notificationMessage.value = 'Une erreur est survenue lors du traitement de votre demande.';
          notificationType.value = 'error';
          showNotification.value = true;
          
          setTimeout(() => {
            showNotification.value = false;
          }, 3000);
        } finally {
          processing.value = false;
        }
      };
  
      return {
        showModal,
        selectedFormationId,
        modalAction,
        loading,
        processing,
        rejectionReason,
        searchQuery,
        statusFilter,
        showNotification,
        notificationMessage,
        notificationType,
        filteredFormations,
        formatDate,
        formatTime,
        getInitials,
        toggleModal,
        closeModal,
        submitAction,
        getStatusClass,
        getStatusText,
        viewFormationDetails
      };
    }
  };
  </script>