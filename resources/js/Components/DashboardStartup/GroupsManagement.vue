<script setup>
import { ref, onMounted } from 'vue';
import GroupCard from '@/Components/DashboardStartup/GroupCard.vue';
import GroupFormModal from '@/Components/DashboardStartup/GroupFormModal.vue';
import GroupDeleteModal from '@/Components/DashboardStartup/GroupDeleteModal.vue';
import AddWorkerToGroupModal from '@/Components/DashboardStartup/AddWorkerToGroupModal.vue';
import axios from 'axios';

// Groups state
const groups = ref([]);
const selectedGroup = ref(null);
const isGroupFormVisible = ref(false);
const isGroupDeleteModalVisible = ref(false);
const isAddWorkerToGroupModalVisible = ref(false);
const groupMode = ref('view');
const isLoading = ref(true);
const error = ref(null);

// Load data on mount
onMounted(() => {
    fetchGroups();
});

// Groups methods
const fetchGroups = async () => {
    isLoading.value = true;
    error.value = null;
    
    try {
        const response = await axios.get('/api/groups');
        
        if (response.data && response.data.groups) {
            groups.value = response.data.groups.map(group => {
                return {
                    ...group,
                    workers: Array.isArray(group.workers) ? group.workers : [],
                    isExpanded: false
                };
            });
        } else {
            groups.value = [];
        }
    } catch (err) {
        console.error('Error fetching groups:', err);
        error.value = 'Failed to load groups. Please try again.';
        groups.value = [];
    } finally {
        isLoading.value = false;
    }
};

const showGroupForm = (mode, group = null) => {
    groupMode.value = mode;
    selectedGroup.value = group;
    isGroupFormVisible.value = true;
};

const showDeleteGroupModal = (group) => {
    selectedGroup.value = group;
    isGroupDeleteModalVisible.value = true;
};

const showAddWorkerToGroupModal = (group) => {
    selectedGroup.value = group;
    isAddWorkerToGroupModalVisible.value = true;
};

const handleGroupCreated = (group) => {
    groups.value.push({
        ...group,
        workers: [],
        isExpanded: false
    });
    isGroupFormVisible.value = false;
};

const handleGroupUpdated = (updatedGroup) => {
    const index = groups.value.findIndex(g => g.id === updatedGroup.id);
    if (index !== -1) {
        // Preserve the workers array and expanded state
        groups.value[index] = {
            ...updatedGroup,
            workers: groups.value[index].workers || [],
            isExpanded: groups.value[index].isExpanded
        };
    }
    isGroupFormVisible.value = false;
};

const handleGroupDeleted = (groupId) => {
    groups.value = groups.value.filter(g => g.id !== groupId);
    isGroupDeleteModalVisible.value = false;
};

const handleWorkerAddedToGroup = (updatedGroup) => {
    const index = groups.value.findIndex(g => g.id === updatedGroup.id);
    if (index !== -1) {
        groups.value[index] = {
            ...updatedGroup,
            workers: updatedGroup.workers || [],
            isExpanded: groups.value[index].isExpanded
        };
    }
    isAddWorkerToGroupModalVisible.value = false;
};

const handleWorkerRemovedFromGroup = (groupId, updatedWorkers) => {
    const index = groups.value.findIndex(g => g.id === groupId);
    if (index !== -1) {
        groups.value[index].workers = updatedWorkers;
    }
};

const refreshGroups = () => {
    fetchGroups();
};
</script>

<template>
    <div>
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="sm:flex sm:justify-between sm:items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Gestion du groupe</h3>
                    <p class="text-gray-500 text-sm">Organisez vos travailleurs en équipes fonctionnelles</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button 
                        @click="showGroupForm('create')" 
                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-700 focus:ring ring-primary-300 disabled:opacity-25 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Créer un groupe
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading state -->
        <div v-if="isLoading" class="flex justify-center items-center py-16">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="text-red-500 mb-4">{{ error }}</div>
            <button 
                @click="refreshGroups" 
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Essayer à nouveau
            </button>
        </div>

        <!-- Empty state -->
        <div v-else-if="groups.length === 0" class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun groupe créé pour le moment</h3>
            <p class="mt-1 text-sm text-gray-600">Créez des groupes pour organiser efficacement les membres de votre équipe.</p>
            <div class="mt-6">
                <button 
                    @click="showGroupForm('create')" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Créer un groupe
                </button>
            </div>
        </div>

        <!-- Groups grid -->
<div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <!-- Add New Group Card -->
    <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 flex flex-col items-center justify-center p-6 h-full transition-all hover:border-blue-400 cursor-pointer" @click="showGroupForm('create')">
        <div class="bg-blue-100 rounded-full p-4 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </div>
        <h3 class="font-medium text-gray-900 mb-1">Nouveau groupe</h3>
        <p class="text-sm text-gray-500 text-center">Créer un groupe supplémentaire pour votre équipe</p>
    </div>
    
    <!-- Existing Group Cards -->
    <GroupCard
        v-for="group in groups"
        :key="group.id"
        :group="group"
        @edit="showGroupForm('edit', $event)"
        @delete="showDeleteGroupModal"
        @add-worker="showAddWorkerToGroupModal"
        @remove-worker="handleWorkerRemovedFromGroup"
    />
</div>
        
        <!-- Modals -->
        <GroupFormModal
            :show="isGroupFormVisible"
            :mode="groupMode"
            :group="selectedGroup"
            @close="isGroupFormVisible = false"
            @group-created="handleGroupCreated"
            @group-updated="handleGroupUpdated"
        />
        
        <GroupDeleteModal
            :show="isGroupDeleteModalVisible"
            :group="selectedGroup"
            @close="isGroupDeleteModalVisible = false"
            @group-deleted="handleGroupDeleted"
        />
        
        <AddWorkerToGroupModal
            :show="isAddWorkerToGroupModalVisible"
            :group="selectedGroup"
            @close="isAddWorkerToGroupModalVisible = false"
            @worker-added="handleWorkerAddedToGroup"
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