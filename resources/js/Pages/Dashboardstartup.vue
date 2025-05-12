<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WorkersManagement from '@/Components/DashboardStartup/WorkersManagement.vue';
import GroupsManagement from '@/Components/DashboardStartup/GroupsManagement.vue';

// Toggle active tab
const activeTab = ref('workers');
</script>

<template>
    <Head title="Startup Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Startup Dashboard
                </h2>
                <div class="text-sm text-gray-500">
                    Gérez efficacement votre équipe et vos projets
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <!-- Dashboard Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px px-4">
                            <button
                                @click="activeTab = 'workers'"
                                :class="[
                                    'px-6 py-4 font-medium text-sm leading-5 whitespace-nowrap transition-all duration-200 ease-in-out',
                                    activeTab === 'workers' 
                                        ? 'border-b-2 border-primary-500 text-primary-600 font-bold'
                                        : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'
                                ]"
                            >
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>travailleurs</span>
                                </div>
                            </button>
                            <button
                                @click="activeTab = 'groups'"
                                :class="[
                                    'px-6 py-4 font-medium text-sm leading-5 whitespace-nowrap transition-all duration-200 ease-in-out',
                                    activeTab === 'groups' 
                                        ? 'border-b-2 border-primary-500 text-primary-600 font-bold'
                                        : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'
                                ]"
                            >
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>groupe</span>
                                </div>
                            </button>
                        </nav>
                    </div>
                    <div class="p-6">
                        <transition name="fade" mode="out-in">
                            <!-- Workers Tab -->
                            <WorkersManagement v-if="activeTab === 'workers'" :key="'workers'" />
                            <!-- Groups Tab -->
                            <GroupsManagement v-else-if="activeTab === 'groups'" :key="'groups'" />
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>