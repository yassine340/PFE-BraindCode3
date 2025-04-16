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
                                            <!-- Affichage normal pour toutes les colonnes sauf cv_file -->
                                            <div 
                                                v-if="column.key !== 'cv_file'"
                                                :class="{
                                                    'text-sm': true,
                                                    'text-gray-900': true,
                                                    'text-blue-600 group-hover:underline': column.key === 'email'
                                                }"
                                            >
                                                {{ formateur[column.key] || 'N/A' }}
                                            </div>
                                            
                                            <!-- Affichage spécial pour le CV -->
                                            <div v-else class="text-sm">
                                                <a 
                                                    v-if="formateur.cv_file" 
                                                    href="#"
                                                    @click.prevent="openCvPreview(formateur.cv_file)"
                                                    class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Voir CV
                                                </a>
                                                <span v-else class="text-gray-400">Non fourni</span>
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

        <!-- Modal de prévisualisation du CV -->
        <div v-if="showCvModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showCvModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                CV du formateur
                            </h3>
                            <button @click="showCvModal = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-4 h-[70vh]">
                            <iframe :src="selectedCvUrl" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <a :href="selectedCvUrl" target="_blank" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ouvrir dans un nouvel onglet
                        </a>
                        <a :href="selectedCvUrl" download class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Télécharger
                        </a>
                        <button @click="showCvModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    props: {
        formateurs: Array,
    },
    data() {
        return {
            searchQuery: '',
            sortColumn: null,
            sortDirection: 'asc',
            showCvModal: false,
            selectedCvUrl: null,
            columns: [
                { key: 'id', label: 'ID' },
                { key: 'first_name', label: 'Prénom' },
                { key: 'last_name', label: 'Nom' },
                { key: 'email', label: 'Email' },
                { key: 'phone', label: 'Téléphone' },
                { key: 'speciality', label: 'Spécialité' },
                { key: 'cv_file', label: 'CV' },
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
        },
        // Méthode pour ouvrir la prévisualisation du CV
        openCvPreview(cvUrl) {
            this.selectedCvUrl = cvUrl;
            this.showCvModal = true;
        }
    }
};
</script>

<style scoped>
/* Styles pour l'animation du modal */
.fixed {
    transition: opacity 0.3s ease-out;
}

/* Styles pour les boutons du modal */
.bg-blue-600:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.bg-green-600:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Style pour le bouton CV */
.bg-blue-100.text-blue-700:hover {
    background-color: #dbeafe;
    transform: translateY(-1px);
}
</style>