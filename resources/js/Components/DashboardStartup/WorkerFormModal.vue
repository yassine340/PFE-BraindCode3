<script setup>
import { ref, watch, nextTick } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    mode: {
        type: String,
        default: 'create'
    },
    worker: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'worker-created', 'worker-updated']);

const workerForm = ref({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    phone: '',
});

const workerErrors = ref({});
const isLoading = ref(false);
const firstNameInput = ref(null);

// Reset form when modal is opened
watch(() => props.show, async (value) => {
    if (value) {
        workerErrors.value = {};
        
        if (props.mode === 'create') {
            workerForm.value = {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                phone: '',
            };
        } else if (props.mode === 'edit' && props.worker) {
            workerForm.value = {
                first_name: props.worker.first_name,
                last_name: props.worker.last_name,
                email: props.worker.email,
                password: '',
                phone: props.worker.phone || '',
            };
        }
        
        // Focus the first input after the modal is visible
        await nextTick();
        if (firstNameInput.value) {
            firstNameInput.value.focus();
        }
    }
});

const submitWorkerForm = async () => {
    isLoading.value = true;
    workerErrors.value = {};
    
    try {
        let response;
        
        if (props.mode === 'create') {
            response = await axios.post('/workers', workerForm.value);
            emit('worker-created', response.data.worker);
        } else if (props.mode === 'edit') {
            response = await axios.put(`/workers/${props.worker.id}`, workerForm.value);
            emit('worker-updated', response.data.worker);
        }
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            workerErrors.value = error.response.data.errors;
        } else {
            console.error('Error submitting worker form:', error);
        }
    } finally {
        isLoading.value = false;
    }
};

// Handle Enter key press to submit the form
const handleKeyDown = (e) => {
    if (e.key === 'Enter' && !isLoading.value) {
        e.preventDefault();
        submitWorkerForm();
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="md">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path v-if="mode === 'create'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ mode === 'create' ? 'Ajouter un nouveau travailleur' : 'Modifier le travailleur' }}
                    </h3>
                    <div class="mt-1">
                        <p class="text-sm text-gray-500">
                            {{ mode === 'create' 
                                ? 'Saisissez les détails du nouveau membre de l’équipe.' 
                                : `Mettre à jour les informations de ${worker?.first_name} ${worker?.last_name}.` }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 space-y-5" @keydown="handleKeyDown">
                <div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-2">
                    <div>
                        <InputLabel for="first_name" value="Prénom" required />
                        <TextInput
                            id="first_name"
                            ref="firstNameInput"
                            v-model="workerForm.first_name"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{'border-red-500 ring-red-500': workerErrors.first_name}"
                            placeholder="Entrez le prénom"
                        />
                        <p v-if="workerErrors.first_name" class="mt-1 text-sm text-red-600">
                            {{ workerErrors.first_name[0] }}
                        </p>
                    </div>
                    
                    <div>
                        <InputLabel for="last_name" value="Nom de famille" required />
                        <TextInput
                            id="last_name"
                            v-model="workerForm.last_name"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{'border-red-500 ring-red-500': workerErrors.last_name}"
                            placeholder="Entrez le nom de famille"
                        />
                        <p v-if="workerErrors.last_name" class="mt-1 text-sm text-red-600">
                            {{ workerErrors.last_name[0] }}
                        </p>
                    </div>
                </div>

                <div>
                    <InputLabel for="email" value="Adresse email" required />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <TextInput
                            id="email"
                            v-model="workerForm.email"
                            type="email"
                            class="pl-10 mt-1 block w-full"
                            :class="{'border-red-500 ring-red-500': workerErrors.email}"
                            placeholder="worker@example.com"
                        />
                    </div>
                    <p v-if="workerErrors.email" class="mt-1 text-sm text-red-600">
                        {{ workerErrors.email[0] }}
                    </p>
                </div>

                <div>
                    <InputLabel for="phone" value="Numéro de téléphone" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <TextInput
                            id="phone"
                            v-model="workerForm.phone"
                            type="tel"
                            class="pl-10 mt-1 block w-full"
                            :class="{'border-red-500 ring-red-500': workerErrors.phone}"
                            placeholder="Numéro de téléphone facultatif"
                        />
                    </div>
                    <p v-if="workerErrors.phone" class="mt-1 text-sm text-red-600">
                        {{ workerErrors.phone[0] }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <PrimaryButton
                @click="submitWorkerForm"
                :disabled="isLoading"
                class="w-full sm:w-auto sm:ml-3"
            >
                <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ mode === 'create' ? 'Ajouter un travailleur' : 'Mettre à jour le travailleur' }}
            </PrimaryButton>
            <SecondaryButton
                @click="$emit('close')"
                :disabled="isLoading"
                class="w-full sm:w-auto mt-3 sm:mt-0"
            >
            Annuler
            </SecondaryButton>
        </div>
    </Modal>
</template>