<script setup>
defineProps({
    workers: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['edit-worker', 'delete-worker']);
</script>

<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nom et Prénom
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Téléphone
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actes
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                    v-for="(worker, index) in workers" 
                    :key="worker.id"
                    class="hover:bg-gray-50 transition-colors duration-150 ease-in-out"
                    :class="{'bg-gray-50': index % 2 === 1}"
                >
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold">
                                    {{ worker.first_name.charAt(0) }}{{ worker.last_name.charAt(0) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ worker.first_name }} {{ worker.last_name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ worker.id && `ID: ${worker.id.toString().padStart(4, '0')}` }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ worker.email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div v-if="worker.phone" class="text-sm text-gray-900">{{ worker.phone }}</div>
                        <div v-else class="text-sm text-gray-400">Not provided</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <button 
                                @click="$emit('edit-worker', worker)" 
                                class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150 ease-in-out p-1 rounded-full hover:bg-indigo-50"
                                title="Edit worker"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button 
                                @click="$emit('delete-worker', worker)" 
                                class="text-red-600 hover:text-red-900 transition-colors duration-150 ease-in-out p-1 rounded-full hover:bg-red-50"
                                title="Delete worker"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>