<template>
    <section class="py-16 md:py-24 overflow-hidden" style="background: linear-gradient(to bottom, #F1F9FF, #FFFFFF)">
      <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-10 md:mb-0">
          <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
            <div 
              v-for="(text, index) in animatedTexts" 
              :key="index"
              :class="`transform transition-all duration-700 ${
                textIndex > index 
                  ? 'translate-x-0 opacity-100' 
                  : 'translate-x-full opacity-0'
              }`"
            >
              <span 
                :class="index === 2 ? 'text-blue-500' : 'text-custom-blue'"
                :style="{color: index === 2 ? '#3B82F6' : '#003E64'}"
              >
                {{ text }}
              </span>
              <br />
            </div>
          </h1>
          
          <p 
            :class="`text-lg mb-8 transform transition-all duration-700 ${
              textIndex > 2 ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
            }`"
          >
            Explorez l'innovation, accompagnez des entrepreneurs passionnés et bénéficiez d'une croissance durable avec BrainCode startups.
          </p>
  
          <div 
            :class="`flex space-x-4 transform transition-all duration-700 ${
              buttonVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
            }`"
          >
            <button class="group inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
              Rejoindre maintenant
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 transition-transform group-hover:translate-x-1">
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
              </svg>
            </button>
            <button class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-medium transition duration-300 transform hover:scale-105">En savoir plus</button>
          </div>
        </div>
        
        <div 
          :class="`md:w-1/2 transform transition-all duration-1000 ${
            isVisible ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0'
          }`"
        >
          <div class="relative">
            <!-- Pulse effect behind image -->
            <div class="absolute inset-0 bg-blue-400 rounded-full filter blur-xl opacity-20 animate-pulse"></div>
            
            <!-- Image with loading placeholder -->
            <div class="relative">
              <img 
                src="/api/placeholder/500/400" 
                alt="Investisseur placeholder"
                :class="`max-w-full h-auto rounded-lg transition-opacity duration-500 ${imageLoaded ? 'opacity-0 absolute' : 'opacity-100'}`"
              />
              <img 
                src="/image/Investisseur/aa.png" 
                alt="Personne debout sur une pièce d'or" 
                :class="`max-w-full h-auto rounded-lg transition-opacity duration-500 ${imageLoaded ? 'opacity-100' : 'opacity-0'}`"
                @load="imageLoaded = true"
              />
            </div>
            
            <!-- Floating decorative elements -->
            <div class="absolute -top-4 -right-4 w-12 h-12 bg-yellow-400 rounded-full opacity-80" style="animation: bounce 3s infinite"></div>
            <div class="absolute bottom-4 -left-4 w-8 h-8 bg-blue-500 rounded-full opacity-80" style="animation: bounce 2.5s 0.5s infinite"></div>
            <div class="absolute top-1/2 -right-2 w-10 h-10 bg-green-400 rounded-full opacity-80" style="animation: bounce 4s 1s infinite"></div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'HeroSection',
    data() {
      return {
        isVisible: false,
        textIndex: 0,
        buttonVisible: false,
        imageLoaded: false,
        animatedTexts: [
          "Investissez dans les",
          "startups de",
          "demain"
        ]
      };
    },
    mounted() {
      // Trigger animation after page loads
      this.isVisible = true;
      
      // Sequential text animation
      this.animatedTexts.forEach((_, index) => {
        setTimeout(() => {
          this.textIndex = index + 1;
        }, 700 * (index + 1));
      });
      
      // Show button after text animation completes
      setTimeout(() => {
        this.buttonVisible = true;
      }, 700 * (this.animatedTexts.length + 1));
    }
  };
  </script>
  
  <style>
  .text-custom-blue {
    color: #003E64;
  }
  
  @keyframes bounce {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-10px);
    }
  }
  
  .animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }
  
  @keyframes pulse {
    0%, 100% {
      opacity: 0.2;
    }
    50% {
      opacity: 0.3;
    }
  }
  
  /* Ensure hover transitions work */
  .group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
  }
  
  .hover\:scale-105:hover {
    transform: scale(1.05);
  }
  
  .transition {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }
  
  .duration-700 {
    transition-duration: 700ms;
  }
  
  .duration-1000 {
    transition-duration: 1000ms;
  }
  
  .duration-500 {
    transition-duration: 500ms;
  }
  </style>