<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';

const user = usePage().props.auth.user;

// Data management
const payments = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortField = ref('created_at');
const sortDirection = ref('desc');
const filterStatus = ref('all');
const filterPaymentMethod = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const showDetailsModal = ref(false);
const selectedPayment = ref(null);

// Fetch payment data from the API
const fetchPayments = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/payments');
        payments.value = response.data;
    } catch (error) {
        console.error('Error fetching payments:', error);
    } finally {
        loading.value = false;
    }
};

// Reset pagination when filters change
watch([searchQuery, filterStatus, filterPaymentMethod], () => {
    currentPage.value = 1;
});

// Sort payments
const sortPayments = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
};

// Filter and sort payments
const filteredPayments = computed(() => {
    let result = [...payments.value];
    
    // Filter by search query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(payment => 
            payment.user?.first_name?.toLowerCase().includes(query) ||
            payment.user?.last_name?.toLowerCase().includes(query) ||
            payment.user?.email?.toLowerCase().includes(query) ||
            payment.formation?.titre?.toLowerCase().includes(query) 
        );
    }
    
    // Filter by status
    if (filterStatus.value !== 'all') {
        result = result.filter(payment => payment.status === filterStatus.value);
    }
    
    // Filter by payment method
    if (filterPaymentMethod.value !== 'all') {
        result = result.filter(payment => payment.payment_method === filterPaymentMethod.value);
    }
    
    // Sort
    result.sort((a, b) => {
        let valA = a[sortField.value];
        let valB = b[sortField.value];
        
        // Handle nested fields
        if (sortField.value === 'user') valA = a.user?.first_name +" "+a.user?.last_name || '';
        if (sortField.value === 'user') valB = b.user?.first_name +" "+b.user?.last_name || '';
        if (sortField.value === 'formation') valA = a.formation?.titre || '';
        if (sortField.value === 'formation') valB = b.formation?.titre || '';
        
        if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1;
        if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1;
        return 0;
    });
    
    return result;
});

// Paginated payments
const paginatedPayments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredPayments.value.slice(start, end);
});

// Total pages for pagination
const totalPages = computed(() => {
    return Math.ceil(filteredPayments.value.length / itemsPerPage.value);
});

// Format date
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
};

// Format currency
const formatCurrency = (amount, currency = 'EUR') => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: currency.toUpperCase()
    }).format(amount);
};

// View payment details
const viewPaymentDetails = (payment) => {
    selectedPayment.value = payment;
    showDetailsModal.value = true;
};

// Close details modal
const closeDetailsModal = () => {
    showDetailsModal.value = false;
    selectedPayment.value = null;
};

// Calculate statistics
const statistics = computed(() => {
    const completed = payments.value.filter(p => p.status === 'completed');
    const pending = payments.value.filter(p => p.status === 'pending');
    const failed = payments.value.filter(p => p.status === 'failed');
    
    const totalRevenue = completed.reduce((sum, p) => sum + parseFloat(p.amount), 0);
    const avgTransactionValue = completed.length > 0 ? totalRevenue / completed.length : 0;
    
    return {
        totalRevenue,
        completedCount: completed.length,
        pendingCount: pending.length,
        failedCount: failed.length,
        avgTransactionValue
    };
});

// Load payments data when component mounts
onMounted(() => {
    fetchPayments();
});
</script>

<template>
    <Head title="Payment Transactions" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Transactions de paiement
                </h2>
                <button 
                    @click="fetchPayments" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center gap-2 text-sm"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Actualiser les données
                </button>
            </div>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats cards -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenu total</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ formatCurrency(statistics.totalRevenue) }}</p>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Complété</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ statistics.completedCount }}</p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En attente</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ statistics.pendingCount }}</p>
                            </div>
                            <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Échoué</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ statistics.failedCount }}</p>
                            </div>
                            <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <!-- Search and filters -->
                        <div class="mb-6 space-y-4">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        v-model="searchQuery"
                                        placeholder="Recherche par utilisateur, email, cours..."
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                    />
                                </div>
                                
                                <div class="flex flex-col md:flex-row gap-3">
                                    <select 
                                        v-model="filterStatus" 
                                        class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                    >
                                        <option value="all">Tous les statuts</option>
                                        <option value="completed">complété</option>
                                        <option value="pending">Pending</option>
                                        <option value="failed">Échoué</option>
                                    </select>
                                    
                                    <select 
                                        v-model="filterPaymentMethod" 
                                        class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                    >
                                        <option value="all">Toutes les méthodes</option>
                                        <option value="stripe">Stripe</option>
                                        <option value="paypal">PayPal</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                                <div>
                                    Affichage <span class="font-medium text-gray-900 dark:text-white">{{ paginatedPayments.length }}</span> de <span class="font-medium text-gray-900 dark:text-white">{{ filteredPayments.length }}</span> payments
                                </div>
                                <div>
                                    <select 
                                        v-model="itemsPerPage" 
                                        class="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                    >
                                        <option value="5">5 par page</option>
                                        <option value="10">10 par page</option>
                                        <option value="20">20 par page</option>
                                        <option value="50">50 par page</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payments table -->
                        <div class="overflow-x-auto -mx-4 sm:-mx-6">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700">
                                                <th @click="sortPayments('id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center">
                                                        ID
                                                        <span v-if="sortField === 'id'" class="ml-1">
                                                            <svg v-if="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th @click="sortPayments('user')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center">
                                                        Utilisateur
                                                        <span v-if="sortField === 'user'" class="ml-1">
                                                            <svg v-if="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th @click="sortPayments('formation')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center">
                                                        Cours
                                                        <span v-if="sortField === 'formation'" class="ml-1">
                                                            <svg v-if="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th @click="sortPayments('amount')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center">
                                                        Montant
                                                        <span v-if="sortField === 'amount'" class="ml-1">
                                                            <svg v-if="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th @click="sortPayments('payment_method')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center">
                                                        Méthode
                                                        <span v-if="sortField === 'payment_method'" class="ml-1">
                                                            <svg v-if="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th @click="sortPayments('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center">
                                                        Statut
                                                        <span v-if="sortField === 'status'" class="ml-1">
                                                            <svg v-if="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th @click="sortPayments('created_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center">
                                                        Date
                                                        <span v-if="sortField === 'created_at'" class="ml-1">
                                                            <svg v-if="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Actes
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                            <tr v-if="loading" class="text-center">
                                                <td colspan="8" class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                                    <div class="flex justify-center items-center">
                                                        <svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Chargement des paiements...
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-else-if="filteredPayments.length === 0" class="text-center">
                                                <td colspan="8" class="px-6 py-10 text-gray-500 dark:text-gray-400">
                                                    <div class="flex flex-col items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <p class="text-lg font-medium">Aucun paiement trouvé</p>
                                                        <p class="text-sm mt-1">Essayez de modifier vos critères de recherche ou de filtrage</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-for="payment in paginatedPayments" :key="payment.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    #{{ payment.id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                                                {{ payment.user?.first_name?.charAt(0).toUpperCase() || 'U' }}
                                                            </span>
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ payment.user?.first_name +" "+payment.user?.last_name || 'N/A' }}</div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ payment.user?.email || 'N/A' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    <div class="max-w-xs truncate">{{ payment.formation?.titre || 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ formatCurrency(payment.amount, payment.currency) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span 
                                                        :class="{
                                                            'px-2.5 py-1.5 rounded-full text-xs font-medium flex items-center justify-center w-20': true,
                                                            'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-300': payment.payment_method === 'stripe',
                                                            'bg-yellow-50 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300': payment.payment_method === 'paypal'
                                                        }"
                                                    >
                                                        <span v-if="payment.payment_method === 'stripe'" class="mr-1.5">
                                                            <svg class="h-3 w-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M14.1 7.1c-2.3-1-4.6-.7-6.3.1-.9.4-1.6 1-2 1.6-.5.8-.6 1.5-.4 2.3.4 1.4 1.8 2 3.3 2.2 1.8.2 3.8-.4 3.8-.4l.3-.1c.3-.1.6-.3.9-.4.6-.3 1.4-.2 1.9.2.6.5.7 1.4.3 2.1-.5.8-1.7 1.3-3.1 1.3-1.1 0-2.4-.3-3.3-.8-.7-.4-1.2-.3-1.6.2-.3.4-.3.9.1 1.2 1.1.9 2.5 1.4 3.7 1.5 2.8.3 5.5-.8 6.8-2.8.7-1.2.9-2.4.6-3.6-.4-1.3-1.5-2.2-2.8-2.7-.6-.3-1.1-.4-1.2-.6-.2-.4.3-.7.5-.8 1.1-.5 2.4-.3 3.4.5.4.3.9.3 1.2-.1.4-.4.3-.9-.1-1.2-1.3-1-2.8-1.4-4.1-1.4-1.5 0-3.1.3-4.5 1-1.7.8-2.5 2.5-1.9 4 .4 1.1 1.3 1.8 2.2 2.2.3.1.6.2.9.4-1.4.3-2.8-.2-3.3-1.2-.5-1.2-.1-2.7 1.1-3.8 1.8-1.5 4.3-2 6.8-1.3 2.1.6 3.8 2 4.3 4.1.5 2.2-.6 4.5-2.7 5.6-2.1 1-4.3 1-6.5.2-.7-.3-1.3-.6-1.9-1.1-.9-.7-.9-1.4-.5-2 .3-.6 1.2-.9 1.9-.5.6.3 1.2.5 1.9.6 2.4.1 4.2-1.4 4.3-3.3.1-1.8-1.4-3.3-3.5-3.5-2.3-.2-4.3 1.2-4.7 3.4-.1.4.2.9.6.9h.1c.4 0 .8-.3.9-.7.3-1.5 1.7-2.4 3.4-2.2 1.5.2 2.3 1.1 2.3 2.2-.1 1.3-1.3 2.2-2.9 2.2-.4 0-.9-.1-1.3-.2-.8-.3-1.5-.9-2.1-1.7-.6-.7-.9-1.6-.7-2.5.2-1 .8-1.8 1.5-2.4 2.1-1.6 5.2-1.6 7.3-.1 1.2.9 2 2.2 2.1 3.9.1 1.2-.3 2.4-1.1 3.3-.7.8-1.6 1.4-2.6 1.8-1.7.6-3.5.6-5.2-.1-1.5-.6-2.7-1.6-3.5-3-.7-1.2-.9-2.5-.7-3.9.2-1.3.9-2.4 1.8-3.2.7-.7 1.6-1.2 2.6-1.5 2.4-.8 4.9-.5 7.1.8.9.6 1.7 1.3 2.2 2.2.6 1 .9 2.1.9 3.3-.1 1.2-.5 2.2-1.2 3.2-1.4 2-3.8 3.1-6.3 3-2.4-.1-4.6-1.4-5.8-3.5-1-1.8-1.1-3.9-.3-5.8.5-1.2 1.3-2.2 2.4-3 1.5-1.1 3.2-1.6 5-1.7h.3c2.4 0 4.7.9 6.3 2.7 1.1 1.2 1.8 2.8 1.9 4.5.1 1.4-.3 2.8-1.1 4-.6 1-1.5 1.7-2.5 2.1-1.4.6-3 .7-4.5.2s-2.8-1.5-3.4-2.9c-.6-1.3-.7-2.7-.3-4.1.3-1.2 1.1-2.2 2.1-2.9.5-.3.7-.8.5-1.3-.1-.5-.7-.7-1.1-.4-1.3 1-2.2 2.3-2.6 3.9-.5 1.7-.4 3.5.3 5.1.8 1.7 2.3 3 4.2 3.5 1.9.5 3.9.3 5.6-.5 1.3-.6 2.4-1.5 3.1-2.7 1-1.5 1.4-3.3 1.3-5.1-.1-2-1-3.9-2.4-5.3-2-2.1-4.8-3.3-7.7-3.3h-.3c-2.1.1-4.2.7-6 1.9-1.4 1-2.4 2.3-3.1 3.7-1 2.3-.9 4.9.4 7.1 1.5 2.6 4.2 4.1 7.1 4.2 3.1.1 6-1.3 7.7-3.7.9-1.2 1.4-2.6 1.5-4.1 0-1.5-.3-3-1.1-4.3-.6-1.1-1.6-2-2.7-2.8-2.8-1.4-5.9-1.8-8.9-.8-1.2.4-2.3 1-3.2 1.8-1.2 1-2.1 2.4-2.4 4-.3 1.7.1 3.3.9 4.8 1 1.8 2.5 3.1 4.4 3.8 2.1.8 4.4.7 6.5 0 1.3-.5 2.5-1.2 3.4-2.2 1.1-1.2 1.7-2.8 1.6-4.4-.1-2.1-1.1-3.9-2.6-5.2-1-1-2.3-1.7-4.1-2z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                        <span v-else-if="payment.payment_method === 'paypal'" class="mr-1.5">
                                                            <svg class="h-3 w-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M20.1 6.8c.1-.5.1-1 0-1.5-.4-1.4-1.9-1.9-3.8-1.9h-5C11 3.4 10.8 3.3 10.7 3.1L10.2 2C9.9 1.5 9.4 1.3 8.9 1.3H5.3c-.5 0-.9.2-1 .6l-3 13c-.1.5.3.9.8.9h3.3l.7-3.4v.2c.1-.4.5-.7 1-.7h1.5c3.2 0 5.7-1.3 6.4-5.1.1-.3.1-.5.1-.8.2 0 .1-.1 0-.2 0 0 .3.1.3.1.4.1.7.3 1 .5.3.2.5.5.7.8z" fill="currentColor"/>
                                                                <path d="M9.3 7.1c0-.2.1-.3.2-.4.1-.1.2-.1.3-.1h4.3c.5 0 .9 0 1.3.1.1 0 .2 0 .3.1.1 0 .2.1.2.1.1 0 .1 0 .2.1.1 0 .1.1.1.1.2.1.3.2.4.3v.4c-.1.5-.3 1-.5 1.5-.4.9-1.2 1.4-2.4 1.4H11l-.3 1.9v.2s-.3 1.7-.3 1.7c0 .1-.1.2-.3.2H8c-.2 0-.3-.1-.2-.3l1.5-6.3z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                        {{ payment.payment_method }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span 
                                                        :class="{
                                                            'px-2.5 py-1.5 rounded-full text-xs font-medium flex items-center w-24 justify-center': true,
                                                            'bg-green-50 text-green-700 dark:bg-green-900 dark:text-green-300': payment.status === 'completed',
                                                            'bg-yellow-50 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300': payment.status === 'pending',
                                                            'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-300': payment.status === 'failed'
                                                        }"
                                                    >
                                                        <span v-if="payment.status === 'completed'" class="mr-1.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                        <span v-else-if="payment.status === 'pending'" class="mr-1.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                        <span v-else-if="payment.status === 'failed'" class="mr-1.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                        {{ payment.status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ formatDate(payment.created_at) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button 
                                                        @click="viewPaymentDetails(payment)" 
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors flex items-center gap-1"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Details
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4 flex items-center justify-between">
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        Showing
                                        <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                                        to
                                        <span class="font-medium">{{ 
                                            Math.min(currentPage * itemsPerPage, filteredPayments.length)
                                        }}</span>
                                        of
                                        <span class="font-medium">{{ filteredPayments.length }}</span>
                                        results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <button
                                            @click="currentPage = Math.max(1, currentPage - 1)"
                                            :disabled="currentPage === 1"
                                            :class="[
                                                'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium',
                                                currentPage === 1 ? 'text-gray-300 dark:text-gray-600 cursor-not-allowed' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
                                            ]"
                                        >
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        
                                        <template v-for="page in totalPages" :key="page">
                                            <button
                                                v-if="page === currentPage || 
                                                      page === 1 || 
                                                      page === totalPages || 
                                                      (page >= currentPage - 1 && page <= currentPage + 1)"
                                                @click="currentPage = page"
                                                :class="[
                                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                                    currentPage === page ? 'z-10 bg-indigo-50 dark:bg-indigo-900 border-indigo-500 dark:border-indigo-600 text-indigo-600 dark:text-indigo-300' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
                                                ]"
                                            >
                                                {{ page }}
                                            </button>
                                            <span
                                                v-else-if="page === currentPage - 2 || page === currentPage + 2"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300"
                                            >
                                                ...
                                            </span>
                                        </template>
                                        
                                        <button
                                            @click="currentPage = Math.min(totalPages, currentPage + 1)"
                                            :disabled="currentPage === totalPages || totalPages === 0"
                                            :class="[
                                                'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium',
                                                currentPage === totalPages || totalPages === 0 ? 'text-gray-300 dark:text-gray-600 cursor-not-allowed' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
                                            ]"
                                        >
                                            <span class="sr-only">Suivant</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Details Modal -->
        <div v-if="showDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Détails de paiement</h3>
                    <button @click="closeDetailsModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div v-if="selectedPayment" class="p-6 space-y-6">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">ID de paiement</p>
                            <p class="text-base text-gray-900 dark:text-white font-medium">#{{ selectedPayment.id }}</p>
                        </div>
                        <div>
                            <span 
                                :class="{
                                    'px-3 py-1.5 rounded-full text-sm font-medium': true,
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedPayment.status === 'completed',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedPayment.status === 'pending',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': selectedPayment.status === 'failed'
                                }"
                            >
                                {{ selectedPayment.status }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Informations client</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">nom et pernom</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.user?.first_name +" "+ selectedPayment.user?.last_name || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.user?.email || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Téléphone</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.user?.phone || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Adresse</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.adresse || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Ville</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.ville || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Code postal</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.code_postal || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pays</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.Pays || 'N/A' }}</p>
                                </div> 
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Informations de paiement</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Montant</p>
                                    <p class="text-base text-gray-900 dark:text-white font-medium">{{ formatCurrency(selectedPayment.amount, selectedPayment.currency) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Méthode</p>
                                    <p class="text-base text-gray-900 dark:text-white capitalize">{{ selectedPayment.payment_method }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ formatDate(selectedPayment.created_at) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Mode de paiement</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.payment_method || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Statut de paiement</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ selectedPayment.status || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Date de paiement</p>
                                    <p class="text-base text-gray-900 dark:text-white">{{ formatDate(selectedPayment.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button @click="closeDetailsModal" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 dark:bg-indigo-900 dark:hover:bg-indigo-800 transition-colors">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
