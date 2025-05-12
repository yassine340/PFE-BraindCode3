<template>
    <section class="why-invest bg-blue-50 py-20 overflow-hidden">
      <div class="container mx-auto px-4 md:px-8">
        <h2 
          class="text-3xl font-bold text-blue-600 mb-12 text-center transform transition-all duration-700"
          :class="{ 'translate-y-0 opacity-100': isVisible, 'translate-y-10 opacity-0': !isVisible }"
        >
          Pourquoi investir avec BrainCode ?
        </h2>
        
        <div class="flex flex-col md:flex-row items-center justify-between">
          <div 
            class="investment-graphic mb-12 md:mb-0 md:w-2/5 transform transition-all duration-1000"
            :class="{ 'translate-x-0 opacity-100': isVisible, 'translate-x-full opacity-0': !isVisible }"
          >
            <div class="relative">
              <!-- Enhanced glow effect behind image -->
              <div class="absolute inset-0 bg-blue-300 rounded-full filter blur-xl opacity-20 animate-pulse-glow"></div>
              
              <!-- Money coins with individual animations -->
              <div class="relative h-96 mx-auto">
                <img 
                  src="/image/Investisseur/aaa.png" 
                  alt="Pièces empilées et graphique croissant" 
                  class="h-96 mx-auto drop-shadow-lg animate-float-slow" 
                />
                
                <!-- Animated particles -->
                <div v-for="i in 5" :key="'particle-'+i" 
                  class="absolute rounded-full bg-blue-500 opacity-70 particle" 
                  :class="`particle-${i}`">
                </div>
              </div>
              
              <!-- Enhanced animated chart line overlay -->
              <svg 
                class="absolute top-1/4 left-1/4 w-1/2 h-1/2 pointer-events-none" 
                viewBox="0 0 100 100" 
                xmlns="http://www.w3.org/2000/svg"
                :class="{ 'svg-line-animation': isVisible }"
              >
                <path 
                  d="M 0,80 Q 20,75 30,65 T 50,55 T 70,40 T 100,20" 
                  fill="none" 
                  stroke="#3B82F6" 
                  stroke-width="2" 
                  stroke-dasharray="200" 
                  stroke-dashoffset="200"
                />
                <!-- Add dots at key points -->
                <circle v-if="isVisible" class="animate-dot-1" cx="0" cy="80" r="2" fill="#3B82F6" />
                <circle v-if="isVisible" class="animate-dot-2" cx="30" cy="65" r="2" fill="#3B82F6" />
                <circle v-if="isVisible" class="animate-dot-3" cx="50" cy="55" r="2" fill="#3B82F6" />
                <circle v-if="isVisible" class="animate-dot-4" cx="70" cy="40" r="2" fill="#3B82F6" />
                <circle v-if="isVisible" class="animate-dot-5" cx="100" cy="20" r="2" fill="#3B82F6" />
              </svg>
            </div>
          </div>
          
          <div class="investment-reasons md:w-1/2">
            <div class="grid grid-cols-1 gap-6">
              <div 
                v-for="(reason, index) in reasons" 
                :key="index"
                class="transform transition-all duration-700"
                :class="{ 'translate-y-0 opacity-100': isVisible, 'translate-y-10 opacity-0': !isVisible }"
                :style="`transition-delay: ${300 + (index * 150)}ms`"
              >
                <AnimatedReasonCard 
                  :icon="reason.icon" 
                  :title="reason.title" 
                  :description="reason.description" 
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import ReasonCard from './ReasonCard.vue';
  
  export default {
    name: 'WhyInvestSection',
    components: {
      ReasonCard
    },
    data() {
      return {
        isVisible: false,
        reasons: [
          {
            icon: 'check',
            title: 'Accès à des startups qualifiées',
            description: 'Nous sélectionnons rigoureusement les startups les plus prometteuses pour vous garantir des opportunités de qualité.'
          },
          {
            icon: 'grid',
            title: 'Opportunités diversifiées',
            description: 'Un large éventail de secteurs d\'activité pour diversifier votre portefeuille d\'investissements.'
          },
          {
            icon: 'shield',
            title: 'Sécurisation de l\'investissement',
            description: 'Nous mettons en place des mécanismes de protection pour minimiser les risques liés à vos investissements.'
          },
          {
            icon: 'chart',
            title: 'Rentabilité et scalabilité',
            description: 'Nous ciblons des startups à fort potentiel de croissance pour maximiser votre retour sur investissement.'
          }
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
  // Animated reason card component with enhanced interactions
  const AnimatedReasonCard = {
    props: {
      icon: {
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
      }
    },
    data() {
      return {
        isHovered: false,
        isClicked: false
      }
    },
    methods: {
      getIconComponent() {
        switch(this.icon) {
          case 'check':
            return `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>`;
          case 'grid':
            return `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>`;
          case 'shield':
            return `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>`;
          case 'chart':
            return `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>`;
          default:
            return `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>`;
        }
      },
      handleClick() {
        this.isClicked = true;
        setTimeout(() => {
          this.isClicked = false;
        }, 500);
      }
    },
    template: `
      <div 
        class="bg-white p-6 rounded-lg shadow-md transition-all duration-300 relative overflow-hidden group"
        :class="{'shadow-lg transform scale-[1.02]': isHovered, 'animate-card-click': isClicked}"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        @click="handleClick"
      >
        <!-- Animated background gradient -->
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        
        <!-- Card shine effect on hover -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent shine-effect opacity-0 group-hover:opacity-30"></div>
        
        <div class="flex items-start relative z-10">
          <div 
            class="flex-shrink-0 rounded-full p-2 mr-4 transition-all duration-300"
            :class="{'bg-blue-100 text-blue-600 rotate-icon': isHovered, 'bg-blue-50 text-blue-500': !isHovered}"
          >
            <div v-html="getIconComponent()"></div>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-blue-800 mb-2 transition-colors duration-300" 
                :class="{'text-blue-600': isHovered}">{{ title }}</h3>
            <p class="text-gray-600 leading-relaxed transition-all duration-300"
               :class="{'text-gray-700': isHovered}">{{ description }}</p>
          </div>
        </div>
      </div>
    `
  }
  </script>
  
  <style scoped>
  /* Enhanced floating animation */
  @keyframes float-slow {
    0%, 100% {
      transform: translateY(0) rotate(0deg);
    }
    25% {
      transform: translateY(-10px) rotate(1deg);
    }
    50% {
      transform: translateY(-15px) rotate(0deg);
    }
    75% {
      transform: translateY(-5px) rotate(-1deg);
    }
  }
  
  .animate-float-slow {
    animation: float-slow 8s ease-in-out infinite;
  }
  
  /* Enhanced pulse glow animation */
  @keyframes pulse-glow {
    0%, 100% {
      opacity: 0.1;
      transform: scale(1);
    }
    50% {
      opacity: 0.3;
      transform: scale(1.1);
    }
  }
  
  .animate-pulse-glow {
    animation: pulse-glow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }
  
  /* Line animation */
  .svg-line-animation path {
    stroke-dasharray: 200;
    stroke-dashoffset: 200;
    animation: drawLine 2s ease forwards 0.5s;
  }
  
  @keyframes drawLine {
    to {
      stroke-dashoffset: 0;
    }
  }
  
  /* Dot animations on the chart */
  @keyframes dot-appear {
    0% {
      opacity: 0;
      r: 0;
    }
    100% {
      opacity: 1;
      r: 2;
    }
  }
  
  .animate-dot-1 { animation: dot-appear 0.4s ease forwards 0.5s; }
  .animate-dot-2 { animation: dot-appear 0.4s ease forwards 1s; }
  .animate-dot-3 { animation: dot-appear 0.4s ease forwards 1.5s; }
  .animate-dot-4 { animation: dot-appear 0.4s ease forwards 2s; }
  .animate-dot-5 { animation: dot-appear 0.4s ease forwards 2.5s; }
  
  /* Card click animation */
  @keyframes card-click {
    0% {
      transform: scale(1.02);
    }
    50% {
      transform: scale(0.98);
    }
    100% {
      transform: scale(1.02);
    }
  }
  
  .animate-card-click {
    animation: card-click 0.5s ease;
  }
  
  /* Icon rotation on hover */
  @keyframes rotate-icon {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  
  .rotate-icon {
    animation: rotate-icon 0.5s ease forwards;
  }
  
  /* Shine effect animation */
  @keyframes shine {
    0% {
      left: -100%;
    }
    100% {
      left: 100%;
    }
  }
  
  .shine-effect {
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    transform: skewX(-20deg);
  }
  
  .group:hover .shine-effect {
    animation: shine 1s ease;
  }
  
  /* Particle animations */
  .particle {
    position: absolute;
    width: 6px;
    height: 6px;
    opacity: 0;
  }
  
  @keyframes particle-float {
    0% {
      transform: translateY(0) scale(0);
      opacity: 0;
    }
    10% {
      opacity: 0.7;
    }
    90% {
      opacity: 0;
    }
    100% {
      transform: translateY(-50px) scale(1);
      opacity: 0;
    }
  }
  
  .particle-1 {
    bottom: 30%;
    left: 20%;
    animation: particle-float 3s ease-out infinite 0.2s;
  }
  
  .particle-2 {
    bottom: 40%;
    left: 50%;
    animation: particle-float 3.5s ease-out infinite 1s;
  }
  
  .particle-3 {
    bottom: 35%;
    right: 30%;
    animation: particle-float 3s ease-out infinite 1.5s;
  }
  
  .particle-4 {
    bottom: 25%;
    left: 40%;
    animation: particle-float 4s ease-out infinite 0.7s;
  }
  
  .particle-5 {
    bottom: 45%;
    right: 20%;
    animation: particle-float 3.2s ease-out infinite 2s;
  }
  </style>