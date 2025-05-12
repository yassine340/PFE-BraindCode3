<template>
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6">{{ formateur.first_name }} {{ formateur.last_name }}'s Statistics</h1>
      
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-700">Total Formations</h2>
          <p class="text-4xl font-bold text-blue-600 mt-2">{{ stats.totalFormations }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-700">Published Formations</h2>
          <p class="text-4xl font-bold text-green-600 mt-2">{{ stats.publishedFormations }}</p>
          <p class="text-sm text-gray-500 mt-2">
            {{ Math.round((stats.publishedFormations / stats.totalFormations) * 100) || 0 }}% of total
          </p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-700">Validated Formations</h2>
          <p class="text-4xl font-bold text-purple-600 mt-2">{{ stats.validatedFormations }}</p>
          <p class="text-sm text-gray-500 mt-2">
            {{ Math.round((stats.validatedFormations / stats.totalFormations) * 100) || 0 }}% of total
          </p>
        </div>
      </div>
      
      <!-- Latest Formations -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Latest Formations</h2>
        
        <div v-if="stats.latestFormations.length > 0">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-50">
                <th class="py-2 px-4 text-left">Title</th>
                <th class="py-2 px-4 text-left">Created At</th>
                <th class="py-2 px-4 text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="formation in stats.latestFormations" :key="formation.id" class="border-b border-gray-100">
                <td class="py-3 px-4">{{ formation.titre }}</td>
                <td class="py-3 px-4">{{ formatDate(formation.created_at) }}</td>
                <td class="py-3 px-4">
                  <Link :href="`/formations/${formation.id}`" class="text-blue-600 hover:underline">View</Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div v-else class="text-gray-500 py-4 text-center">
          No formations created yet.
        </div>
      </div>
      
      <!-- Navigation Links -->
      <div class="mt-8 flex flex-wrap gap-4">
        <Link 
          :href="`/formateurs/${formateur.id}/formations`"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
        >
          View All Formations
        </Link>
        
        <Link 
          :href="`/formateurs/${formateur.id}/formations?est_publiee=1`"
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
        >
          Published Formations
        </Link>
        
        <Link 
          :href="`/formateurs/${formateur.id}/formations?est_valide=Validée`"
          class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition"
        >
          Validated Formations
        </Link>
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
      stats: {
        type: Object,
        required: true
      }
    },
    
    methods: {
      formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric'
        });
      }
    }
  }
  </script>