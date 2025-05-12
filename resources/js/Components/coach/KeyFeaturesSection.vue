<template>
  <!-- Key Features Section - Enhanced with animations -->
  <section class="features-section bg-gray-50 py-16 overflow-hidden" ref="featureSection">
    <div class="container mx-auto container-context px-4">
      <!-- Animated section heading -->
      <div class="text-center mb-12">
        <span 
          class="text-sm font-semibold text-blue-600 uppercase tracking-wider block mb-2 transform transition-all duration-700"
          :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
        >
          Plateforme
        </span>
        
        <h2 
          class="text-2xl md:text-3xl font-bold text-gray-800 transform transition-all duration-700"
          :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
          :style="{ transitionDelay: '200ms' }"
        >
          Fonctionnalités Clés de l'Espace Coach
        </h2>
      </div>

      <!-- Animated feature grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="(feature, index) in features" 
          :key="index"
          class="transform transition-all duration-700"
          :class="isVisible ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-16 opacity-0 scale-95'"
          :style="{ transitionDelay: `${400 + index * 150}ms` }"
        >
          <PlatformFeature 
            :imageUrl="feature.imageUrl" 
            :title="feature.title" 
            :description="feature.description"
            :isVisible="isVisible"
            :animationDelay="600 + index * 150"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
// PlatformFeature component with internal animations
const PlatformFeature = {
  props: {
    imageUrl: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    description: {
      type: String,
      required: true
    },
    isVisible: {
      type: Boolean,
      default: false
    },
    animationDelay: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      imageLoaded: false
    }
  },
  template: `
    <div class="feature-card bg-white rounded-lg shadow-lg p-6 h-full hover:shadow-xl transition-shadow duration-300 relative overflow-hidden group">
      <!-- Animated subtle background gradient -->
      <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
      
      <!-- Icon/Image with animations -->
      <div class="icon-container relative mb-5 overflow-hidden">
        <div class="icon-background absolute inset-0 bg-blue-100 rounded-full scale-0 transition-transform duration-500"
             :class="isVisible ? 'scale-100' : 'scale-0'"
             :style="{ transitionDelay: animationDelay + 'ms' }"></div>
        
        <img 
          :src="imageUrl || '/api/placeholder/60/60'" 
          :alt="title"
          class="w-16 h-16 object-contain relative z-10 transform transition-all duration-700"
          :class="isVisible ? 'translate-y-0 opacity-100 rotate-0' : 'translate-y-8 opacity-0 rotate-12'"
          :style="{ transitionDelay: (animationDelay + 100) + 'ms' }"
          @load="imageLoaded = true"
        />
      </div>
      
      <!-- Animated text elements -->
      <h3 
        class="text-xl font-semibold mb-2 text-gray-800 relative z-10 transform transition-all duration-500"
        :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'"
        :style="{ transitionDelay: (animationDelay + 200) + 'ms' }"
      >
        {{ title }}
      </h3>
      
      <p 
        class="text-gray-600 relative z-10 transform transition-all duration-500"
        :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
        :style="{ transitionDelay: (animationDelay + 300) + 'ms' }"
      >
        {{ description }}
      </p>
      
      <!-- Animated hover indicator -->
      <div class="absolute bottom-3 right-3 w-6 h-6 flex items-center justify-center rounded-full bg-blue-500 opacity-0 transform scale-0 translate-x-2 translate-y-2 group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0 group-hover:translate-y-0 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </div>
    </div>
  `
};

export default {
  name: 'AnimatedKeyFeaturesSection',
  components: {
    PlatformFeature
  },
  data() {
    return {
      isVisible: false,
      features: [
        {
          imageUrl: "/image/coach/1.png",
          title: "Suivi & gestion des startups",
          description: "Suivez la progression des startups que vous accompagnez"
        },
        {
          imageUrl: "/image/coach/2.png",
          title: "Networking & mise en relation",
          description: "Connectez-vous avec l'écosystème entrepreneurial"
        },
        {
          imageUrl: "/image/coach/3.png",
          title: "Évaluation & validation",
          description: "Analysez et validez les modèles économiques"
        },
        {
          imageUrl: "/image/coach/4.png",
          title: "Planification des sessions",
          description: "Organisez vos séances de coaching efficacement"
        }
      ]
    };
  },
  mounted() {
    // Use Intersection Observer to trigger animations when in viewport
    this.setupIntersectionObserver();
    
    // Fallback for browsers without IntersectionObserver
    setTimeout(() => {
      if (!this.isVisible) {
        this.isVisible = true;
      }
    }, 300);
  },
  methods: {
    setupIntersectionObserver() {
      if ('IntersectionObserver' in window && this.$refs.featureSection) {
        const options = {
          root: null,
          rootMargin: '0px',
          threshold: 0.15
        };
        
        const observer = new IntersectionObserver((entries) => {
          if (entries[0].isIntersecting) {
            this.isVisible = true;
            observer.disconnect();
          }
        }, options);
        
        // Use the ref to get the actual DOM element
        observer.observe(this.$refs.featureSection);
      } else {
        // If no refs or no IntersectionObserver, make it visible
        this.isVisible = true;
      }
    }
  }
};
</script>

<style scoped>
.features-section {
  position: relative;
}

.features-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.3), transparent);
}

.icon-container {
  width: 64px;
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.icon-background {
  transform-origin: center;
}

/* Optional: Add a subtle floating animation to icons */
.feature-card:hover img {
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-5px);
  }
}

/* Optional: Add wave animation to section top */
.features-section::after {
  content: '';
  position: absolute;
  top: -2px;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, transparent, #3B82F6, transparent);
  opacity: 0.5;
  animation: wave 8s infinite linear;
}

@keyframes wave {
  0% {
    background-position: 0% 0;
  }
  100% {
    background-position: 100% 0;
  }
}

/* For smaller screens adjust animations as needed */
@media (max-width: 768px) {
  .icon-container {
    margin: 0 auto 1rem;
  }
}
</style>