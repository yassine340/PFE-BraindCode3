// FormationsIndex.vue
<template>
  <AuthenticatedLayout>
    <div class="bg-gray-50 min-h-screen">
      <HeaderSection 
        :formationsCount="formations.length"
        :publishedCount="formations.filter(f => f.est_publiee).length"
        :unpublishedCount="formations.filter(f => !f.est_publiee).length"
        :totalModules="calculateTotalModules()"
      />
      
      <FilterSection 
        v-model:searchTerm="searchTerm"
        v-model:statusFilter="statusFilter"
        v-model:sortBy="sortBy"
        @reset="resetFilters"
      />
      
      <MainContent 
        :formations="filteredFormations"
        :allFormationsCount="formations.length"
        @delete="confirmDelete"
        @resetFilters="resetFilters"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import HeaderSection from '@/Components/HeaderSection.vue';
import FilterSection from '@/Components/FilterSection.vue';
import MainContent from '@/Components/MainContent.vue';

export default {
  components: {
    Link,
    AuthenticatedLayout,
    HeaderSection,
    FilterSection,
    MainContent
  },
  
  props: {
    formations: {
      type: Array,
      required: true
    }
  },
  
  setup(props) {
    // Logs de diagnostic améliorés
    onMounted(() => {
      console.group('📊 DIAGNOSTIC FORMATIONS');
      console.log('Formations brutes reçues du backend:', props.formations);
      console.log('Nombre de formations:', props.formations?.length || 0);
      
      if (props.formations.length === 0) {
        console.warn('⚠️ ALERTE: Aucune formation n\'a été reçue!');
      } else {
        // Examiner les propriétés de la première formation pour débogage
        const formation = props.formations[0];
        console.log('Exemple de formation:', formation);
        console.log('ID utilisateur de la formation:', formation?.user_id);
        console.log('Statut de publication:', formation?.est_publiee ? 'Publiée' : 'Brouillon');
        console.log('Statut de validation:', formation?.est_valide);
        
        // Analyse des images
        console.log('🖼️ Image originale:', formation?.image_formation);
        console.log('🖼️ URL d\'image générée:', formation?.image_url);
        
        // Détection S3
        const isS3 = formation?.image_url && 
                     (formation.image_url.includes('amazonaws.com') || 
                      formation.image_url.includes('s3.'));
        
        console.log('📎 Type d\'image:', isS3 ? 'AWS S3' : 'Locale/Externe');
      }
      
      // Vérification avancée des images S3
      if (props.formations.length > 0) {
        // Compter les images S3
        const s3Images = props.formations.filter(f => 
          f.image_url && (f.image_url.includes('amazonaws.com') || f.image_url.includes('s3.'))
        );
        
        console.log('🖼️ Résumé des images:');
        console.log(`   - Total formations: ${props.formations.length}`);
        console.log(`   - Images S3: ${s3Images.length}`);
        console.log(`   - Images locales/autres: ${props.formations.length - s3Images.length}`);
        
        // Afficher un exemple d'URL S3 pour vérification
        if (s3Images.length > 0) {
          console.log('📋 Exemple URL S3:', s3Images[0].image_url);
        }
        
        // Vérifier les formations non publiées
        const nonPubliees = props.formations.filter(f => !f.est_publiee);
        const valideesNonPubliees = props.formations.filter(f => f.est_valide === 'Validée' && !f.est_publiee);
        
        if (nonPubliees.length > 0) {
          console.log('⚠️ Formations non publiées:', nonPubliees.length);
        }
        
        if (valideesNonPubliees.length > 0) {
          console.log('ℹ️ Formations validées mais non publiées:', valideesNonPubliees.length);
        }
      }
      console.groupEnd();
    });
    
    // State
    const searchTerm = ref('');
    const statusFilter = ref('all');
    const sortBy = ref('newest');
    
    // Computed
    const filteredFormations = computed(() => {
      let result = [...props.formations];
      
      // Debug: Log avant le filtrage
      console.log('🔍 Filtrage: formations initiales:', result.length);
      
      // Apply search - with null checks to prevent errors
      if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase();
        result = result.filter(formation => {
          const titre = formation.titre ? formation.titre.toLowerCase() : '';
          const description = formation.description ? formation.description.toLowerCase() : '';
          
          return titre.includes(term) || description.includes(term);
        });
        console.log(`🔍 Après filtrage par terme "${searchTerm.value}":`, result.length);
      }
      
      // Apply status filter
      if (statusFilter.value === 'published') {
        result = result.filter(formation => formation.est_publiee);
        console.log('🔍 Après filtrage "publiées":', result.length);
      } else if (statusFilter.value === 'draft') {
        result = result.filter(formation => !formation.est_publiee);
        console.log('🔍 Après filtrage "brouillons":', result.length);
      }
      
      // Apply sorting - with error handling for date fields
      if (sortBy.value === 'newest') {
        result.sort((a, b) => {
          // Handle potential missing created_at fields
          if (!a.created_at) return 1;
          if (!b.created_at) return -1;
          return new Date(b.created_at) - new Date(a.created_at);
        });
      } else if (sortBy.value === 'oldest') {
        result.sort((a, b) => {
          if (!a.created_at) return 1;
          if (!b.created_at) return -1;
          return new Date(a.created_at) - new Date(b.created_at);
        });
      } else if (sortBy.value === 'price-high') {
        result.sort((a, b) => (b.prix || 0) - (a.prix || 0));
      } else if (sortBy.value === 'price-low') {
        result.sort((a, b) => (a.prix || 0) - (b.prix || 0));
      }
      
      console.log('🔍 Résultat final après filtrage/tri:', result.length);
      return result;
    });
    
    // Helper - Détectez si une image est une URL S3
    const isS3Image = (formation) => {
      return formation.image_url && 
             (formation.image_url.includes('amazonaws.com') || 
              formation.image_url.includes('s3.'));
    };
    
    // Methods
    const resetFilters = () => {
      searchTerm.value = '';
      statusFilter.value = 'all';
      sortBy.value = 'newest';
      console.log('🔄 Filtres réinitialisés');
    };
    
    const confirmDelete = (formation) => {
      if (confirm(`Are you sure you want to delete "${formation.titre || 'this formation'}"?`)) {
        // Here you would implement the actual deletion logic
        console.log(`Delete formation: ${formation.id}`);
      }
    };
    
    const calculateTotalModules = () => {
      return props.formations.reduce((total, formation) => {
        return total + (formation.modules_count || 0);
      }, 0);
    };
    
    return {
      searchTerm,
      statusFilter,
      sortBy,
      filteredFormations,
      resetFilters,
      confirmDelete,
      calculateTotalModules,
      isS3Image
    };
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

<style scoped>
/* Optional custom transitions */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Indication visuelle pour les images S3 */
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
</style>