<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import axios from "axios";

// Responsive sidebar
const isMobileView = ref(false);
const checkScreenSize = () => {
  isMobileView.value = window.innerWidth < 768; // Consider mobile under 768px
  
  // Auto close sidebar on mobile
  if (isMobileView.value && isSidebarOpen.value) {
    isSidebarOpen.value = false;
  }
  
  // Auto open sidebar on desktop
  if (!isMobileView.value && !isSidebarOpen.value && !userClosedSidebar.value) {
    isSidebarOpen.value = true;
  }
};

// Keep track of user's preference
const userClosedSidebar = ref(false);

// Sidebar toggle functionality
const isSidebarOpen = ref(true);
const isSidebarCollapsing = ref(false);

const toggleSidebar = () => {
  if (isSidebarOpen.value) {
    isSidebarCollapsing.value = true;
    userClosedSidebar.value = true;
    setTimeout(() => {
      isSidebarOpen.value = false;
      isSidebarCollapsing.value = false;
    }, 300);
  } else {
    isSidebarOpen.value = true;
    userClosedSidebar.value = false;
  }
};

const props = defineProps({
  formations: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] }
});

// Search functionality
const searchQuery = ref("");
const formations = ref(props.formations);
const categories = ref([]);

watch(() => props.formations, newVal => formations.value = newVal || []);

const searchFormations = () => {
  router.get("/formations", { search: searchQuery.value }, { preserveState: true });
};

// User data and navigation
const page = usePage();
const user = computed(() => page.props.auth.user);
const role = computed(() => user.value?.role || 'user');
const showingNavigationDropdown = ref(false);

const navigateToRoute = (routeName) => router.visit(route(routeName));
const logout = () => router.post(route('logout'));

// Load categories on mount and setup responsive behavior
onMounted(async () => {
  try {
    const response = await axios.get("/categories");
    categories.value = response.data;
  } catch (error) {
    console.error("Erreur lors du chargement des catégories", error);
  }
  
  // Initial check
  checkScreenSize();
  
  // Add resize listener
  window.addEventListener('resize', checkScreenSize);
});
</script>

<template>
  <div class="flex relative min-h-screen bg-gray-50">
    <!-- Sidebar Toggle Button - Fixed positioning -->
    <button @click="toggleSidebar" 
      class="sidebar-toggle-button"
      :class="{
        'sidebar-toggle-open': isSidebarOpen,
        'sidebar-toggle-mobile': isMobileView
      }"
      aria-label="Toggle sidebar">
      <div class="button-inner-container">
        <svg xmlns="http://www.w3.org/2000/svg" 
          class="toggle-icon"
          :class="isSidebarOpen ? 'rotate-180' : ''"
          viewBox="0 0 24 24" stroke="currentColor" fill="none">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
        </svg>
      </div>
    </button>

    <!-- Sidebar -->
    <div class="sidebar fixed top-0 left-0 h-full transition-all duration-300 ease-in-out overflow-hidden z-50"
      :class="{
        'sidebar-closed': !isSidebarOpen,
        'sidebar-open': isSidebarOpen,
        'mobile-sidebar-closed': !isSidebarOpen && isMobileView,
        'mobile-sidebar-open': isSidebarOpen && isMobileView
      }">
      <div class="relative h-full bg-white p-4 overflow-y-auto shadow-xl sidebar-inner">
        <!-- Logo -->
        <div class="flex items-center mb-8 px-2">
          <Link :href="route('dashboard')" class="flex items-center">
            <img src="/image/logos/Icon_BraindCode_Blue.png" class="sidebar-logo-icon h-8 w-8" alt="Logo" />
            <div class="ml-3 sidebar-logo-text">
              <div class="text-lg font-bold text-sky-500">Braindcode</div>
              <div class="text-xs text-black" style="margin-left: 26px;">Startup Studio</div>
            </div>
          </Link>
        </div>

        <!-- Navigation Links -->
        <div class="space-y-1 mt-8">
          <!-- Common Links -->
          <NavLink :href="route('formations.index')" :active="route().current('formations.index')"
            class="flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200 group"
            :class="{ 'bg-blue-600/20 text-blue-300': route().current('formations.index') }">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" :class="{ 'text-blue-400': route().current('formations.index') }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Formations
          </NavLink>
          
          <NavLink :href="route('user.stats')" :active="route().current('user.stats')"
            class="flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200 group"
            :class="{ 'bg-blue-600/20 text-blue-300': route().current('user.stats') }">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" :class="{ 'text-blue-400': route().current('user.stats') }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Mes statistiques
          </NavLink>
          
          <!-- Formateur Links -->
          <template v-if="role === 'formateur'">
            <NavLink v-for="link in [
              { route: 'DashboardFormateur', label: 'Dashboard Formateur', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
              { route: 'upload.videos', label: 'Upload Vidéos', icon: 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12' },
              { route: 'afficher.videos', label: 'Voir Vidéos', icon: 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z' },
            ]"
            :key="link.route" :href="route(link.route)" :active="route().current(link.route)"
            class="flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200 group"
            :class="{ 'bg-blue-600/20 text-blue-300': route().current(link.route) }">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" :class="{ 'text-blue-400': route().current(link.route) }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="link.icon" />
              </svg>
              {{ link.label }}
            </NavLink>
          </template>
          
          <!-- Admin Links -->
          <template v-else-if="role === 'admin'">
            <NavLink v-for="link in [
              { route: 'DashboardAdmin', label: 'Dashboard Admin', icon: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' },
              { route: 'formateur.en.attente', label: 'Formateurs en attente', icon: 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z' },
              { route: 'formateurs.index', label: 'Formateurs', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' }
            ]"
            :key="link.route" :href="route(link.route)" :active="route().current(link.route)"
            class="flex items-center px-4 py-3 hover:bg-gray-700/50 rounded-xl transition-all duration-200 group"
            :class="{ 'bg-blue-600/20 text-blue-300': route().current(link.route) }">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" :class="{ 'text-blue-400': route().current(link.route) }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="link.icon" />
              </svg>
              {{ link.label }}
            </NavLink>
          </template>
        </div>
        
        <!-- User Profile -->
        <div class="absolute bottom-0 left-0 right-0 p-5 backdrop-blur-sm">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-300 font-semibold mr-3">
                <div v-if="user.profile_image" class="flex-shrink-0 h-10 w-10">
                  <img class="h-10 w-10 rounded-full" :src="user.profile_image" alt="" />
                </div>
                <template v-else>
                  {{ user?.first_name?.charAt(0) || 'U' }}
                </template>
              </div>
              <div class="flex flex-col">
                <span class="text-sm font-medium">{{ user?.first_name || 'Utilisateur' }}</span>
                <span class="text-xs text-gray-400">{{ role === 'admin' ? 'Administrateur' : (role === 'formateur' ? 'Formateur' : 'Apprenant') }}</span>
              </div>
            </div>
            <div class="flex space-x-3">
              <Link :href="route('profile.edit')" class="p-2 rounded-full hover:bg-gray-700/50 text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </Link>
              <button @click="logout" class="p-2 rounded-full hover:bg-gray-700/50 text-gray-400 hover:text-red-400 transition-colors">
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
    <div class="main-content flex-1 transition-all duration-300 ease-in-out"
      :class="{
        'ml-64': isSidebarOpen && !isMobileView,
        'ml-16': !isSidebarOpen && !isMobileView,
        'ml-0': isMobileView
      }">
      <header class="sticky top-0 z-40 transition-all duration-300 bg-white/70 backdrop-blur shadow-sm">
        <slot name="header" />
      </header>

      <main class="py-8 px-6 md:px-8 lg:px-10 bg-sky-50">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Sidebar base styling */
.sidebar {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.sidebar-open {
  width: 16rem; /* 256px */
}

.sidebar-closed {
  width: 4rem; /* 64px */
}

.sidebar-inner {
  width: 16rem; /* Fixed width */
}

/* Logo styling */
.sidebar-logo-icon {
  transition: all 0.3s ease-in-out;
}

.sidebar-logo-text {
  transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
  white-space: nowrap;
}

.sidebar-closed .sidebar-logo-text {
  opacity: 0;
  transform: translateX(10px);
  position: absolute;
}

/* Navigation link styling */
.sidebar-nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  transition: all 0.2s ease-in-out;
  color: #99d3ff;
  white-space: nowrap;
  position: relative;
}

.sidebar-nav-link:hover {
  background-color: rgba(153, 211, 255, 0.1);
}

.sidebar-nav-link.router-link-active {
  background-color: rgba(153, 211, 255, 0.15);
  color: #99d3ff;
}

.sidebar-icon {
  width: 1.25rem;
  height: 1.25rem;
  stroke: currentColor;
  flex-shrink: 0;
  margin-right: 0.75rem;
  transition: margin 0.3s ease;
}

.sidebar-link-text {
  display: flex;
  align-items: center;
  transition: opacity 0.2s ease-in-out, transform 0.3s ease-in-out;
}

.sidebar-closed .sidebar-link-text {
  opacity: 0;
  transform: translateX(10px);
  position: absolute;
  pointer-events: none;
}

/* Highlight badge for active items */
.highlight-badge {
  display: inline-flex;
  background-color: #ffdd00;
  color: #333;
  font-size: 0.7rem;
  border-radius: 0.25rem;
  padding: 0 0.25rem;
  margin-left: 0.5rem;
  font-weight: 500;
}

/* Sidebar toggle button - Fixed positioning */
.sidebar-toggle-button {
  position: fixed;
  top: 2.7rem;
  z-index: 1200;
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* For desktop - When sidebar is open */
.sidebar-toggle-button {
  left: 14.5rem; /* Aligned with expanded sidebar */
}

/* For desktop - When sidebar is closed */
.sidebar-toggle-button:not(.sidebar-toggle-open) {
  left: 3rem; /* Position when sidebar is collapsed */
}

/* For mobile devices */
.sidebar-toggle-mobile {
  left: 1rem !important; /* Force left position for mobile */
}

.button-inner-container {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.toggle-icon {
  height: 1rem;
  width: 1rem;
  color: #99d3ff;
  transition: transform 0.3s ease;
}

/* Scrollbar styling */
.sidebar div::-webkit-scrollbar {
  width: 3px;
}

.sidebar div::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar div::-webkit-scrollbar-thumb {
  background-color: rgba(153, 211, 255, 0.3);
  border-radius: 20px;
}

/* Animation for sidebar transition */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.sidebar {
  animation: fadeIn 0.3s ease-out;
}

/* Media queries for responsive design */
@media (max-width: 767px) {
  /* Mobile sidebar */
  .mobile-sidebar-open {
    width: 16rem; /* Full width on mobile */
    transform: translateX(0);
  }
  
  .mobile-sidebar-closed {
    width: 16rem; /* Keep width but move offscreen */
    transform: translateX(-100%);
  }
  
  /* Add a backdrop when sidebar is open on mobile */
  .mobile-sidebar-open::after {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: -1;
  }
}

@media (min-width: 768px) and (max-width: 1023px) {
  /* Tablet adjustments */
  .sidebar-open {
    width: 14rem;
  }
  
  .sidebar-toggle-button {
    left: 12.5rem;
  }
  
  .sidebar-toggle-button:not(.sidebar-toggle-open) {
    left: 3rem;
  }
}

@media (min-width: 1024px) {
  /* Desktop adjustments */
  .sidebar-toggle-button {
    left: 14.5rem;
  }
  
  .sidebar-toggle-button:not(.sidebar-toggle-open) {
    left: 3rem;
  }
}
</style>