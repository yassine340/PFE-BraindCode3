<template>
    <section class="py-16 bg-white overflow-hidden relative">
      <!-- Background animated elements -->
      <div class="absolute inset-0 overflow-hidden opacity-5 pointer-events-none">
        <div v-for="i in 6" :key="`bg-shape-${i}`" 
             class="absolute rounded-full bg-blue-500 animate-float-circles"
             :class="`bg-shape-${i}`"></div>
      </div>
      
      <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
        <!-- Title with animated entrance -->
        <div 
          v-intersect="onIntersect"
          class="flex items-center justify-center mb-4 transition-all duration-700 transform"
          :class="{'opacity-100 translate-y-0': isVisible, 'opacity-0 translate-y-10': !isVisible}"
        >
          <h2 class="text-3xl font-bold text-blue-600 text-center mb-4">
            {{ title }}
          </h2>
          <div class="relative ml-3">
            <img 
              src="/image/startup/rocket-icon.png" 
              alt="Rocket icon" 
              class="w-10 h-10 transition-all duration-500 transform"
              :class="{'animate-rocket': isVisible}"
            />
            <!-- Rocket flame animation -->
            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-4 h-6 rocket-flame opacity-0"
                 :class="{'opacity-100': isVisible}"></div>
          </div>
        </div>
        
        <!-- Description with animated entrance -->
        <p 
          class="text-lg text-gray-700 max-w-3xl mx-auto mb-8 transition-all duration-700 delay-300 transform"
          :class="{'opacity-100 translate-y-0': isVisible, 'opacity-0 translate-y-10': !isVisible}"
        >
          {{ description }}
        </p>
        
        <!-- Button with animations -->
        <button 
          class="relative bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg 
                 transition-all duration-300 hover:shadow-lg overflow-hidden button-pulse"
          :class="{'btn-visible': isVisible, 'opacity-0 translate-y-10': !isVisible}"
          @mouseenter="startButtonAnimation"
          @mouseleave="stopButtonAnimation"
        >
          <span 
            class="relative z-10 transition-transform duration-300"
            :class="{'transform scale-110': isButtonHovered}"
          >{{ buttonText }}</span>
          
          <!-- Button ripple effect -->
          <span class="absolute inset-0 bg-blue-500 btn-ripple" ref="btnRipple"></span>
        </button>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'CallToAction',
    props: {
      title: {
        type: String,
        default: 'Rejoignez notre réseau d\'investisseurs !'
      },
      description: {
        type: String,
        default: 'Prêt à investir dans des startups prometteuses et à accompagner l\'innovation ? Rejoignez-nous dès maintenant pour découvrir nos opportunités d\'investissement.'
      },
      buttonText: {
        type: String,
        default: 'Postuler en tant que investisseur'
      }
    },
    data() {
      return {
        isVisible: false,
        isButtonHovered: false,
        observer: null
      }
    },
    mounted() {
      // Use Intersection Observer API for triggering animations
      this.observer = new IntersectionObserver(
        (entries) => {
          if (entries[0].isIntersecting) {
            this.isVisible = true;
            this.observer.disconnect();
          }
        }, 
        { threshold: 0.2 }
      );
      
      this.observer.observe(this.$el);
    },
    beforeUnmount() {
      if (this.observer) {
        this.observer.disconnect();
      }
    },
    methods: {
      onIntersect(entries) {
        if (entries[0].isIntersecting) {
          this.isVisible = true;
        }
      },
      startButtonAnimation() {
        this.isButtonHovered = true;
        
        // Create ripple effect
        if (this.$refs.btnRipple) {
          this.$refs.btnRipple.classList.add('ripple-active');
        }
      },
      stopButtonAnimation() {
        this.isButtonHovered = false;
        
        // Remove ripple effect
        if (this.$refs.btnRipple) {
          this.$refs.btnRipple.classList.remove('ripple-active');
        }
      }
    }
  }
  </script>
  
  <style scoped>
  /* Rocket animation */
  @keyframes rocket-float {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-8px);
    }
  }
  
  .animate-rocket {
    animation: rocket-float 2s ease-in-out infinite;
  }
  
  /* Rocket flame */
  .rocket-flame {
    background: linear-gradient(to bottom, #FF9500, #FF5E3A, transparent);
    clip-path: polygon(0% 0%, 100% 0%, 80% 100%, 20% 100%);
    transform-origin: top center;
    animation: flame 0.5s linear infinite alternate;
    transition: opacity 0.5s ease;
  }
  
  @keyframes flame {
    0% {
      transform: scaleY(0.8) scaleX(0.9);
    }
    100% {
      transform: scaleY(1) scaleX(1.1);
    }
  }
  
  /* Button effects */
  .button-pulse {
    transition: all 0.3s ease;
  }
  
  .btn-visible {
    opacity: 1;
    transform: translateY(0);
    transition: opacity 0.7s ease 0.6s, transform 0.7s ease 0.6s;
  }
  
  .button-pulse:hover {
    box-shadow: 0 0 0 rgba(59, 130, 246, 0.6);
    animation: pulse 1.5s infinite;
  }
  
  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.6);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
    }
  }
  
  /* Button ripple effect */
  .btn-ripple {
    transform: scale(0);
    opacity: 0.3;
    border-radius: 100%;
    transition: transform 0.6s ease-out, opacity 0.6s ease;
  }
  
  .ripple-active {
    transform: scale(2.5);
    opacity: 0;
  }
  
  /* Background floating circles */
  .bg-shape-1, .bg-shape-2, .bg-shape-3, .bg-shape-4, .bg-shape-5, .bg-shape-6 {
    position: absolute;
    border-radius: 50%;
  }
  
  .bg-shape-1 {
    width: 100px;
    height: 100px;
    left: 5%;
    top: 20%;
    animation-delay: 0s;
  }
  
  .bg-shape-2 {
    width: 150px;
    height: 150px;
    right: 10%;
    top: 10%;
    animation-delay: 0.5s;
  }
  
  .bg-shape-3 {
    width: 80px;
    height: 80px;
    left: 15%;
    bottom: 15%;
    animation-delay: 1s;
  }
  
  .bg-shape-4 {
    width: 120px;
    height: 120px;
    right: 15%;
    bottom: 20%;
    animation-delay: 1.5s;
  }
  
  .bg-shape-5 {
    width: 60px;
    height: 60px;
    left: 50%;
    top: 5%;
    animation-delay: 2s;
  }
  
  .bg-shape-6 {
    width: 90px;
    height: 90px;
    right: 30%;
    bottom: 5%;
    animation-delay: 2.5s;
  }
  
  @keyframes float-circles {
    0%, 100% {
      transform: translateY(0) scale(1);
      opacity: 0.5;
    }
    50% {
      transform: translateY(-15px) scale(1.05);
      opacity: 0.7;
    }
  }
  
  .animate-float-circles {
    animation: float-circles 8s ease-in-out infinite;
  }
  </style>