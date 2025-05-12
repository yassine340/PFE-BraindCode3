// MainContent.vue
<template>
  <div class="container mx-auto px-4 pb-12">
    <!-- Formations Grid -->
    <div v-if="formations.length > 0">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <FormationCard 
          v-for="formation in formations" 
          :key="formation.id"
          :formation="formation"
          @delete="$emit('delete', formation)"
        />
      </div>
      
      <!-- Pagination (Placeholder) -->
      <div class="mt-8 flex justify-center">
        <nav class="inline-flex items-center -space-x-px">
          <button class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-l-lg">
            <i class="fas fa-chevron-left text-xs"></i>
          </button>
          <button class="px-3 py-2 bg-blue-600 text-white border border-blue-600">1</button>
          <button class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50">2</button>
          <button class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50">3</button>
          <button class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-r-lg">
            <i class="fas fa-chevron-right text-xs"></i>
          </button>
        </nav>
      </div>
    </div>
    
    <!-- Empty State -->
    <div v-else-if="allFormationsCount === 0" class="bg-white rounded-xl shadow-sm p-10 text-center border border-gray-200">
      <div class="flex flex-col items-center max-w-md mx-auto">
        <div class="w-32 h-32 bg-blue-50 rounded-full flex items-center justify-center mb-6">
          <i class="fas fa-graduation-cap text-5xl text-blue-300"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-3">Commencez votre parcours d'enseignement</h2>
        <p class="text-gray-500 mb-8">Créez votre première formation pour partager vos connaissances et votre expertise avec votre public. Créez des cours complets et développez votre plateforme.</p>
        <Link 
          href="/formations/create" 
          class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all shadow-sm"
        >
          <i class="fas fa-plus mr-2"></i> Créez votre première formation
        </Link>
      </div>
    </div>
    
    <!-- No Results State -->
    <div v-else class="bg-white rounded-xl shadow-sm p-10 text-center border border-gray-200">
      <div class="flex flex-col items-center max-w-md mx-auto">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
          <i class="fas fa-search text-3xl text-gray-300"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">No formations found</h2>
        <p class="text-gray-500 mb-6">No formations match your current search criteria. Try adjusting your filters or search terms.</p>
        <button 
          @click="$emit('resetFilters')"
          class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all"
        >
          <i class="fas fa-redo-alt mr-2"></i> Reset Filters
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import FormationCard from './FormationCard.vue';

export default {
  components: {
    Link,
    FormationCard
  },
  
  props: {
    formations: {
      type: Array,
      required: true
    },
    allFormationsCount: {
      type: Number,
      required: true
    }
  },
  
  emits: ['delete', 'resetFilters']
}
</script>