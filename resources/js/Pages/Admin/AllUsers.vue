<script setup>
import { ref, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';

// Ajouter une référence pour le type de vue (tableau ou grille)
const viewType = ref('table'); // 'table' ou 'grid'

const props = defineProps({
    users: Object,
    filters: Object,
    userCounts: Object
});

const search = ref(props.filters.search);
const role = ref(props.filters.role);

// Mettre à jour les filtres avec debounce pour éviter trop de requêtes
const updateFilters = debounce(() => {
    router.get(route('admin.users'), {
        search: search.value,
        role: role.value,
        sort_field: props.filters.sort_field,
        sort_direction: props.filters.sort_direction
    }, {
        preserveState: true,
        replace: true
    });
}, 300);

// Observer les changements dans les filtres
watch([search, role], () => {
    updateFilters();
});

// Fonction pour trier les résultats
const sort = (field) => {
    const direction = field === props.filters.sort_field && props.filters.sort_direction === 'asc' ? 'desc' : 'asc';
    
    router.get(route('admin.users'), {
        search: search.value,
        role: role.value,
        sort_field: field,
        sort_direction: direction
    }, {
        preserveState: true,
        replace: true
    });
};

// Fonction pour gérer la suppression d'un utilisateur
const deleteUser = (userId) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        router.delete(route('admin.users.delete', userId), {
            onSuccess: () => {
                // Message de succès
            }
        });
    }
};

// Fonction pour obtenir la classe CSS de badge basée sur le rôle
const getRoleBadgeClass = (userRole) => {
    const classes = {
        'user': 'bg-blue-100 text-blue-800',
        'formateur': 'bg-purple-100 text-purple-800',
        'startup': 'bg-yellow-100 text-yellow-800',
        'admin': 'bg-red-100 text-red-800'
    };
    
    return classes[userRole] || 'bg-gray-100 text-gray-800';
};

// Fonction pour obtenir le label du rôle
const getRoleLabel = (userRole) => {
    const labels = {
        'user': 'Utilisateur',
        'formateur': 'Formateur',
        'startup': 'Startup',
        'admin': 'Admin'
    };
    
    return labels[userRole] || userRole;
};

// Fonction pour obtenir la classe du statut
const getStatusClass = (status) => {
    return status === 'valide' ? 'bg-green-100 text-green-800' :
           status === 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
           status === 'rejete' ? 'bg-red-100 text-red-800' :
           'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Tous les utilisateurs" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Section -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Total utilisateurs</div>
                            <div class="mt-1 text-3xl font-semibold text-gray-900">{{ userCounts.total }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Utilisateurs</div>
                            <div class="mt-1 text-3xl font-semibold text-blue-600">{{ userCounts.user }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Formateurs</div>
                            <div class="mt-1 text-3xl font-semibold text-purple-600">{{ userCounts.formateur }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Startups</div>
                            <div class="mt-1 text-3xl font-semibold text-yellow-600">{{ userCounts.startup }}</div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                                <!-- Search Input -->
                                <div class="relative">
                                    <input
                                        type="text"
                                        placeholder="Rechercher..."
                                        v-model="search"
                                        class="px-4 py-2 pr-8 border rounded-md w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Role Filter -->
                                <select
                                    v-model="role"
                                    class="px-4 py-2 border rounded-md w-full md:w-auto focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Tous les rôles</option>
                                    <option value="user">Utilisateurs</option>
                                    <option value="formateur">Formateurs</option>
                                    <option value="startup">Startups</option>
                                    <option value="admin">Administrateurs</option>
                                </select>
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Boutons de changement de vue -->
                                <div class="bg-gray-100 p-1 rounded-lg flex">
                                    <button 
                                        @click="viewType = 'table'" 
                                        :class="[
                                            'p-2 rounded transition-colors',
                                            viewType === 'table' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'
                                        ]"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="viewType = 'grid'" 
                                        :class="[
                                            'p-2 rounded transition-colors',
                                            viewType === 'grid' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'
                                        ]"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Add User Button -->
                                <Link :href="route('register')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                    Ajouter un utilisateur
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vue tableau (affichée conditionnellement) -->
                <div v-if="viewType === 'table'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th @click="sort('id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            ID
                                            <span v-if="props.filters.sort_field === 'id'">
                                                {{ props.filters.sort_direction === 'asc' ? '▲' : '▼' }}
                                            </span>
                                        </th>
                                        <th @click="sort('first_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Nom
                                            <span v-if="props.filters.sort_field === 'first_name'">
                                                {{ props.filters.sort_direction === 'asc' ? '▲' : '▼' }}
                                            </span>
                                        </th>
                                        <th @click="sort('email')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Email
                                            <span v-if="props.filters.sort_field === 'email'">
                                                {{ props.filters.sort_direction === 'asc' ? '▲' : '▼' }}
                                            </span>
                                        </th>
                                        <th @click="sort('role')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Rôle
                                            <span v-if="props.filters.sort_field === 'role'">
                                                {{ props.filters.sort_direction === 'asc' ? '▲' : '▼' }}
                                            </span>
                                        </th>
                                        <th @click="sort('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Statut
                                            <span v-if="props.filters.sort_field === 'status'">
                                                {{ props.filters.sort_direction === 'asc' ? '▲' : '▼' }}
                                            </span>
                                        </th>
                                        <th @click="sort('created_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Date d'inscription
                                            <span v-if="props.filters.sort_field === 'created_at'">
                                                {{ props.filters.sort_direction === 'asc' ? '▲' : '▼' }}
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ user.id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div v-if="user.profile_image" class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" :src="user.profile_image" alt="" />
                                                </div>
                                                <div v-else class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ user.first_name }} {{ user.last_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ user.email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span 
                                                :class="[
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full', 
                                                    getRoleBadgeClass(user.role)
                                                ]"
                                            >
                                                {{ getRoleLabel(user.role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span 
                                                :class="[
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full', 
                                                    user.status === 'valide' ? 'bg-green-100 text-green-800' :
                                                    user.status === 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
                                                    user.status === 'rejete' ? 'bg-red-100 text-red-800' :
                                                    'bg-gray-100 text-gray-800'
                                                ]"
                                            >
                                                {{ user.status || 'Actif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ new Date(user.created_at).toLocaleDateString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <!-- Commenté pour éviter les erreurs de route non définies -->
                                                <!-- 
                                                <Link :href="route('admin.users.show', user.id)" class="text-blue-600 hover:text-blue-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </Link>
                                                <Link :href="route('admin.users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </Link>
                                                <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                -->
                                                <span class="text-gray-400 italic text-xs">Actions à venir</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="users.data.length === 0">
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Aucun utilisateur trouvé
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Vue en grille (nouvelle) -->
                <div v-else class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <!-- Carte utilisateur -->
                        <div v-for="user in users.data" :key="user.id" 
                             class="bg-white border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="p-5 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                                <div class="flex justify-between items-start">
                                    <!-- Avatar et nom -->
                                    <div class="flex items-center">
                                        <div v-if="user.profile_image" class="h-12 w-12 rounded-full overflow-hidden">
                                            <img class="h-12 w-12 object-cover" :src="user.profile_image" alt="" />
                                        </div>
                                        <div v-else class="h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-semibold text-gray-600">
                                                {{ (user.first_name?.charAt(0) || '') + (user.last_name?.charAt(0) || '') }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-md font-semibold text-gray-900">
                                                {{ user.first_name }} {{ user.last_name }}
                                            </h3>
                                            <p class="text-sm text-gray-600">ID: {{ user.id }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Badge de rôle -->
                                    <span 
                                        :class="[
                                            'px-2 py-1 text-xs leading-5 font-semibold rounded-full', 
                                            getRoleBadgeClass(user.role)
                                        ]"
                                    >
                                        {{ getRoleLabel(user.role) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <!-- Informations utilisateur -->
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-gray-600 truncate">{{ user.email }}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-gray-600">{{ new Date(user.created_at).toLocaleDateString() }}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span 
                                            :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full', 
                                                getStatusClass(user.status)
                                            ]"
                                        >
                                            {{ user.status || 'Actif' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="bg-gray-50 px-4 py-3 border-t flex justify-end space-x-3">
                                <!-- Commenté pour éviter les erreurs de route non définies -->
                                <!--
                                <Link :href="route('admin.users.show', user.id)" class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </Link>
                                <Link :href="route('admin.users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 p-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </Link>
                                <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900 hover:bg-red-50 p-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                -->
                                <span class="text-gray-400 italic text-xs">Actions à venir</span>
                            </div>
                        </div>
                        
                        <!-- Message si aucun utilisateur -->
                        <div v-if="users.data.length === 0" class="col-span-full py-8 text-center text-gray-500">
                            Aucun utilisateur trouvé
                        </div>
                    </div>
                </div>
                
                <!-- Pagination simplifiée -->
                <div v-if="users.links && users.links.length > 3" class="mt-6 bg-white p-4 shadow rounded-lg">
                    <div class="flex flex-wrap justify-center">
                        <div v-for="(link, key) in users.links" :key="key" class="mx-1 mb-1">
                            <div v-if="link.url === null" 
                                class="px-4 py-2 text-sm text-gray-400 border rounded"
                                v-html="link.label">
                            </div>
                            
                            <Link v-else
                                :href="link.url" 
                                class="px-4 py-2 text-sm border rounded hover:bg-blue-50 focus:outline-none cursor-pointer" 
                                :class="{ 'bg-blue-500 text-white hover:bg-blue-600': link.active }"
                                v-html="link.label">
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Si pas de pagination, afficher une pagination simple -->
                <div v-else-if="users.prev_page_url || users.next_page_url" class="mt-6 bg-white p-4 shadow rounded-lg">
                    <div class="flex justify-between">
                        <button 
                            v-if="users.prev_page_url" 
                            @click="() => router.visit(users.prev_page_url)" 
                            class="px-4 py-2 border rounded bg-gray-100 hover:bg-gray-200 focus:outline-none"
                        >
                            Précédent
                        </button>
                        <div v-else></div>
                        
                        <span class="px-4 py-2">
                            Page {{ users.current_page }} sur {{ users.last_page }}
                        </span>
                        
                        <button 
                            v-if="users.next_page_url" 
                            @click="() => router.visit(users.next_page_url)" 
                            class="px-4 py-2 border rounded bg-gray-100 hover:bg-gray-200 focus:outline-none"
                        >
                            Suivant
                        </button>
                        <div v-else></div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>