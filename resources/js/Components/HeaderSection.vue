// HeaderSection.vue
<template>
  <div class="bg-white border-b border-gray-200 pb-6">
    <div class="container mx-auto px-4 pt-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Mes formations</h1>
          <p class="mt-2 text-sm text-gray-500">Gérez et créez votre contenu pédagogique</p>
        </div>
        <div class="mt-4 md:mt-0">
          <Link 
            href="/formations/create" 
            class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all shadow-sm"
          >
            <i class="fas fa-plus mr-2"></i> New Formation
          </Link>
        </div>
      </div>
      
      <!-- Quick Stats -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <StatCard 
          icon="fas fa-book" 
          label="Total Formations" 
          :value="formationsCount" 
          colorClass="blue"
        />
        <StatCard 
          icon="fas fa-check-circle" 
          label="publiee" 
          :value="publishedCount" 
          colorClass="green"
        />
        <StatCard 
          icon="fas fa-pencil-alt" 
          label="non publiee" 
          :value="unpublishedCount" 
          colorClass="yellow"
        />
        <StatCard 
          icon="fas fa-layer-group" 
          label="Total Modules" 
          :value="totalModules" 
          colorClass="purple"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import StatCard from './StatCard.vue';

export default {
  components: {
    Link,
    StatCard
  },
  
  props: {
    formationsCount: {
      type: Number,
      required: true
    },
    publishedCount: {
      type: Number,
      required: true
    },
    unpublishedCount: {
      type: Number,
      required: true
    },
    totalModules: {
      type: Number,
      required: true
    }
  }
}
</script>