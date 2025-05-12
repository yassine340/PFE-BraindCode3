// FormationCard.vue
<template>
  <div class="group bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200 hover:shadow-lg transition-all duration-300">
    <div class="relative">
      <!-- Image principale avec gestion S3 correcte -->
      <img 
        v-if="formation.image_url" 
        :src="formation.image_url"
        :alt="formation.titre || 'Formation'"
        class="w-full h-56 object-cover group-hover:opacity-95 transition-opacity"
        @error="handleImageError"
      >
      <div v-else class="w-full h-56 bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
        <i class="fas fa-graduation-cap text-5xl text-blue-300"></i>
      </div>
      
      <!-- Badge S3 si c'est une image S3 -->
      <div 
        v-if="isS3Image(formation)" 
        class="absolute top-3 left-3 bg-blue-500 text-white text-xs font-bold py-1 px-2 rounded-full"
      >
        S3
      </div>
      
      <!-- Status Tag -->
      <div class="absolute bottom-3 left-3">
        <span 
          v-if="formation.est_publiee" 
          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200"
        >
          <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full"></span>
          publiee
        </span>
        <span 
          v-else 
          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200"
        >
          <span class="w-2 h-2 mr-1.5 bg-yellow-500 rounded-full"></span>
          non publiee
        </span>
      </div>
      
      <!-- Validation Status -->
      <div class="absolute top-3 right-3">
        <span 
          v-if="formation.est_valide === 'Validée'" 
          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"
        >
          <i class="fas fa-shield-alt mr-1"></i> Validé
        </span>
        <span 
          v-else-if="formation.est_valide === 'En attente'" 
          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200"
        >
          <i class="fas fa-clock mr-1"></i> publiee
        </span>
        <span 
          v-else 
          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200"
        >
          <i class="fas fa-times-circle mr-1"></i> non publiee
        </span>
      </div>
      
      <!-- Action Menu (Desktop) -->
      <div class="absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity">
        <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow p-1 border border-gray-100">
          <div class="flex items-center space-x-1">
            <Link 
              :href="`/formations/${formation.id}/edit`"
              class="p-2 text-gray-700 hover:text-blue-600 rounded-md hover:bg-blue-50"
              title="Edit"
            >
              <i class="fas fa-edit"></i>
            </Link>
            <Link 
              :href="`/formations/${formation.id}`"
              class="p-2 text-gray-700 hover:text-blue-600 rounded-md hover:bg-blue-50"
              title="View"
            >
              <i class="fas fa-eye"></i>
            </Link>
            <button 
              class="p-2 text-gray-700 hover:text-red-600 rounded-md hover:bg-red-50"
              title="Delete"
              @click="deleteFormation"
            >
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="p-5">
      <h2 class="text-xl font-bold mb-2 text-gray-900 line-clamp-1 group-hover:text-blue-600 transition-colors">
        {{ formation.titre || 'Untitled Formation' }}
      </h2>
      
      <div class="flex flex-wrap items-center text-sm text-gray-500 mb-3 gap-x-4 gap-y-2">
        <span class="flex items-center">
          <i class="fas fa-clock mr-1.5 text-blue-500"></i>
          {{ formation.duree || 'N/A' }}
        </span>
        <span class="flex items-center">
          <i class="fas fa-layer-group mr-1.5 text-blue-500"></i>
          {{ formation.modules_count || 0 }} modules
        </span>
      </div>
      
      <p class="text-gray-600 mb-4 line-clamp-2 text-sm">
        {{ formation.description || 'No description available' }}
      </p>
      
      <div class="flex items-center justify-between pt-3 mt-1 border-t border-gray-100">
        <div class="flex items-center">
          <span class="text-lg font-bold text-blue-600">{{ formatPrice(formation.prix || 0) }}</span>
        </div>
        
        <!-- Mobile Actions -->
        <div class="flex items-center gap-2 md:hidden">
          <Link 
            :href="`/formations/${formation.id}/edit`"
            class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium"
          >
            <i class="fas fa-edit mr-1.5"></i> Edit
          </Link>
          <Link 
            :href="`/formations/${formation.id}`"
            class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium"
          >
            View
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import '@fortawesome/fontawesome-free/css/all.min.css';

export default {
  components: {
    Link
  },
  
  props: {
    formation: {
      type: Object,
      required: true
    }
  },
  
  methods: {
    deleteFormation() {
      if (confirm("Êtes-vous sûr de vouloir supprimer cette formation ? Cette action est irréversible.")) {
        this.$inertia.delete(`/formations/${this.formation.id}`, {
          onSuccess: () => alert("Formation supprimée avec succès !")
        });
      }
    },
    
    handleImageError(event) {
      console.error(`Failed to load image for formation: ${this.formation.id} - ${this.formation.titre || 'Untitled'}`);
      // Utiliser une image de fallback au lieu de simplement mettre image_url à null
      event.target.src = '/images/placeholder-formation.jpg';
    },
    
    formatPrice(price) {
      return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
      }).format(price);
    },
    
    // Méthode pour détecter si une image provient de S3
    isS3Image(formation) {
      return formation.image_url && 
             (formation.image_url.includes('amazonaws.com') || 
              formation.image_url.includes('s3.'));
    }
  }
}
</script>

<style scoped>
/* Style pour le badge S3 */
.s3-badge {
  position: absolute;
  top: 8px;
  right: 8px;
  background-color: #3498db;
  color: white;
  font-size: 0.7rem;
  padding: 2px 6px;
  border-radius: 4px;
  font-weight: bold;
}

/* Animation pour les images au survol */
img {
  transition: transform 0.3s ease;
}

.group:hover img {
  transform: scale(1.03);
}
</style>