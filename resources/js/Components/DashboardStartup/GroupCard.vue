<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    group: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['edit', 'delete', 'add-worker', 'remove-worker']);

const isExpanded = ref(false);
const isRemoving = ref(false);

// Compute the default image or actual image URL
const groupImageUrl = computed(() => {
    if (props.group.image_path) {
        return `/storage/${props.group.image_path}`;
    }
    return null;
});

// Set background color based on group name for default image
const groupInitialBg = computed(() => {
    const colors = [
        'from-blue-400 to-blue-600',
        'from-green-400 to-green-600',
        'from-purple-400 to-purple-600',
        'from-red-400 to-red-600',
        'from-yellow-400 to-yellow-600',
        'from-pink-400 to-pink-600',
        'from-indigo-400 to-indigo-600',
    ];
    
    // Use character code of first letter to select a color
    const index = props.group.name.charCodeAt(0) % colors.length;
    return colors[index];
});

const removeWorker = async (worker) => {
    if (isRemoving.value) return;
    
    isRemoving.value = true;
    
    try {
        const response = await axios.post('/groups/remove-worker', {
            group_id: props.group.id,
            worker_id: worker.id
        });
        
        const updatedWorkers = Array.isArray(response.data.group.workers) 
            ? response.data.group.workers 
            : [];
            
        emit('remove-worker', props.group.id, updatedWorkers);
    } catch (error) {
        console.error('Error removing worker from group:', error);
    } finally {
        isRemoving.value = false;
    }
};

const workerCount = computed(() => {
    return Array.isArray(props.group.workers) ? props.group.workers.length : 0;
});
</script>

<template>
    <div 
        class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300"
    >
        <!-- Group header with image -->
        <div class="aspect-w-16 aspect-h-9 bg-gray-100 relative">
            <div v-if="groupImageUrl" class="w-full h-full overflow-hidden">
                <img :src="groupImageUrl" alt="Group cover" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
            </div>
            <div v-else class="w-full h-full bg-gradient-to-br flex items-center justify-center text-white font-bold text-3xl" :class="groupInitialBg">
                {{ group.name.charAt(0) }}
            </div>
            
            <!-- Actions overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/50 flex items-end">
                <div class="p-4 w-full">
                    <h3 class="text-xl font-semibold text-white mb-1 truncate">{{ group.name }}</h3>
                    <div class="flex items-center text-white/80 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>{{ workerCount }} {{ workerCount === 1 ? 'Worker' : 'Workers' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Group details -->
        <div class="p-5">
            <div v-if="group.description" class="text-gray-500 text-sm mb-4 line-clamp-2">
                {{ group.description }}
            </div>
            
            <div class="flex flex-wrap justify-between gap-2 mb-4">
                <button 
                    @click="$emit('edit', group)" 
                    class="flex-1 inline-flex justify-center items-center py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </button>
                <button 
                    @click="$emit('delete', group)" 
                    class="flex-1 inline-flex justify-center items-center py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Supprimer
                </button>
            </div>
            
            <!-- Workers section -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <button 
                        @click="isExpanded = !isExpanded" 
                        class="text-sm font-medium text-gray-700 flex items-center focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 transition-transform" :class="{'rotate-90': isExpanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        {{ isExpanded ? 'Hide' : 'Show' }} Workers
                    </button>
                    <button 
                        @click="$emit('add-worker', group)" 
                        class="text-sm text-primary-600 hover:text-primary-800 flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un travailleur
                    </button>
                </div>
                
                <transition name="expand">
                    <div v-if="isExpanded" class="border rounded-md overflow-hidden">
                        <ul v-if="Array.isArray(group.workers) && group.workers.length > 0" class="max-h-60 overflow-y-auto">
                            <li v-for="worker in group.workers" :key="worker.id" class="flex justify-between items-center py-2 px-3 border-b last:border-b-0 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="h-7 w-7 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-xs mr-2">
                                        {{ worker.first_name.charAt(0) }}{{ worker.last_name.charAt(0) }}
                                    </div>
                                    <div class="text-sm">
                                        {{ worker.first_name }} {{ worker.last_name }}
                                    </div>
                                </div>
                                <button 
                                    @click="removeWorker(worker)"
                                    :disabled="isRemoving"
                                    class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </li>
                        </ul>
                        <div v-else class="py-3 px-3 text-center text-gray-500 text-sm">
                            Aucun travailleur dans ce groupe.
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Aspect ratio for the image section */
.aspect-w-16 {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
}
.aspect-w-16 > * {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}

/* Expansion animation */
.expand-enter-active, .expand-leave-active {
    transition: all 0.3s;
    max-height: 500px;
    overflow: hidden;
}
.expand-enter-from, .expand-leave-to {
    max-height: 0;
    opacity: 0;
}

/* Truncate long text */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>