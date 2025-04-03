<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, router } from '@inertiajs/vue3';
import axios from "axios";

// Advanced Sidebar Toggle
const isSidebarOpen = ref(true);
const isSidebarCollapsing = ref(false);

const toggleSidebar = () => {
  if (isSidebarOpen.value) {
    // Start closing animation
    isSidebarCollapsing.value = true;
    setTimeout(() => {
      isSidebarOpen.value = false;
      isSidebarCollapsing.value = false;
    }, 300);
  } else {
    // Open sidebar
    isSidebarOpen.value = true;
  }
};

// Existing component logic remains the same
const filterFormations = async () => {
  if (selectedCategory.value !== "") {
    await router.get("/formationscat", {
      category: selectedCategory.value,
    }, { preserveState: true });
  }
};

const props = defineProps({
  formations: {
    type: Array,
    default: () => [],
  },
  categories: {
    type: Array,
    default: () => [],
  }
});

const searchQuery = ref("");
const formations = ref(props.formations); 

watch(
  () => props.formations,
  (newFormations) => {
    formations.value = newFormations || [];
  }
);

const searchFormations = async () => {
  await router.get("/formations", {
    search: searchQuery.value,
  }, { preserveState: true });
};

const categories = ref([]);
const selectedCategory = ref("");
const selectedCategoryName = ref("");

onMounted(async () => {
  try {
    const response = await axios.get("/categories");
    categories.value = response.data;
  } catch (error) {
    console.error("Erreur lors du chargement des catégories", error);
  }
});

const page = usePage();
const user = computed(() => page.props.auth.user); 

const role = computed(() => user.value?.role || 'user'); 

const showingNavigationDropdown = ref(false);

const navigateToRoute = (routeName) => {
  router.visit(route(routeName));
};

const logout = async () => {
  await router.post(route('logout'));
};
</script>

<template>
  <div class="flex relative min-h-screen bg-gray-50">
    <!-- Sidebar Toggle Button Amélioré -->
    <button 
      @click="toggleSidebar" 
      class="sidebar-toggle-button"
      :class="{'sidebar-toggle-open': isSidebarOpen}"
      aria-label="Toggle sidebar"
    >
      <div class="button-inner-container">
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          class="toggle-icon"
          :class="isSidebarOpen ? 'rotate-180' : ''"
          viewBox="0 0 24 24" 
          stroke="currentColor"
          fill="none"
        >
          <path 
            stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" 
          />
        </svg>
      </div>
    </button>

    <!-- Floating Sidebar -->
    <div 
      class="sidebar fixed top-0 left-0 h-full transition-all duration-300 ease-in-out overflow-hidden z-50"
      :class="{
        'w-0 -translate-x-full': !isSidebarOpen,
        'w-72 translate-x-0': isSidebarOpen,
      }"
    >
      <div class="relative h-full w-72 bg-gradient-to-b from-[#1e2433] to-[#272f42] text-white p-6 overflow-y-auto shadow-xl">
        <div class="flex items-center mb-8">
          <Link :href="route('dashboard')" class="text-xl font-bold">
            <ApplicationLogo class="block h-10 w-auto fill-current text-blue-400" />
          </Link>
        </div>



        <!-- Categories Dropdown -->
        <div class="relative mb-6">
          <label class="block text-xs text-gray-400 font-medium mb-2 pl-1">CATÉGORIE</label>
          <Dropdown align="right" width="48">
            <template #trigger>
              <span class="inline-flex rounded-md w-full">
                <button
                  type="button"
                  class="inline-flex items-center justify-between w-full rounded-xl border border-gray-600/30 bg-gray-700/50 px-4 py-2.5 text-sm font-medium text-white transition duration-200 ease-in-out hover:bg-gray-600/70 focus:outline-none focus:ring-2 focus:ring-blue-500/70"
                >
                  {{ selectedCategoryName || "Sélectionner une catégorie" }}
                  <svg
                    class="h-4 w-4 ml-2"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
              </span>
            </template>
            
            <template #content>
              <DropdownLink
                v-for="category in categories"
                :key="category.id"
                @click="() => {
                  selectedCategory = category.id;
                  selectedCategoryName = category.name;
                  filterFormations();
                }"
                class="hover:bg-blue-500/10 transition-colors duration-150"
              >
                {{ category.name }}
              </DropdownLink>
            </template>
          </Dropdown>
        </div>

        <!-- Sidebar Navigation Links -->
        <div class="space-y-1 mt-8">
          <label class="block text-xs text-gray-400 font-medium mb-3 pl-1">NAVIGATION</label>
          <NavLink
            :href="route('formations.index')"
            :active="route().current('formations.index')"
            class="flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200 group"
            :class="{ 'bg-blue-600/20 text-blue-300': route().current('formations.index') }"
          >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3"
              :class="{ 'text-blue-400': route().current('formations.index') }"
              fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
          Formations
          </NavLink>
          <NavLink
            :href="route('user.stats')"
            :active="route().current('user.stats')"
            class="flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200 group"
            :class="{ 'bg-blue-600/20 text-blue-300': route().current('user.stats') }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" 
                 :class="{ 'text-blue-400': route().current('user.stats') }"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Mes statistiques
          </NavLink>
          
          <!-- Lien Statistiques - Disponible pour tous les utilisateurs -->
         
          
          <template v-if="role === 'formateur'">
            <NavLink 
              v-for="link in [
                { route: 'DashboardFormateur', label: 'Dashboard Formateur', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
                { route: 'upload.videos', label: 'Upload Vidéos', icon: 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12' },
                { route: 'afficher.videos', label: 'Voir Vidéos', icon: 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z' },
                { route: 'formations.index', label: 'Formations', icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' }
              ]"
              :key="link.route"
              :href="route(link.route)"
              :active="route().current(link.route)"
              class="flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200 group"
              :class="{ 'bg-blue-600/20 text-blue-300': route().current(link.route) }"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" 
                   :class="{ 'text-blue-400': route().current(link.route) }"
                   fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="link.icon" />
              </svg>
              {{ link.label }}
            </NavLink>
          </template>
          
          <template v-else-if="role === 'admin'">
            <NavLink 
              v-for="link in [
                { route: 'DashboardAdmin', label: 'Dashboard Admin', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
                { route: 'formateur.en.attente', label: 'Formateurs en attente', icon: 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z' },
                { route: 'formateurs.index', label: 'Formateurs', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' }
              ]"
              :key="link.route"
              :href="route(link.route)"
              :active="route().current(link.route)"
              class="flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200 group"
              :class="{ 'bg-blue-600/20 text-blue-300': route().current(link.route) }"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" 
                   :class="{ 'text-blue-400': route().current(link.route) }"
                   fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="link.icon" />
              </svg>
              {{ link.label }}
            </NavLink>
            
            <div class="relative mt-2">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button class="w-full text-left flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Autres pages
                    <svg class="ml-auto h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </template>
                <template #content>
                  <DropdownLink @click="navigateToRoute('categories.index')" class="hover:bg-blue-500/10 transition-colors duration-150">
                    <div class="flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                      </svg>
                      Catégories
                    </div>
                  </DropdownLink>
                  <DropdownLink @click="navigateToRoute('formations.index')" class="hover:bg-blue-500/10 transition-colors duration-150">
                    <div class="flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                      </svg>
                      Formations
                    </div>
                  </DropdownLink>
                </template>
              </Dropdown>
            </div>
          </template>
        </div>

        <!-- User Profile and Logout -->
        <div class="absolute bottom-0 left-0 right-0 p-5 border-t border-gray-700/50 bg-gray-800/20 backdrop-blur-sm">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-300 font-semibold mr-3">
                {{ user?.first_name?.charAt(0).toUpperCase() || 'U' }}
              </div>
              <div class="flex flex-col">
                <span class="text-sm font-medium">{{ user?.first_name || 'Utilisateur' }}</span>
                <span class="text-xs text-gray-400">{{ role === 'admin' ? 'Administrateur' : (role === 'formateur' ? 'Formateur' : 'Apprenant') }}</span>
              </div>
            </div>
            <div class="flex space-x-3">
              <Link 
                :href="route('profile.edit')" 
                class="p-2 rounded-full hover:bg-gray-700/50 text-gray-400 hover:text-white transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </Link>
              <button 
                @click="logout" 
                class="p-2 rounded-full hover:bg-gray-700/50 text-gray-400 hover:text-red-400 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div 
      class="main-content flex-1 transition-all duration-300 ease-in-out"
      :class="{
        'ml-72': isSidebarOpen,
        'ml-0': !isSidebarOpen
      }"
    >
      <header 
        class="sticky top-0 z-40 transition-all duration-300 bg-white/70 backdrop-blur shadow-sm"
      >
        <slot name="header" />
      </header>

      <main class="py-8 px-6 md:px-8 lg:px-10">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
.sidebar-toggle-button {
  position: fixed;
  top: 1.5rem;
  left: 1.5rem;
  z-index: 1200;
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.1));
  border: none;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15), 0 0 0 2px rgba(59, 130, 246, 0.05);
  backdrop-filter: blur(10px);
  cursor: pointer;
  overflow: hidden;
  outline: none;
}

.button-inner-container {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

.button-inner-container::after {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 50%;
}

.sidebar-toggle-button:hover {
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(37, 99, 235, 0.15));
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 20px rgba(37, 99, 235, 0.2), 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.sidebar-toggle-button:hover .button-inner-container::after {
  opacity: 1;
}

.sidebar-toggle-button:active {
  transform: translateY(0) scale(0.98);
  box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1), 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.toggle-icon {
  height: 20px;
  width: 20px;
  color: rgb(37, 99, 235);
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.3s ease;
  filter: drop-shadow(0 0 1px rgba(59, 130, 246, 0.5));
}

.sidebar-toggle-open {
  left: 18.5rem;
  background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(59, 130, 246, 0.1));
}

.sidebar-toggle-open .toggle-icon {
  color: rgb(30, 64, 175);
}

/* Animation de pulsation subtile */
@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.3); }
  70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
  100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
}

/* Appliquer l'animation seulement quand le sidebar est fermé */
.sidebar-toggle-button:not(.sidebar-toggle-open) {
  animation: pulse 2s infinite;
}

/* Smooth scrollbar for the sidebar */
.sidebar div {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
}

.sidebar div::-webkit-scrollbar {
  width: 4px;
}

.sidebar div::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar div::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.2);
  border-radius: 20px;
}

/* Animation pour le sidebar */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.sidebar {
  animation: fadeIn 0.3s ease-out;
}
</style>