<template>
  <div class="paid-formations-container">
    <div class="header-section mb-8">
      <h2 class="text-3xl font-bold text-gray-800">My Paid Formations</h2>
      <p class="text-gray-600 mt-2">Access your purchased educational content</p>
    </div>
    
    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center items-center py-16">
      <div class="spinner border-t-4 border-blue-500 border-solid rounded-full w-10 h-10 animate-spin"></div>
      <span class="ml-4 text-lg text-gray-600">Loading your formations...</span>
    </div>
    
    <!-- Error state -->
    <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-lg shadow-sm mb-8" role="alert">
      <div class="flex items-center">
        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        <div>
          <p class="font-bold">Unable to load formations</p>
          <p class="text-sm">{{ error }}</p>
        </div>
      </div>
      <button @click="fetchPaidFormations" class="mt-4 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Try Again
      </button>
    </div>
    
    <!-- Empty state -->
    <div v-else-if="formations.length === 0" class="bg-gray-50 p-10 text-center rounded-xl shadow-sm">
      <div class="mb-6">
        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
      </div>
      <p class="text-xl text-gray-700 mb-2">No Formations Purchased Yet</p>
      <p class="text-gray-500 mb-6">Discover and invest in educational content to enhance your skills</p>
      <router-link to="/formations" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Browse Formations
      </router-link>
    </div>
    
    <!-- Search and filter bar (shown only when there are formations) -->
    <div v-else class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="relative mb-4 md:mb-0 w-full md:w-64">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search formations..." 
            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>
        <div class="flex items-center">
          <span class="text-gray-600 mr-2">{{ filteredFormations.length }} formations</span>
          <button @click="toggleDisplayMode" class="ml-4 p-2 rounded-md hover:bg-gray-100">
            <svg v-if="displayMode === 'grid'" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg v-else class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Formations list - Grid View -->
    <div v-if="filteredFormations.length > 0 && displayMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div 
        v-for="formation in filteredFormations" 
        :key="formation.id" 
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100"
      >
        <div class="relative">
          <img 
            v-if="`/storage/${formation.image_formation}`" 
            :src="`/storage/${formation.image_formation}`" 
            :alt="formation.titre" 
            class="w-full h-52 object-cover"
          >
          <div v-else class="w-full h-52 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          
          <div class="absolute top-0 right-0 bg-green-500 text-white px-3 py-1 m-3 rounded-full text-sm font-medium shadow-sm">
            Paid
          </div>
        </div>
        
        <div class="p-5">
          <h3 class="text-xl font-semibold mb-2 text-gray-800 line-clamp-1">{{ formation.titre }}</h3>
          <p class="text-gray-600 mb-4 line-clamp-2" v-if="formation.description">{{ formation.description }}</p>
          
          <div class="flex justify-between items-center mt-6">
            <span class="text-blue-600 font-bold text-lg">{{ formatPrice(formation.prix) }}</span>
            <router-link 
              :to="`/formations/${formation.id}`" 
              class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
            >
              Access Course
            </router-link>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Formations list - List View -->
    <div v-else-if="filteredFormations.length > 0 && displayMode === 'list'" class="space-y-4">
      <div 
        v-for="formation in filteredFormations" 
        :key="formation.id" 
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col md:flex-row"
      >
        <div class="relative md:w-64 flex-shrink-0">
          <img 
            v-if="`/storage/${formation.image_formation}`" 
            :src="`/storage/${formation.image_formation}`" 
            :alt="formation.titre" 
            class="w-full h-48 md:h-full object-cover"
          >
          <div v-else class="w-full h-48 md:h-full bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          
          <div class="absolute top-0 right-0 bg-green-500 text-white px-3 py-1 m-3 rounded-full text-sm font-medium shadow-sm">
            Paid
          </div>
        </div>
        
        <div class="p-5 flex-grow flex flex-col justify-between">
          <div>
            <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ formation.titre }}</h3>
            <p class="text-gray-600 mb-4" v-if="formation.description">{{ formation.description }}</p>
          </div>
          
          <div class="flex justify-between items-center mt-4">
            <span class="text-blue-600 font-bold text-lg">{{ formatPrice(formation.prix) }}</span>
            <router-link 
              :to="`/formations/${formation.id}`" 
              class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
            >
              Access Course
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PaidFormations',
  
  data() {
    return {
      formations: [],
      loading: true,
      error: null,
      searchQuery: '',
      displayMode: 'grid' // 'grid' or 'list'
    };
  },
  
  computed: {
    filteredFormations() {
      if (!this.searchQuery) {
        return this.formations;
      }
      
      const query = this.searchQuery.toLowerCase();
      return this.formations.filter(formation => {
        return (
          formation.titre.toLowerCase().includes(query) || 
          (formation.description && formation.description.toLowerCase().includes(query))
        );
      });
    }
  },
  
  created() {
    this.fetchPaidFormations();
  },
  
  methods: {
    async fetchPaidFormations() {
  this.loading = true;
  this.error = null;
  
  try {
    const response = await axios.get('/api/formations/paid'); // Updated route
    this.formations = response.data.formations;
  } catch (err) {
    console.error('Error fetching paid formations:', err);
    this.error = err.response?.data?.message || 'Failed to load formations. Please try again.';
  } finally {
    this.loading = false;
  }
},
    
    formatPrice(price) {
      // Format price with currency symbol (assumes price is in EUR)
      return new Intl.NumberFormat('fr-FR', { 
        style: 'currency', 
        currency: 'EUR' 
      }).format(price);
    },
    
    toggleDisplayMode() {
      this.displayMode = this.displayMode === 'grid' ? 'list' : 'grid';
    }
  }
};
</script>

<style scoped>
.paid-formations-container {
  padding: 1.5rem;
}

.spinner {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>