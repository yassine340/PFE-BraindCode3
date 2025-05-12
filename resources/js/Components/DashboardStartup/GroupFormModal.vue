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
    group: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'group-created', 'group-updated']);

const groupForm = ref({
    name: '',
    description: '',
});

const imagePreview = ref(null);
const imageFile = ref(null);
const groupErrors = ref({});
const isLoading = ref(false);
const isDragOver = ref(false);
const nameInput = ref(null);

// Reset form when modal is opened
watch(() => props.show, async (value) => {
    if (value) {
        groupErrors.value = {};
        imagePreview.value = null;
        imageFile.value = null;
        
        if (props.mode === 'create') {
            groupForm.value = {
                name: '',
                description: '',
            };
        } else if (props.mode === 'edit' && props.group) {
            groupForm.value = {
                name: props.group.name,
                description: props.group.description || '',
            };
        }
        
        // Focus the name input after the modal is visible
        await nextTick();
        if (nameInput.value) {
            nameInput.value.focus();
        }
    }
});

const handleImageSelection = (event) => {
    const file = event.target.files[0];
    if (file) {
        processImageFile(file);
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragOver.value = false;
    
    // Process the dropped file
    if (event.dataTransfer.items) {
        // Use DataTransferItemList interface
        if (event.dataTransfer.items[0].kind === 'file') {
            const file = event.dataTransfer.items[0].getAsFile();
            if (file.type.startsWith('image/')) {
                processImageFile(file);
            }
        }
    } else if (event.dataTransfer.files.length) {
        // Use DataTransfer interface
        const file = event.dataTransfer.files[0];
        if (file.type.startsWith('image/')) {
            processImageFile(file);
        }
    }
};

const processImageFile = (file) => {
    imageFile.value = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const removeImage = () => {
    imageFile.value = null;
    imagePreview.value = null;
    
    // Clear the file input so the same file can be selected again
    const fileInput = document.getElementById('image');
    if (fileInput) {
        fileInput.value = '';
    }
};

const submitGroupForm = async () => {
    isLoading.value = true;
    try {
        // Create form data to handle file upload
        const formData = new FormData();
        formData.append('name', groupForm.value.name);
        formData.append('description', groupForm.value.description || '');
        
        // Add image if selected
        if (imageFile.value) {
            formData.append('image', imageFile.value);
        }
        
        let response;
        
        if (props.mode === 'create') {
            response = await axios.post('/groups', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            emit('group-created', response.data.group);
        } else if (props.mode === 'edit') {
            // Add _method to the form data for Laravel method spoofing
            formData.append('_method', 'PUT');
            
            response = await axios.post(`/groups/${props.group.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            
            emit('group-updated', response.data.group);
        }
        
        groupErrors.value = {};
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            groupErrors.value = error.response.data.errors;
        } else {
            console.error('Error submitting group form:', error);
        }
    } finally {
        isLoading.value = false;
    }
};

// Handle Enter key press to submit the form
const handleKeyDown = (e) => {
    if (e.key === 'Enter' && !isLoading.value) {
        e.preventDefault();
        submitGroupForm();
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="md">
        <div class="bg-white rounded-lg overflow-hidden">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-25 rounded-full p-2">
                        <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="mode === 'create'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ mode === 'create' ? 'Créer un nouveau groupe' : 'Modifier le groupe' }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ mode === 'create' ? 'Ajoutez un nouveau groupe d équipe pour organiser vos travailleurs' : 'Mettre à jour les informations du groupe' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6" @keydown="handleKeyDown">
                <div class="space-y-6">
                    <!-- Group Name -->
                    <div>
                        <InputLabel for="name" value="Nom du groupe" required class="text-gray-700 font-medium" />
                        <TextInput
                            id="name"
                            ref="nameInput"
                            v-model="groupForm.name"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{'border-red-300 ring ring-red-200': groupErrors.name}"
                            placeholder="Entrez le nom du groupe"
                        />
                        <div v-if="groupErrors.name" class="text-red-500 text-sm mt-1.5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ groupErrors.name[0] }}
                        </div>
                        <p v-else class="mt-1.5 text-sm text-gray-500">Choisissez un nom descriptif pour votre groupe</p>
                    </div>

                    <!-- Group Description -->
                    <div>
                        <InputLabel for="description" value="Description (facultatif)" class="text-gray-700 font-medium" />
                        <textarea
                            id="description"
                            v-model="groupForm.description"
                            class="border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                            :class="{'border-red-300 ring ring-red-200': groupErrors.description}"
                            rows="3"
                            placeholder="Fournissez des détails sur l'objectif de ce groupe"
                        ></textarea>
                        <div v-if="groupErrors.description" class="text-red-500 text-sm mt-1.5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ groupErrors.description[0] }}
                        </div>
                    </div>
                    
                    <!-- Group Image -->
                    <div>
                        <InputLabel for="image" value="Image de groupe" class="text-gray-700 font-medium" />
                        
                        <!-- File upload box with drag-and-drop -->
                        <div 
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md transition-all duration-200"
                            :class="[
                                isDragOver ? 'border-primary-500 bg-primary-50' : 'border-gray-300 hover:border-gray-400',
                                {'border-red-300 bg-red-50': groupErrors.image}
                            ]"
                            @dragover.prevent="isDragOver = true"
                            @dragleave.prevent="isDragOver = false"
                            @drop.prevent="handleDrop"
                        >
                            <div class="space-y-1 text-center">
                                <svg
                                    class="mx-auto h-12 w-12 transition-all duration-200"
                                    :class="isDragOver ? 'text-primary-500' : 'text-gray-400'"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 48 48"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                        <span>Télécharger un fichier</span>
                                        <input 
                                            id="image" 
                                            type="file" 
                                            accept="image/*" 
                                            class="sr-only" 
                                            @change="handleImageSelection"
                                        />
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                        
                        <div v-if="groupErrors.image" class="text-red-500 text-sm mt-1.5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ groupErrors.image[0] }}
                        </div>
                        
                        <!-- Image preview section -->
                        <div class="mt-3" v-if="group && group.image_path && !imagePreview">
                            <div class="relative rounded-md overflow-hidden border border-gray-200 shadow-sm">
                                <img 
                                    :src="`/storage/${group.image_path}`" 
                                    alt="Current group image" 
                                    class="w-full h-40 object-cover transition-transform duration-300 hover:scale-105" 
                                />
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                    <button 
                                        type="button"
                                        class="bg-red-600 text-white text-sm py-1.5 px-3 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        @click="removeImage"
                                    >
                                    Remplacer l'image
                                    </button>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent text-white px-3 py-2">
                                    <p class="text-xs font-medium">Image actuelle</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3" v-else-if="imagePreview">
                            <div class="relative rounded-md overflow-hidden border border-gray-200 shadow-sm">
                                <img 
                                    :src="imagePreview" 
                                    alt="New image preview" 
                                    class="w-full h-40 object-cover transition-transform duration-300 hover:scale-105" 
                                />
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                    <button 
                                        type="button"
                                        class="bg-red-600 text-white text-sm py-1.5 px-3 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        @click="removeImage"
                                    >
                                    Supprimer l'image
                                    </button>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent text-white px-3 py-2">
                                    <p class="text-xs font-medium">Image sélectionnée</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
                    <SecondaryButton @click="$emit('close')" class="mr-3" :disabled="isLoading">
                        Annuler
                    </SecondaryButton>
                    <PrimaryButton 
                        @click="submitGroupForm" 
                        :disabled="isLoading" 
                        class="relative"
                    >
                        <span v-if="isLoading" class="absolute inset-0 flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span :class="{'opacity-0': isLoading}">
                            {{ mode === 'create' ? 'Create Group' : 'Groupe de mise à jour' }}
                        </span>
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
/* Custom loading spinner animation */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
.animate-spin {
  animation: spin 1s linear infinite;
}

/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>