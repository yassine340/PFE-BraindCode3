<template>
    <section class="bg-white py-12">
      <div class="container mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div 
            v-for="(stat, index) in stats" 
            :key="index"
            :class="`transform transition-all duration-700 ${
              isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
            }`"
            :style="`transition-delay: ${index * 150}ms`"
          >
            <AnimatedStatCard 
              :number="stat.number" 
              :description="stat.description" 
              :active="isVisible" 
              :delay="index * 150"
            />
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import StatCard from './StatCard.vue';
  
  export default {
    name: 'StatsSection',
    components: {
      StatCard
    },
    data() {
      return {
        isVisible: false,
        stats: [
          { number: '150+', description: 'Startups accompagnées' },
          { number: '€25M', description: 'Investis au total' },
          { number: '92%', description: 'Taux de réussite' },
          { number: '400+', description: 'Investisseurs actifs' }
        ]
      }
    },
    mounted() {
      // Use intersection observer to trigger animations when section comes into view
      const observer = new IntersectionObserver(
        (entries) => {
          if (entries[0].isIntersecting) {
            this.isVisible = true;
            observer.disconnect();
          }
        },
        { threshold: 0.2 }
      );
      
      observer.observe(this.$el);
    }
  }
  </script>
  
  <script setup>
  // AnimatedStatCard component (to be used within the main component)
  const AnimatedStatCard = {
    props: {
      number: {
        type: String,
        required: true
      },
      description: {
        type: String,
        required: true
      },
      active: {
        type: Boolean,
        default: false
      },
      delay: {
        type: Number,
        default: 0
      }
    },
    data() {
      return {
        displayNumber: '0',
        animationStarted: false
      }
    },
    watch: {
      active(newVal) {
        if (newVal && !this.animationStarted) {
          setTimeout(() => {
            this.animateNumber();
          }, this.delay);
        }
      }
    },
    methods: {
      animateNumber() {
        this.animationStarted = true;
        
        // Parse the number part and the suffix part (if any)
        const numStr = this.number.replace(/[^\d.]/g, '');
        const suffix = this.number.replace(/[\d.]/g, '');
        
        const num = parseFloat(numStr);
        const duration = 1500; // Animation duration in ms
        const steps = 20; // Number of steps in the animation
        const increment = num / steps;
        let current = 0;
        let step = 0;
        
        const timer = setInterval(() => {
          step++;
          current = step * increment;
          
          if (current > num) current = num;
          
          // Format the number
          if (Number.isInteger(num)) {
            this.displayNumber = Math.floor(current).toString() + suffix;
          } else {
            // Handle decimal places
            const decimalPlaces = numStr.includes('.') ? numStr.split('.')[1].length : 0;
            this.displayNumber = current.toFixed(decimalPlaces) + suffix;
          }
          
          if (step >= steps) {
            clearInterval(timer);
            this.displayNumber = this.number; // Ensure we end with the exact target number
          }
        }, duration / steps);
      }
    },
    template: `
      <div class="bg-blue-50 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 text-center relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-100 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10">
          <h3 class="text-4xl font-bold text-blue-600 mb-2">{{ displayNumber }}</h3>
          <p class="text-gray-700">{{ description }}</p>
        </div>
      </div>
    `
  }
  </script>
  
  <style scoped>
  .stats-card-enter-active,
  .stats-card-leave-active {
    transition: all 0.8s ease;
  }
  
  .stats-card-enter-from,
  .stats-card-leave-to {
    opacity: 0;
    transform: translateY(30px);
  }
  </style>