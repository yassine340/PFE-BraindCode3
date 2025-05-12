<template>
  <section class="hero-section bg-gradient-to-r from-blue-50 to-indigo-50 py-16">
    <div class="container mx-auto hero-container px-4">
      <div class="hero-content flex flex-col md:flex-row items-center">
        <!-- Text Content with Sequential Animation -->
        <div class="hero-text md:w-1/2 text-left">
          <span 
            class="text-sm font-semibold text-blue-600 uppercase tracking-wider mb-2 block transform transition-all duration-700"
            :class="textVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
          >Programme de mentorat</span>
          
          <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 leading-tight">
            <div 
              v-for="(text, index) in animatedHeadingText" 
              :key="index"
              class="transform transition-all duration-700"
              :class="headingTextIndex > index ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0'"
            >
              <span :class="index === 1 ? 'text-blue-600' : ''">{{ text }}</span>
            </div>
          </h1>
          
          <p 
            class="text-gray-600 mb-8 text-lg leading-relaxed transform transition-all duration-700"
            :class="paragraphVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
          >
            BrainCode Startup Studio réunit des experts et mentors de haut niveau investis sur le projet pour maximiser les chances de succès des entrepreneurs.
          </p>
          
          <div
            class="transform transition-all duration-700"
            :class="buttonVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
          >
            <button class="bg-blue-600 text-white py-3 px-8 rounded-lg hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center group">
              <span>Devenir mentor</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Image with Animation and Loading Effects -->
        <div 
          class="hero-image md:w-1/2 flex justify-center mt-8 md:mt-0 transform transition-all duration-1000"
          :class="imageContainerVisible ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0'"
        >
          <div class="relative">
            <!-- Background glow effect -->
            <div class="absolute inset-0 bg-blue-400 rounded-full filter blur-xl opacity-20 animate-pulse"></div>
            
            <!-- Image with loading transition -->
            <div class="relative">
              <img 
                src="/api/placeholder/500/400" 
                alt="Expert mentor illustration placeholder"
                class="h-64 md:h-80 rounded-lg transition-opacity duration-500"
                :class="imageLoaded ? 'opacity-0 absolute' : 'opacity-100'"
              />
              <img 
                src="/image/coach/a.png" 
                alt="Expert mentor illustration" 
                class="h-64 md:h-80 rounded-lg transition-opacity duration-500 transform hover:scale-105 transition-transform filter drop-shadow-xl"
                :class="imageLoaded ? 'opacity-100' : 'opacity-0'"
                @load="imageLoaded = true"
              />
            </div>
            
            <!-- Floating decorative elements -->
            <div class="absolute -top-4 -right-4 w-12 h-12 bg-yellow-400 rounded-full opacity-80 animate-float-slow"></div>
            <div class="absolute bottom-4 -left-4 w-8 h-8 bg-blue-500 rounded-full opacity-80 animate-float-medium"></div>
            <div class="absolute top-1/2 -right-2 w-10 h-10 bg-green-400 rounded-full opacity-80 animate-float-fast"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'AnimatedHeroSection',
  data() {
    return {
      textVisible: false,
      headingTextIndex: 0,
      paragraphVisible: false,
      buttonVisible: false,
      imageContainerVisible: false,
      imageLoaded: false,
      animatedHeadingText: [
        "Encadré par les",
        "meilleurs experts",
        "et mentors"
      ]
    };
  },
  mounted() {
    // Start sequence of animations after component is mounted
    setTimeout(() => {
      this.textVisible = true;
    }, 300);
    
    // Sequential animation for heading text
    this.animatedHeadingText.forEach((_, index) => {
      setTimeout(() => {
        this.headingTextIndex = index + 1;
      }, 700 * (index + 1));
    });
    
    // Show paragraph after heading
    setTimeout(() => {
      this.paragraphVisible = true;
    }, 700 * (this.animatedHeadingText.length + 1));
    
    // Show button after paragraph
    setTimeout(() => {
      this.buttonVisible = true;
    }, 700 * (this.animatedHeadingText.length + 2));
    
    // Show image container last
    setTimeout(() => {
      this.imageContainerVisible = true;
    }, 700 * (this.animatedHeadingText.length + 3));
  }
};
</script>

<style>
/* Animation keyframes */
@keyframes float-slow {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

@keyframes float-medium {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-8px);
  }
}

@keyframes float-fast {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-12px);
  }
}

@keyframes pulse {
  0%, 100% {
    opacity: 0.15;
  }
  50% {
    opacity: 0.25;
  }
}

/* Animation classes */
.animate-float-slow {
  animation: float-slow 4s ease-in-out infinite;
}

.animate-float-medium {
  animation: float-medium 3s ease-in-out infinite;
}

.animate-float-fast {
  animation: float-fast 3.5s ease-in-out infinite;
}

.animate-pulse {
  animation: pulse 3s ease-in-out infinite;
}

/* Ensure hover transitions work */
.group:hover .group-hover\:translate-x-1 {
  transform: translateX(0.25rem);
}

.transition-transform {
  transition-property: transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
</style>