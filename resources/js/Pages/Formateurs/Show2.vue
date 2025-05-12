<template>
    <div class="container mx-auto px-4 py-8">
      <!-- Formateur Profile -->
      <div class="bg-white rounded-lg shadow mb-8">
        <div class="flex flex-col md:flex-row">
          <div class="w-full md:w-1/3 p-6 flex flex-col items-center">
            <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mb-4">
              <span v-if="!formateur.profile_photo" class="text-2xl font-bold text-gray-400">
                {{ getInitials(formateur.first_name, formateur.last_name) }}
              </span>
              <img 
                v-else 
                :src="formateur.profile_photo" 
                :alt="formateur.first_name + ' ' + formateur.last_name" 
                class="w-full h-full object-cover rounded-full"
              >
            </div>
            <h1 class="text-2xl font-bold text-center">{{ formateur.first_name }} {{ formateur.last_name }}</h1>
            <p class="text-gray-600 text-center mt-1">{{ formateur.email }}</p>
            
            <div class="mt-4 flex gap-2">
              <a 
                v-if="formateur.linkedin_url" 
                :href="formateur.linkedin_url" 
                target="_blank" 
                class="text-blue-600 hover:text-blue-800"
              >
                <i class="fab fa-linkedin text-xl"></i>
              </a>
              <a 
                v-if="formateur.website_url" 
                :href="formateur.website_url" 
                target="_blank" 
                class="text-blue-600 hover:text-blue-800"
              >
                <i class="fas fa-globe text-xl"></i>
              </a>
            </div>
          </div>
          
          <div class="w-full md:w-2/3 p-6 border-t md:border-t-0 md:border-l border-gray-200">
            <h2 class="text-xl font-semibold mb-4">About the Formateur</h2>
            <div v-if="formateur.bio" class="text-gray-700">
              {{ formateur.bio }}
            </div>
            <div v-else class="text-gray-500 italic">
              No biography provided
            </div>
            
            <div class="mt-6">
              <h2 class="text-xl font-semibold mb-3">Expertise</h2>
              <div v-if="formateur.specialties && formateur.specialties.length" class="flex flex-wrap gap-2">
                <span 
                  v-for="specialty in formateur.specialties" 
                  :key="specialty"
                  class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded"
                >
                  {{ specialty }}
                </span>
              </div>
              <div v-else class="text-gray-500 italic">
                No specialties listed
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Formations Section -->
      <div>
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold">Formations</h2>
          
          <div class="flex gap-2">
            <Link 
              :href="`/formateurs/${formateur.id}/statistics`"
              class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition"
            >
              View Statistics
            </Link>
            
            <Link 
              :href="`/formateurs/${formateur.id}/formations`"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >
              View All Formations
            </Link>
          </div>
        </div>
        
        <!-- Formations List -->
        <div v-if="formations.length > 0">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="formation in formations" :key="formation.id" class="bg-white rounded-lg shadow overflow-hidden">
              <div class="relative">
                <img 
                  v-if="formation.image_formation" 
                  :src="formation.image_formation" 
                  :alt="formation.titre"
                  class="w-full h-48 object-cover"
                >
                <div v-else class="w-full h-48 bg-gray-200 flex items-center justify-center">
                  <span class="text-gray-400">No image</span>
                </div>
                
                <!-- Status Badges -->
                <div class="absolute top-2 right-2 flex gap-2">
                  <span v-if="formation.est_publiee" class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    Published
                  </span>
                  
                  <span v-if="formation.estcertifiante" class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    Certified
                  </span>
                </div>
              </div>
              
              <div class="p-4">
                <h3 class="text-xl font-semibold mb-2">{{ formation.titre }}</h3>
                
                <div class="flex items-center text-sm text-gray-500 mb-3">
                  <span class="mr-3">
                    <i class="fas fa-clock mr-1"></i>
                    {{ formation.duree }}
                  </span>
                  <span>
                    <i class="fas fa-layer-group mr-1"></i>
                    {{ formation.modules_count }} modules
                  </span>
                </div>
                
                <p class="text-gray-600 mb-4 line-clamp-3">{{ formation.description }}</p>
                
                <div class="flex items-center justify-between">
                  <span class="text-lg font-bold text-blue-600">{{ formatPrice(formation.prix) }}</span>
                  
                  <Link 
                    :href="`/formations/${formation.id}`"
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                  >
                    View Details
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div v-else class="bg-white rounded-lg shadow p-8 text-center">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">No formations available</h2>
          <p class="text-gray-500">This formateur hasn't published any formations yet.</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/vue3';
  
  export default {
    components: {
      Link
    },
    
    props: {
      formateur: {
        type: Object,
        required: true
      },
      formations: {
        type: Array,
        required: true
      }
    },
    
    methods: {
      getInitials(firstName, lastName) {
        return `${firstName?.charAt(0) || ''}${lastName?.charAt(0) || ''}`;
      },
      
      formatPrice(price) {
        return new Intl.NumberFormat('fr-FR', {
          style: 'currency',
          currency: 'EUR'
        }).format(price);
      }
    }
  }
  </script>