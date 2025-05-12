// FilterSection.vue
<template>
  <div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-1 items-center">
          <div class="relative flex-1 max-w-lg">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <i class="fas fa-search text-gray-400"></i>
            </div>
            <input 
              type="text" 
              :value="searchTerm"
              @input="updateSearchTerm"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" 
              placeholder="recherche formations..."
            >
          </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Status:</label>
            <select 
              :value="statusFilter"
              @change="updateStatusFilter"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
            >
              <option value="all">Toute</option>
              <option value="published">publiee</option>
              <option value="draft">non publiee</option>
            </select>
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Trier:</label>
            <select 
              :value="sortBy"
              @change="updateSortBy"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
            >
              <option value="newest">Le plus récent</option>
              <option value="oldest">Le plus ancien</option>
              <option value="price-high">Prix: du plus élevé au plus bas</option>
              <option value="price-low">Prix: du plus bas au plus élevé</option>
            </select>
          </div>
          <button 
            @click="$emit('reset')" 
            class="text-sm text-gray-600 hover:text-blue-600"
          >
            <i class="fas fa-redo-alt mr-1"></i> Reset
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    searchTerm: {
      type: String,
      required: true
    },
    statusFilter: {
      type: String,
      required: true
    },
    sortBy: {
      type: String,
      required: true
    }
  },
  
  emits: ['update:searchTerm', 'update:statusFilter', 'update:sortBy', 'reset'],
  
  methods: {
    updateSearchTerm(event) {
      this.$emit('update:searchTerm', event.target.value);
    },
    updateStatusFilter(event) {
      this.$emit('update:statusFilter', event.target.value);
    },
    updateSortBy(event) {
      this.$emit('update:sortBy', event.target.value);
    }
  }
}
</script>