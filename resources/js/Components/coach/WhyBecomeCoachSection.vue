<template>
    <!-- Why Become a Coach Section - Improved cards with hover effects -->
    <section class="why-section bg-white py-16">
      <div class="container mx-auto container-context px-4">
        <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider text-center mb-2 block">Opportunités</span>
        <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-6">
          Pourquoi Devenir Coach sur <span class="text-blue-600">Braincode Startup Studio</span> ?
        </h2>
        <p class="text-center mb-12 text-gray-600 max-w-2xl mx-auto">Partagez votre expertise, écoutez les entrepreneurs et façonnez l'avenir des startups !</p>
  
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <FeatureCard 
            icon="bolt" 
            title="Aidez les startups à réussir"
            description="Apportez votre savoir-faire aux jeunes entrepreneurs et contribuez à leur succès"
          />
          <FeatureCard 
            icon="eye" 
            title="Boostez votre visibilité"
            description="Faites-vous connaître auprès de l'écosystème d'innovateurs et élargissez votre réseau"
          />
          <FeatureCard 
            icon="money" 
            title="Monétisez votre expertise"
            description="Recevez des commissions sur les succès commerciaux des startups que vous accompagnez"
          />
          <FeatureCard 
            icon="network" 
            title="Accédez à un réseau dynamique"
            description="Échangez avec d'autres experts et saisissez de nouvelles opportunités de collaboration"
          />
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import FeatureCard from './FeatureCard.vue';
  
  export default {
    name: 'WhyBecomeCoachSection',
    components: {
      FeatureCard
    }
  }
  </script>