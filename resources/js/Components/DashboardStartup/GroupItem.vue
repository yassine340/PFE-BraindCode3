<script setup>
import { ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import axios from 'axios';

const props = defineProps({
    group: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['edit-group', 'delete-group', 'add-worker', 'remove-worker']);

const toggleExpanded = () => {
    props.group.isExpanded = !props.group.isExpanded;
};

const removeWorker = async (worker) => {
    try {
        const response = await axios.post('/groups/remove-worker', {
            group_id: props.group.id,
            worker_id: worker.id
        });
        
        // Get updated workers list
        const updatedWorkers = Array.isArray(response.data.group.workers) 
            ? response.data.group.workers 
            : [];
            
        emit('remove-worker', props.group.id, updatedWorkers);
    } catch (error) {
        console.error('Erreur lors de la suppression du travailleur du groupe:', error);
    }
};
</script>

<template>
    <div class="bg-gray-50 p-4 rounded-lg shadow">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h4 class="text-lg font-medium">{{ group.name }}</h4>
                <p v-if="group.description" class="text-gray-600 mt-1">{{ group.description }}</p>
            </div>
            <div class="flex space-x-2">
                <SecondaryButton @click="$emit('edit-group', group)">Modifier</SecondaryButton>
                <DangerButton @click="$emit('delete-group', group)">Delete</DangerButton>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <h5 class="font-medium">Travailleurs de ce groupe</h5>
                <SecondaryButton @click="$emit('add-worker', group)">Ajouter un travailleur</SecondaryButton>
            </div>
            <div class="bg-white rounded-md shadow-sm p-2 mt-2">
                <ul v-if="Array.isArray(group.workers) && group.workers.length > 0" class="divide-y divide-gray-100">
                    <li v-for="worker in group.workers" :key="worker.id" class="flex justify-between py-2 px-2">
                        <span>{{ worker.first_name }} {{ worker.last_name }}</span>
                        <button @click="removeWorker(worker)" class="text-red-600 text-sm hover:text-red-800">
                            Retirer
                        </button>
                    </li>
                </ul>
                <div v-else class="py-2 text-center text-gray-500">
                    No workers in this group.
                </div>
            </div>
        </div>
    </div>
</template>