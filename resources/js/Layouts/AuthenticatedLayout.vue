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

// Existing component logic remains the same as previous implementation
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

const toggleNavigationDropdown = () => {
  showingNavigationDropdown.value = !showingNavigationDropdown.value;
};

const navigateToRoute = (routeName) => {
  router.visit(route(routeName));
};

const logout = async () => {
  await router.post(route('logout'));
};
</script>

<template>
  <div class="flex relative">
    <!-- Sidebar Toggle Button -->
    <button 
      @click="toggleSidebar" 
      class="sidebar-toggle-button z-[1100]"
    >
      <svg 
        xmlns="http://www.w3.org/2000/svg" 
        class="h-6 w-6 transition-transform duration-300"
        :class="isSidebarOpen ? 'rotate-180' : ''"
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          stroke-width="2" 
          d="M11 19l-7-7 7-7m8 14l-7-7 7-7" 
        />
      </svg>
    </button>

    <!-- Floating Sidebar -->
    <div 
      class="sidebar fixed top-0 left-0 h-full transition-all duration-300 ease-in-out overflow-hidden"
      :class="{
        'w-0 opacity-0': !isSidebarOpen,
        'w-72 opacity-100': isSidebarOpen,
        'shadow-lg': isSidebarCollapsing
      }"
    >
      <div class="relative h-full w-72 bg-[#1e2433] text-white p-6 overflow-y-auto">
        <div class="flex items-center mb-6">
          <Link :href="route('dashboard')" class="text-xl font-bold">
            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-200" />
          </Link>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
          <input 
            v-model="searchQuery"
            type="text"
            placeholder="Search Formations"
            class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            @input="searchFormations"
          />
        </div>

        <!-- Categories Dropdown -->
        <div class="relative mb-4">
          <Dropdown align="right" width="48">
            <template #trigger>
              <span class="inline-flex rounded-md w-full">
                <button
                  type="button"
                  class="inline-flex items-center w-full rounded-md border border-transparent bg-gray-700 px-3 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:bg-gray-600 focus:outline-none"
                >
                  {{ selectedCategoryName || "Select Category" }}
                  <svg
                    class="-me-0.5 ms-2 h-4 w-4"
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
              >
                {{ category.name }}
              </DropdownLink>
            </template>
          </Dropdown>
        </div>

        <!-- Sidebar Navigation Links -->
        <div class="space-y-2">
          <template v-if="role === 'formateur'">
            <NavLink 
              v-for="link in [
                { route: 'DashboardFormateur', label: 'Dashboard Formateur' },
                { route: 'upload.videos', label: 'Upload Vidéos' },
                { route: 'afficher.videos', label: 'Voir Vidéos' },
                { route: 'formations.index', label: 'Formations' }
              ]"
              :key="link.route"
              :href="route(link.route)"
              :active="route().current(link.route)"
              class="block px-4 py-2 hover:bg-gray-700 rounded-lg transition-colors"
            >
              {{ link.label }}
            </NavLink>
          </template>
          
          <template v-else-if="role === 'admin'">
            <NavLink 
              v-for="link in [
                { route: 'DashboardAdmin', label: 'Dashboard Administrateur' },
                { route: 'formateur.en.attente', label: 'Formateurs en attente' },
                { route: 'formateurs.index', label: 'Formateurs' }
              ]"
              :key="link.route"
              :href="route(link.route)"
              :active="route().current(link.route)"
              class="block px-4 py-2 hover:bg-gray-700 rounded-lg transition-colors"
            >
              {{ link.label }}
            </NavLink>

            <div class="relative">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button class="w-full text-left px-4 py-2 hover:bg-gray-700 rounded-lg">
                    Sélectionner une page
                  </button>
                </template>
                <template #content>
                  <DropdownLink @click="navigateToRoute('categories.index')">
                    Catégories
                  </DropdownLink>
                  <DropdownLink @click="navigateToRoute('formations.index')">
                    Formations
                  </DropdownLink>
                </template>
              </Dropdown>
            </div>
          </template>
        </div>

        <!-- User Profile and Logout -->
        <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-700">
          <div class="flex justify-between items-center">
            <Link 
              :href="route('profile.edit')" 
              class="text-sm hover:text-blue-400 transition-colors"
            >
              Profile
            </Link>
            <button 
              @click="logout" 
              class="text-sm hover:text-red-400 transition-colors"
            >
              Déconnecter
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div 
      class="main-content transition-all duration-300 ease-in-out"
      :class="{
        'ml-72': isSidebarOpen,
        'ml-0': !isSidebarOpen
      }"
    >
      <header 
        class="sticky top-0 z-50 transition-all duration-300"
        :class="{
          'pl-72': isSidebarOpen,
          'pl-0': !isSidebarOpen
        }"
      >
        <slot name="header" />
      </header>

      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Additional custom styles can be added here if needed */
.sidebar-toggle-button {
  position: fixed;
  top: 1rem;
  left: 1rem;
  z-index: 1100;
  background-color: rgba(74, 144, 226, 0.1);
  color: #4a90e2;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.sidebar-toggle-button:hover {
  background-color: rgba(74, 144, 226, 0.2);
}
</style>