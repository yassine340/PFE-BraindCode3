<script setup>
import { ref, onMounted, computed } from 'vue';
import WorkerTable from '@/Components/DashboardStartup/WorkerTable.vue';
import WorkerFormModal from '@/Components/DashboardStartup/WorkerFormModal.vue';
import WorkerDeleteModal from '@/Components/DashboardStartup/WorkerDeleteModal.vue';
import axios from 'axios';

// Workers state
const workers = ref([]);
const filteredWorkers = ref([]);
const selectedWorker = ref(null);
const isWorkerFormVisible = ref(false);
const isWorkerDeleteModalVisible = ref(false);
const workerMode = ref('view');
const searchQuery = ref('');
const isLoading = ref(true);
const error = ref(null);

// Pagination
const currentPage = ref(1);
const perPage = ref(10);
const totalWorkers = computed(() => filteredWorkers.value.length);
const totalPages = computed(() => Math.ceil(totalWorkers.value / perPage.value));
const paginatedWorkers = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return filteredWorkers.value.slice(start, end);
});

// Load data on mount
onMounted(() => {
    fetchWorkers();
});

// Workers methods
const fetchWorkers = async () => {
    isLoading.value = true;
    error.value = null;
    
    try {
        const response = await axios.get('/workers');
        workers.value = response.data.workers;
        filteredWorkers.value = [...workers.value];
    } catch (err) {
        console.error('Error fetching workers:', err);
        error.value = 'Failed to load workers. Please try again.';
    } finally {
        isLoading.value = false;
    }
};

const searchWorkers = () => {
    if (!searchQuery.value.trim()) {
        filteredWorkers.value = [...workers.value];
        return;
    }
    
    const query = searchQuery.value.toLowerCase();
    filteredWorkers.value = workers.value.filter(worker => 
        worker.first_name.toLowerCase().includes(query) || 
        worker.last_name.toLowerCase().includes(query) || 
        worker.email.toLowerCase().includes(query) || 
        (worker.phone && worker.phone.toLowerCase().includes(query))
    );
    
    // Reset to first page when searching
    currentPage.value = 1;
};

const showWorkerForm = (mode, worker = null) => {
    workerMode.value = mode;
    selectedWorker.value = worker;
    isWorkerFormVisible.value = true;
};

const confirmDeleteWorker = (worker) => {
    selectedWorker.value = worker;
    isWorkerDeleteModalVisible.value = true;
};

const handleWorkerCreated = (worker) => {
    workers.value.unshift(worker); // Add to beginning of list
    filteredWorkers.value = [...workers.value];
    isWorkerFormVisible.value = false;
    
    // Apply search filter if active
    if (searchQuery.value) {
        searchWorkers();
    }
};

const handleWorkerUpdated = (updatedWorker) => {
    const index = workers.value.findIndex(w => w.id === updatedWorker.id);
    if (index !== -1) {
        workers.value[index] = updatedWorker;
        filteredWorkers.value = [...workers.value];
    }
    isWorkerFormVisible.value = false;
    
    // Apply search filter if active
    if (searchQuery.value) {
        searchWorkers();
    }
};

const handleWorkerDeleted = (workerId) => {
    workers.value = workers.value.filter(w => w.id !== workerId);
    filteredWorkers.value = filteredWorkers.value.filter(w => w.id !== workerId);
    isWorkerDeleteModalVisible.value = false;
    
    // Reset to first page if current page is now empty
    if (paginatedWorkers.value.length === 0 && currentPage.value > 1) {
        currentPage.value = Math.max(1, currentPage.value - 1);
    }
};

const changePage = (page) => {
    currentPage.value = page;
};

const refreshWorkers = () => {
    fetchWorkers();
    searchQuery.value = '';
};
</script>

<template>
    <div>
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="sm:flex sm:justify-between sm:items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Gestion des travailleurs</h3>
                    <p class="text-gray-500 text-sm">Gérez les membres de votre équipe et leurs accès</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button 
                        @click="showWorkerForm('create')" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un travailleur
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Search and actions bar -->
            <div class="p-4 border-b border-gray-200 bg-gray-50 sm:flex sm:justify-between sm:items-center">
                <div class="relative rounded-md shadow-sm max-w-xs w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        v-model="searchQuery" 
                        @input="searchWorkers" 
                        placeholder="Search workers..." 
                        class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-10 py-2 sm:text-sm border-gray-300 rounded-md"
                    >
                    <div v-if="searchQuery" @click="searchQuery = ''; searchWorkers()" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                        <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 sm:mt-0 text-sm text-gray-500 flex items-center">
                    <button @click="refreshWorkers" class="flex items-center text-primary-600 hover:text-primary-700">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Rafraîchir
                    </button>
                    <span class="ml-3">nombre total de travailleurs:{{ totalWorkers }} </span>
                </div>
            </div>

            <!-- Loading state -->
            <div v-if="isLoading" class="flex justify-center items-center py-16">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
            </div>

            <!-- Error state -->
            <div v-else-if="error" class="p-8 text-center">
                <div class="text-red-500 mb-4">{{ error }}</div>
                <button @click="fetchWorkers" class="text-primary-600 hover:text-primary-700">
                    Essayer à nouveau
                </button>
            </div>

            <!-- Empty state -->
            <div v-else-if="workers.length === 0" class="p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Pas encore de travailleurs</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par ajouter votre premier membre d’équipe.</p>
                <div class="mt-6">
                    <button 
                        @click="showWorkerForm('create')" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un travailleur
                    </button>
                </div>
            </div>

            <!-- No search results -->
            <div v-else-if="filteredWorkers.length === 0" class="p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun résultat trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">Nous n'avons trouvé aucun travailleur correspondant "{{ searchQuery }}".</p>
                <div class="mt-6">
                    <button 
                        @click="searchQuery = ''; searchWorkers()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        Effacer la recherche
                    </button>
                </div>
            </div>

            <!-- Workers table -->
            <WorkerTable 
                v-else
                :workers="paginatedWorkers" 
                @edit-worker="showWorkerForm('edit', $event)"
                @delete-worker="confirmDeleteWorker"
            />
            
            <!-- Pagination -->
            <div v-if="totalPages > 1" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage <span class="font-medium">{{ (currentPage - 1) * perPage + 1 }}</span> à 
                            <span class="font-medium">{{ Math.min(currentPage * perPage, totalWorkers) }}</span> de 
                            <span class="font-medium">{{ totalWorkers }}</span> résultats
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <!-- Previous Page Button -->
                            <button
                                @click="currentPage > 1 ? changePage(currentPage - 1) : null"
                                :disabled="currentPage === 1"
                                :class="[
                                    'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500',
                                    currentPage === 1 
                                        ? 'opacity-50 cursor-not-allowed'
                                        : 'hover:bg-gray-50'
                                ]"
                            >
                                <span class="sr-only">Précédente</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <!-- Page Numbers -->
                            <template v-for="page in totalPages" :key="page">
                                <button
                                    v-if="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
                                    @click="changePage(page)"
                                    :class="[
                                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                        currentPage === page 
                                            ? 'z-10 bg-primary-50 border-primary-500 text-primary-600' 
                                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                    ]"
                                >
                                    {{ page }}
                                </button>
                                <span
                                    v-else-if="page === 2 || page === totalPages - 1"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                >
                                    ...
                                </span>
                            </template>
                            
                            <!-- Next Page Button -->
                            <button
                                @click="currentPage < totalPages ? changePage(currentPage + 1) : null"
                                :disabled="currentPage === totalPages"
                                :class="[
                                    'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500',
                                    currentPage === totalPages 
                                        ? 'opacity-50 cursor-not-allowed'
                                        : 'hover:bg-gray-50'
                                ]"
                            >
                                <span class="sr-only">Suivante</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modals -->
        <WorkerFormModal
            :show="isWorkerFormVisible"
            :mode="workerMode"
            :worker="selectedWorker"
            @close="isWorkerFormVisible = false"
            @worker-created="handleWorkerCreated"
            @worker-updated="handleWorkerUpdated"
        />
        
        <WorkerDeleteModal
            :show="isWorkerDeleteModalVisible"
            :worker="selectedWorker"
            @close="isWorkerDeleteModalVisible = false"
            @worker-deleted="handleWorkerDeleted"
        />
    </div>
</template>

<style scoped>
.animate-spin {
    animation: spin 1s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>