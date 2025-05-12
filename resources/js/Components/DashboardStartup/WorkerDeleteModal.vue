<script setup>
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    worker: Object,
});

const emit = defineEmits(['close', 'worker-deleted']);
const isLoading = ref(false);

const deleteWorker = async () => {
    isLoading.value = true;
    try {
        await axios.delete(`/workers/${props.worker.id}`);
        emit('worker-deleted', props.worker.id);
    } catch (error) {
        console.error('Error deleting worker:', error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 bg-white">
            <h2 class="text-lg font-medium text-gray-900">Supprimer le travailleur</h2>
            <p class="mt-2 text-gray-600">
                Êtes-vous sûr de vouloir supprimer {{ worker?.first_name }} {{ worker?.last_name }}? 
                Cette action ne peut pas être annulée.
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="$emit('close')" class="mr-2">Annuler</SecondaryButton>
                <DangerButton @click="deleteWorker" :disabled="isLoading">
                    {{ isLoading ? 'Supprimer...' : 'Supprimer le travailleur' }}
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>