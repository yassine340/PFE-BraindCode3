<script setup>
import { ref, watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    group: Object,
});

const emit = defineEmits(['close', 'worker-added']);

const workersForGroup = ref([]);
const selectedWorkerId = ref(null);
const isLoading = ref(false);

watch(() => props.show, async (value) => {
    if (value && props.group) {
        selectedWorkerId.value = null;
        isLoading.value = true;
        
        try {
            // Get all workers that can be added to this group
            const response = await axios.get('/group-workers');
            // Filter out workers already in the group
            const currentGroupWorkerIds = Array.isArray(props.group.workers) 
                ? props.group.workers.map(w => w.id) 
                : [];
            
            workersForGroup.value = response.data.workers.filter(w => !currentGroupWorkerIds.includes(w.id));
        } catch (error) {
            console.error('Error fetching workers for group:', error);
            workersForGroup.value = [];
        } finally {
            isLoading.value = false;
        }
    }
});

const addWorkerToGroup = async () => {
    if (!selectedWorkerId.value || !props.group) return;
    
    isLoading.value = true;
    try {
        const response = await axios.post('/groups/add-worker', {
            group_id: props.group.id,
            worker_id: selectedWorkerId.value
        });
        
        emit('worker-added', response.data.group);
    } catch (error) {
        console.error('Error adding worker to group:', error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 bg-white">
            <h2 class="text-lg font-medium text-gray-900">
                Ajouter un travailleur à {{ group?.name }}
            </h2>

            <div v-if="isLoading" class="mt-4 text-center py-4">
                <p class="text-gray-500">Chargement des travailleurs disponibles...</p>
            </div>

            <div v-else-if="workersForGroup.length > 0" class="mt-4">
                <div class="mb-4">
                    <InputLabel for="worker_select" value="Select Worker" />
                    <select
                        id="worker_select"
                        v-model="selectedWorkerId"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    >
                        <option value="" disabled>Sélectionnez un travailleur</option>
                        <option v-for="worker in workersForGroup" :key="worker.id" :value="worker.id">
                            {{ worker.first_name }} {{ worker.last_name }}
                        </option>
                    </select>
                </div>
            </div>
            <div v-else class="mt-4 text-center py-4 text-gray-500">
                Aucun travailleur disponible à ajouter à ce groupe.
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="$emit('close')" class="mr-2">Annuler</SecondaryButton>
                <PrimaryButton 
                    @click="addWorkerToGroup" 
                    :disabled="!selectedWorkerId || workersForGroup.length === 0 || isLoading"
                >
                    {{ isLoading ? 'Ajouter...' : 'Ajouter au groupe' }}
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>