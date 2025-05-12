<template>
    <section class="investor-features-heading bg-white pt-20 pb-6">
      <div class="container mx-auto px-4 md:px-8">
        <!-- Title with enhanced animation -->
        <transition
          appear
          name="fade-down"
          enter-active-class="transform transition duration-1000 ease-out"
          enter-from-class="opacity-0 -translate-y-8"
          enter-to-class="opacity-100 translate-y-0"
        >
          <h2 class="text-3xl font-bold text-blue-600 text-center mb-4 hover:scale-105 transition-transform duration-300">
            {{ title }}
          </h2>
        </transition>
        
        <!-- Description with enhanced animation -->
        <transition
          appear
          name="fade-up"
          enter-active-class="transform transition duration-1000 ease-out delay-300"
          enter-from-class="opacity-0 translate-y-8"
          enter-to-class="opacity-100 translate-y-0"
        >
          <p class="text-gray-600 text-center max-w-2xl mx-auto hover:text-blue-500 transition-colors duration-300">
            {{ description }}
          </p>
        </transition>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'FeaturesHeading',
    props: {
      title: {
        type: String,
        default: 'Fonctionnalités dédiées aux investisseurs'
      },
      description: {
        type: String,
        default: 'Découvrez nos outils spécialement conçus pour optimiser votre expérience d\'investissement et suivre vos opportunités.'
      },
      features: {
        type: Array,
        default: () => [
          { icon: 'fas fa-chart-line', label: 'Analyse' },
          { icon: 'fas fa-wallet', label: 'Portfolio' },
          { icon: 'fas fa-bell', label: 'Alertes' },
          { icon: 'fas fa-file-alt', label: 'Rapports' }
        ]
      }
    },
  }
  </script>
  
  <style scoped>
  /* Base transitions provided in original code */
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
  }
  
  .pulse-animation {
    animation: pulse 2s ease-in-out infinite;
  }
  
  .slide-in-left {
    animation: slideFromLeft 0.8s ease-out forwards;
  }
  
  @keyframes slideFromLeft {
    0% { transform: translateX(-30px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
  }
  
  .slide-in-right {
    animation: slideFromRight 0.8s ease-out forwards;
  }
  
  @keyframes slideFromRight {
    0% { transform: translateX(30px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
  }
  
  /* New enhanced animations */
  .feature-icon {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease-out forwards;
  }
  
  @keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
  }
  
  /* Floating animation for elements */
  .floating {
    animation: floating 3s ease-in-out infinite;
  }
  
  @keyframes floating {
    0% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0); }
  }
  
  /* 3D rotation animation */
  @keyframes rotate-y {
    0% { transform: rotateY(0deg); }
    100% { transform: rotateY(360deg); }
  }
  
  .rotate-y-animation {
    animation: rotate-y 2s linear infinite;
  }
  
  /* Y-axis rotation */
  .hover\:rotate-y-180:hover {
    transform: rotateY(180deg);
  }
  
  /* Bounce animation for decorations */
  .animate-bounce {
    animation: bounce 1s infinite;
  }
  
  @keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
  
  /* Pulse animation */
  .animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }
  
  /* Ping animation */
  .animate-ping {
    animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
  }
  
  @keyframes ping {
    75%, 100% { transform: scale(1.2); opacity: 0; }
    0% { transform: scale(1); opacity: 1; }
  }
  </style>