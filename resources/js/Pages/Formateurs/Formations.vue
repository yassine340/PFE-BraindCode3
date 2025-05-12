<template>
    <div class="container mx-auto px-4 py-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-3xl font-bold">{{ formateur.first_name }} {{ formateur.last_name }}'s Formations</h1>
        
        <Link 
          :href="`/formateurs/${formateur.id}`"
          class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
        >
          View Profile
        </Link>
      </div>
      
      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h2 class="text-lg font-semibold mb-3">Filter Formations</h2>
        
        <div class="flex flex-wrap gap-4">
          <Link 
            :href="`/formateurs/${formateur.id}/formations`"
            class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100"
            :class="{'bg-blue-600 text-white hover:bg-blue-700 border-blue-600': !filters.est_publiee && !filters.est_valide}"
          >
            All
          </Link>
          
          <Link 
            :href="`/formateurs/${formateur.id}/formations?est_publiee=1`"
            class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100"
            :class="{'bg-green-600 text-white hover:bg-green-700 border-green-600': filters.est_publiee}"
          >
            Published
          </Link>
          
          <Link 
            :href="`/formateurs/${formateur.id}/formations?est_publiee=0`"
            class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100"
            :class="{'bg-yellow-600 text-white hover:bg-yellow-700 border-yellow-600': filters.est_publiee === false}"
          >
            Drafts
          </Link>
          
          <Link 
            :href="`/formateurs/${formateur.id}/formations?est_valide=Validée`"
            class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100"
            :class="{'bg-purple-600 text-white hover:bg-purple-700 border-purple-600': filters.est_valide === 'Validée'}"
          >
            Validated
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
                <span v-else class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                  Draft
                </span>
                
                <span v-if="formation.est_valide === 'Validée'" class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                  Validated
                </span>
                <span v-else-if="formation.est_valide === 'En attente'" class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">
                  Pending
                </span>
              </div>
            </div>
            
            <div class="p-4">
              <h2 class="text-xl font-semibold mb-2">{{ formation.titre }}</h2>
              
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
        <p class="text-gray-500">
          No formations match the selected filters.
          <Link 
            :href="`/formateurs/${formateur.id}/formations`"
            class="text-blue-600 hover:underline"
          >
            Clear filters
          </Link>
        </p>
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
      },
      filters: {
        type: Object,
        default: () => ({})
      }
    },
    
    methods: {
      formatPrice(price) {
        return new Intl.NumberFormat('fr-FR', {
          style: 'currency',
          currency: 'EUR'
        }).format(price);
      }
    }
  }
  </script>