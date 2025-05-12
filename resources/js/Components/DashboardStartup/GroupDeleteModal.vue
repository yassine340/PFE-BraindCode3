<script setup>
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    group: Object,
});

const emit = defineEmits(['close', 'group-deleted']);
const isLoading = ref(false);

const deleteGroup = async () => {
    if (!props.group) return;
    
    isLoading.value = true;
    try {
        await axios.delete(`/groups/${props.group.id}`);
        emit('group-deleted', props.group.id);
    } catch (error) {
        console.error('Error deleting group:', error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 bg-white">
            <h2 class="text-lg font-medium text-gray-900">Supprimer le groupe</h2>
            <p class="mt-2 text-gray-600">
                Êtes-vous sûr de vouloir supprimer le groupe "{{ group?.name }}"? 
                Cette action ne peut pas être annulée et supprimera toutes les associations de travailleurs.
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="$emit('close')" class="mr-2">Annuler</SecondaryButton>
                <DangerButton @click="deleteGroup" :disabled="isLoading">
                    {{ isLoading ? 'Supprimer...' : 'Supprimer le groupe' }}
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>