<template>
    <section class="features-section bg-white py-12">
      <div class="container mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div 
            v-for="(feature, index) in features" 
            :key="index"
            class="feature-card-wrapper"
            :style="{ animationDelay: `${index * 200}ms` }"
          >
            <FeatureCard 
              :image="feature.image" 
              :title="feature.title" 
              :benefits="feature.benefits" 
            />
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import FeatureCard from './FeatureCard.vue';
  
  export default {
    name: 'FeaturesSection',
    components: {
      FeatureCard
    },
    data() {
      return {
        features: [
          {
            image: '/image/Investisseur/aaaa.png',
            title: 'Messagerie intégrée avec les startups',
            benefits: [
              'Communication directe avec les startups',
              'Échange sécurisé de documents (NDA, Business Plan)',
              'Historique des conversations'
            ]
          },
          {
            image: '/image/Investisseur/b.png',
            title: 'Gestion des demandes d\'investissement',
            benefits: [
              'Réception des demandes des startups',
              'Envoi de propositions personnalisées',
              'Suivi des négociations en cours'
            ]
          },
          {
            image: '/image/Investisseur/bb.png',
            title: 'Suivi des startups en portefeuille',
            benefits: [
              'Tableau de bord des startups',
              'Suivi des KPIs en temps réel',
              'Rapports d\'évolution périodiques'
            ]
          }
        ]
      }
    }
  }
  </script>
  
  <style scoped>
  .feature-card-wrapper {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.8s ease forwards;
  }
  
  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Add staggered animation for entire grid on mobile */
  @media (max-width: 768px) {
    .grid {
      perspective: 1000px;
    }
    
    .feature-card-wrapper {
      transform: translateY(50px) rotateX(10deg);
      opacity: 0;
      animation: mobileFadeIn 0.8s ease forwards;
    }
    
    @keyframes mobileFadeIn {
      to {
        opacity: 1;
        transform: translateY(0) rotateX(0);
      }
    }
  }
  </style>