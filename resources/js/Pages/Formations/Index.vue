<template>
  <Head title="Découvrir nos formations" />
  <AuthenticatedLayout>
    <div class="min-h-screen py-12">
      <div class="container mx-auto px-4 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
            Découvrez nos formations
          </h1>
          <p class="text-gray-600 max-w-2xl mx-auto">
            Développez vos compétences avec notre sélection de formations de qualité.
          </p>
        </div>

        <!-- Filters -->
        <div class="mb-10 max-w-3xl mx-auto flex flex-col md:flex-row gap-4">
          <!-- Search -->
          <div class="flex-1">
            <div class="relative flex items-center w-full h-12 rounded-lg bg-white border border-gray-200 shadow-sm">
              <div class="grid place-items-center h-full w-12 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input
                v-model="searchQuery"
                @input="searchFormations"
                class="peer h-full w-full outline-none text-sm text-gray-700 pr-2 border-0 focus:ring-0"
                type="text"
                placeholder="Rechercher une formation..."
              />
            </div>
          </div>

          <!-- Category dropdown -->
          <div class="md:w-1/3">
            <Dropdown align="right" width="48">
              <template #trigger>
                <button type="button" class="flex items-center justify-between w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700">
                  {{ selectedCategoryName || "Toutes les catégories" }}
                  <svg class="h-4 w-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </template>
              
              <template #content>
                <DropdownLink @click="resetCategory">Toutes les catégories</DropdownLink>
                <DropdownLink v-for="category in categories" :key="category.id"
                  @click="() => { selectedCategory = category.id.toString(); selectedCategoryName = category.name; filterFormations(); }">
                  {{ category.name }}
                </DropdownLink>
              </template>
            </Dropdown>
          </div>
        </div>
        <!-- Course grid -->
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-8">
          <div v-for="formation in formations" :key="formation.id" class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col h-full">
            <!-- Course image - MODIFIÉ POUR S3 -->
            <div class="relative overflow-hidden h-48">
              <!-- Utilisation de image_url au lieu de image_formation -->
              <img 
                v-if="formation.image_url" 
                :src="formation.image_url" 
                :alt="formation.titre || 'Image formation'" 
                class="w-full h-full object-cover"
                @error="handleImageError"
              >
              <div v-else class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              
              <!-- Badge S3 (optionnel) -->
              <div 
                v-if="isS3Image(formation)" 
                class="absolute top-2 left-2 bg-blue-500 text-white text-xs font-bold py-1 px-2 rounded-full"
              >
                S3
              </div>
              
              <Link :href="`/formations/${formation.id}`" class="block">
                <div class="absolute top-3 right-3 bg-white bg-opacity-90 rounded-full py-1 px-3 text-sm font-semibold text-indigo-600">
                  {{ formatDateTime(formation.created_at) }}
                </div>
              </Link>
            </div>
            <div class="flex-1 p-6 flex flex-col">
              <Link :href="`/formations/${formation.id}`" class="block">
                <h2 class="text-xl font-bold text-gray-900 hover:text-blue-600 mb-2 line-clamp-2">
                  {{ formation.titre }}
                </h2>
              </Link>
              
              <p class="text-gray-600 text-sm mb-4 flex-1 line-clamp-2">
                {{ formation.description || "Découvrez cette formation et développez vos compétences professionnelles." }}
              </p>
              
              <!-- Metadata -->
              <div class="flex items-center text-sm text-gray-500 mb-4">
                <div class="flex items-center mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>{{ formation.duree || "À votre rythme" }}</span>
                </div>
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                  <span>{{ formation.modules_count || 0}} modules</span>
                </div>
              </div>
              
              <Link :href="`/formations/${formation.id}`" class="text-center py-2 px-4 bg-indigo-50 text-indigo-600 font-medium rounded-lg hover:bg-indigo-100 mt-auto">
                Découvrir la formation
              </Link>
            </div>
          </div>
        </div>
        
        <!-- No courses message -->
        <div v-if="formations.length === 0" class="text-center py-12">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune formation disponible</h3>
          <p class="text-gray-500">Revenez plus tard pour découvrir nos nouvelles formations.</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  
<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref, onMounted, watch } from 'vue';
import dayjs from 'dayjs';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import axios from 'axios';

const formatDateTime = (date) => {
  return dayjs(date).format('DD/MM/YYYY HH:mm');
};

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
}

interface PageProps {
  auth: {
    user: User | null;
  };
  [key: string]: unknown;
}

interface Formation {
  id: number;
  titre: string;
  description: string;
  created_at: string;
  image_formation: string | null;
  image_url: string | null; // Ajouté pour S3
  duree: string;
  modules_count: number;
}

interface Category {
  id: number;
  name: string;
}

const props = defineProps<{
  formations: Formation[];
  categories?: Category[];
}>();

const searchQuery = ref("");
const formations = ref(props.formations);
const categories = ref<Category[]>([]);
const selectedCategory = ref("");
const selectedCategoryName = ref("");

// Gestionnaire d'erreur d'image
const handleImageError = (event) => {
  console.warn(`Erreur de chargement de l'image pour ${event.target.alt}`);
  event.target.src = '/images/placeholder-formation.jpg';
};

// Méthode pour détecter si une image provient de S3
const isS3Image = (formation) => {
  return formation.image_url && 
         (formation.image_url.includes('amazonaws.com') || 
          formation.image_url.includes('s3.'));
};

watch(() => props.formations, newVal => formations.value = newVal || []);

const filterFormations = () => {
  if (selectedCategory.value) {
    router.get("/formationscat", { category: selectedCategory.value }, { preserveState: true });
  }
};

const resetCategory = () => {
  selectedCategory.value = "";
  selectedCategoryName.value = "";
  router.get("/formations", {}, { preserveState: true });
};

const searchFormations = async () => {
  await router.get("/formations", {
    search: searchQuery.value,
  }, { preserveState: true });
};

const page = usePage<PageProps>();
const role = computed(() => page.props.auth.user?.role || 'user');

onMounted(async () => {
  try {
    const response = await axios.get("/categories");
    categories.value = response.data;
    
    // Logs de diagnostic pour les images S3
    console.group('📊 DIAGNOSTIC FORMATIONS LISTE');
    console.log('Nombre de formations:', formations.value.length);
    
    // Compter les images S3
    const s3Images = formations.value.filter(f => isS3Image(f));
    console.log('Images S3:', s3Images.length);
    console.log('Images locales/autres:', formations.value.length - s3Images.length);
    
    if (s3Images.length > 0) {
      console.log('Exemple URL S3:', s3Images[0].image_url);
    }
    
    console.groupEnd();
  } catch (error) {
    console.error("Erreur lors du chargement des catégories", error);
  }
});
</script>

<style>
.bg-clip-text {
  -webkit-background-clip: text;
  background-clip: text;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>