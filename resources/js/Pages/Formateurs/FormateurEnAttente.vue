<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
</script>

<template>
    <Head title="Liste des formateurs en attente" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-indigo-700 p-4 rounded-lg shadow-md">
                <h2 class="flex items-center text-3xl font-extrabold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Formateurs en Attente
                </h2>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input 
                            v-model="searchQuery" 
                            placeholder="Rechercher un formateur..." 
                            class="pl-10 pr-4 py-2 w-72 bg-white/20 text-white placeholder-white/70 border-2 border-white/30 rounded-full focus:ring-2 focus:ring-white transition-all"
                        />
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 h-5 w-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button 
                        @click="toggleFilter"
                        class="p-2 bg-white/20 text-white rounded-full hover:bg-white/30 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="container mx-auto px-4 max-w-6xl"> 
                <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-blue-50 to-blue-100">
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th 
                                            v-for="column in columns" 
                                            :key="column.key" 
                                            @click="sortBy(column.key)"
                                            class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-200 transition-colors"
                                        >
                                            {{ column.label }}
                                            <span v-if="sortColumn === column.key" class="ml-2">
                                                {{ sortDirection === 'asc' ? '▲' : '▼' }}
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr 
                                        v-for="formateur in filteredAndSortedFormateurs" 
                                        :key="formateur.id" 
                                        class="hover:bg-blue-50 transition-colors group"
                                    >
                                        <td 
                                            v-for="column in columns" 
                                            :key="column.key" 
                                            class="px-6 py-4 whitespace-nowrap"
                                        >
                                            <div 
                                                :class="{
                                                    'text-sm': true,
                                                    'text-gray-900': true,
                                                    'text-blue-600 group-hover:underline': column.key === 'email'
                                                }"
                                            >
                                                {{ formateur[column.key] || 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex space-x-2">
                                                <button 
                                                    @click="validerFormateur(formateur.id)" 
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors group"
                                                    title="Valider le formateur"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                                <button 
                                                    @click="rejetFormateur(formateur.id)" 
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors group"
                                                    title="Rejeter le formateur"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredAndSortedFormateurs.length === 0">
                                        <td colspan="8" class="text-center py-8 text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-xl">Aucun formateur en attente</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
// The script remains exactly the same as in the previous version
export default {
    props: {
        formateurs: Array,
    },
    data() {
        return {
            searchQuery: '',
            sortColumn: null,
            sortDirection: 'asc',
            columns: [
                { key: 'id', label: 'ID' },
                { key: 'first_name', label: 'Prénom' },
                { key: 'last_name', label: 'Nom' },
                { key: 'email', label: 'Email' },
                { key: 'phone', label: 'Téléphone' },
                { key: 'speciality', label: 'Spécialité' },
                { key: 'description', label: 'Description' }
            ]
        }
    },
    computed: {
        filteredAndSortedFormateurs() {
            let result = this.formateurs;

            // Search filter
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                result = result.filter(f => 
                    Object.values(f).some(val => 
                        String(val).toLowerCase().includes(query)
                    )
                );
            }

            // Sorting
            if (this.sortColumn) {
                result.sort((a, b) => {
                    let modifier = this.sortDirection === 'asc' ? 1 : -1;
                    return a[this.sortColumn] > b[this.sortColumn] ? modifier : -modifier;
                });
            }

            return result;
        }
    },
    methods: {
        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
        },
        toggleFilter() {
            // Placeholder for future filter functionality
            alert('Filter functionality coming soon!');
        },
        async validerFormateur(id) {
            try {
                const response = await axios.post(`/formateurs/${id}/valider`);
                
                // Show success alert
                alert('Formateur validé avec succès !');
                
                // Refresh the page
                this.$inertia.reload();
            } catch (error) {
                console.error('Erreur lors de la validation du formateur:', error);
                
                // Show error alert
                alert('Erreur lors de la validation du formateur');
            }
        },
        async rejetFormateur(id) {
            try {
                const response = await axios.post(`/formateurs/${id}/rejeter`);
                
                // Show success alert
                alert('Formateur rejeté avec succès !');
                
                // Refresh the page
                this.$inertia.reload();
            } catch (error) {
                console.error('Erreur lors du rejet du formateur:', error);
                
                // Show error alert
                alert('Erreur lors du rejet du formateur');
            }
        }
    }
};
</script>