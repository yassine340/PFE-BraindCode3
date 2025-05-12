<template>
  <section class="features-section">
    <div class="container">
      <div class="features-grid">
        <div 
          v-for="(feature, index) in features" 
          :key="index"
          class="feature-item-wrapper"
          :class="{'animate-in': featuresVisible}"
          :style="{ transitionDelay: `${index * 150}ms` }"
        >
          <FeatureItem :text="feature" />
        </div>
      </div>
        
      <div 
        class="button-container"
        :class="{'animate-in': buttonVisible}"
      >
        <a href="#" class="cta-button group">
          Postuler en tant que coach
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            class="inline-block h-5 w-5 ml-2 transform transition-transform group-hover:translate-x-1" 
            viewBox="0 0 20 20" 
            fill="currentColor"
          >
            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </a>
      </div>
    </div>
  </section>
</template>

<script>
import FeatureItem from './FeatureItem.vue';

export default {
  name: 'AnimatedFeaturesSection',
  components: {
    FeatureItem
  },
  data() {
    return {
      features: [
        'Partagez votre savoir-faire avec des entrepreneurs ambitieux.',
        'Accédez à des événements et collaborations exclusives.',
        'Développement de votre expertise et réseau professionnel.',
        'Opportunités de collaboration avec des startups innovantes.',
        'Rémunération attractive basée sur votre contribution.',
        'Flexibilité dans votre engagement et vos horaires.'
      ],
      featuresVisible: false,
      buttonVisible: false
    }
  },
  mounted() {
    // Use Intersection Observer to trigger animations when in viewport
    this.setupIntersectionObserver();
    
    // Fallback for browsers without IntersectionObserver support
    setTimeout(() => {
      if (!this.featuresVisible) {
        this.featuresVisible = true;
        
        setTimeout(() => {
          this.buttonVisible = true;
        }, this.features.length * 150 + 300);
      }
    }, 500);
  },
  methods: {
    setupIntersectionObserver() {
      // Check if IntersectionObserver is available
      if ('IntersectionObserver' in window) {
        const options = {
          root: null,
          rootMargin: '0px',
          threshold: 0.2
        };
        
        const observer = new IntersectionObserver((entries) => {
          if (entries[0].isIntersecting) {
            this.featuresVisible = true;
            
            // Animate button after all features are shown
            setTimeout(() => {
              this.buttonVisible = true;
            }, this.features.length * 150 + 300);
            
            // Stop observing once animation has started
            observer.disconnect();
          }
        }, options);
        
        // Start observing the section
        observer.observe(this.$el);
      }
    }
  }
}
</script>

<style scoped>
.features-section {
  padding: 60px 0;
  background-color: white;
  overflow: hidden; /* Prevent animations from causing horizontal scrolling */
}

/* Container for better spacing */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Features Grid Layout */
.features-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 30px 60px;
  margin-bottom: 50px;
}

/* Animation container for each feature */
.feature-item-wrapper {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.feature-item-wrapper.animate-in {
  opacity: 1;
  transform: translateY(0);
}

/* Button Container */
.button-container {
  display: flex;
  justify-content: center;
  margin-top: 40px;
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.8s ease, transform 0.8s ease;
}

.button-container.animate-in {
  opacity: 1;
  transform: translateY(0);
}

/* CTA Button */
.cta-button {
  display: inline-flex;
  align-items: center;
  background-color: #0099ff;
  color: white;
  font-weight: 500;
  padding: 14px 28px;
  border-radius: 50px;
  text-decoration: none;
  text-align: center;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  font-size: 16px;
  box-shadow: 0 4px 6px rgba(0, 153, 255, 0.2);
}

.cta-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.7s ease;
}

.cta-button:hover {
  background-color: #007ecc;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 153, 255, 0.3);
}

.cta-button:hover::before {
  left: 100%;
}

/* Group hover for the arrow icon */
.group {
  position: relative;
}

.group-hover\:translate-x-1 {
  transition: transform 0.3s ease;
}

.group:hover .group-hover\:translate-x-1 {
  transform: translateX(4px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .features-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
  
  .cta-button {
    padding: 12px 24px;
    font-size: 15px;
  }
}
</style>