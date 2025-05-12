<template>
    <Head title="Startup Workspace" />
    <AppHeader :canLogin="true" :canRegister="true" />
    <div class="landing-page">
      <!-- Hero Section -->
        <section class="hero relative w-full h-80 flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" 
                style="background-image: url('image/startup2/1.jpeg'); filter: blur(8px); transform: scale(1.1);"></div>
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="hero-content text-center text-white p-8 z-10 relative">
                <h1 class="text-2xl font-bold mb-2">Un espace de travail immersif en 3D</h1>
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">En savoir plus</button>
            </div>
        </section>
  
      <!-- Structured Process Section -->
      <section class="process-section py-12 px-4">
        <h2 class="text-xl font-bold text-center mb-8">Un accompagnement structuré en 12 étapes</h2>
        
        <div class="timeline-container max-w-4xl mx-auto">
            <div class="timeline-chart">
            <!-- Top row texts -->
            <div class="flex justify-between mb-2">
                <div v-for="i in 6" :key="`top-text-${i}`" class="text-center w-1/6">
                <div class="text-xs text-blue-700">{{ getTimelineStep(i) }}</div>
                </div>
            </div>
            
            <!-- Timeline structure -->
            <div class="relative">
                <!-- Top row dots with vertical lines -->
                <div class="flex justify-between mb-0">
                <div v-for="i in 6" :key="`top-${i}`" class="timeline-point flex flex-col items-center">
                    <div class="timeline-dot bg-blue-300 w-4 h-4 rounded-full"></div>
                    <div class="timeline-line bg-blue-300 w-px h-20"></div>
                </div>
                </div>
                
                <!-- Horizontal connector line -->
                <div class="absolute left-0 right-0 bottom-24 h-px bg-blue-300"></div>
                
                <!-- Bottom vertical lines with dots -->
                <div class="flex justify-between mt-0">
                <div v-for="i in 6" :key="`bottom-${i}`" class="timeline-point flex flex-col items-center">
                    <div class="timeline-line bg-blue-300 w-px h-20"></div>
                    <div class="timeline-dot bg-blue-300 w-4 h-4 rounded-full"></div>
                </div>
                </div>
            </div>
            
            <!-- Bottom row texts -->
            <div class="flex justify-between mt-2">
                <div v-for="i in 6" :key="`bottom-text-${i}`" class="text-center w-1/6">
                <div class="text-xs text-blue-700">{{ getTimelineStep(i + 6) }}</div>
                </div>
            </div>
            </div>
            
            <p class="text-center text-sm mt-8 max-w-2xl mx-auto">
            Le programme vous offre une progression méthodique tout au long de votre parcours et adapté au rythme de votre évolution. À chaque module correspond un espace, un objectif et une ressource pédagogique adaptée.
            </p>
            
            <div class="text-center mt-4">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Commencer</button>
            </div>
        </div>
        </section>
  
      <!-- Coaching Section -->
      <section class="coaching-section py-12 px-4 bg-gray-50">
        <h2 class="text-xl font-bold text-center mb-8">Coaching et mentorat personnalisé</h2>
        <p class="text-center mb-8">Nos experts sont à votre disposition pour un accompagnement personnalisé.</p>
        
        <div class="coaches-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 max-w-6xl mx-auto">
          <div v-for="coach in coaches" :key="coach.id" class="coach-card bg-white rounded shadow p-4 flex flex-col items-center">
            <img :src="coach.image" :alt="coach.name" class="w-32 h-32 object-cover rounded-full mb-4">
            <h3 class="font-bold">{{ coach.name }}</h3>
            <p class="text-gray-600 text-sm mb-4">{{ coach.specialty }}</p>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mt-auto">
              Prendre rendez-vous
            </button>
          </div>
        </div>
      </section>
  
      <!-- AI Agent Section -->
      <section class="ai-section py-12 px-4">
        <h2 class="text-xl font-bold text-center mb-8">Agent IA</h2>
        
        <div class="ai-content flex flex-col md:flex-row items-center max-w-4xl mx-auto">
          <div class="ai-image w-full md:w-1/3 flex justify-center mb-8 md:mb-0">
            <img src="image/startup2/4.png" alt="Agent IA" class="w-48">
          </div>
          
          <div class="ai-description w-full md:w-2/3">
            <p class="text-center md:text-left mb-6">
              Bénéficiez d'un assistant virtuel toujours à votre écoute pour vous guider, vous aider dans vos requêtes.
              Accessible à tout moment, notre IA vous accompagne pour optimiser votre parcours d'apprentissage et maximiser vos résultats.
            </p>
            
            <div class="ai-features grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="feature in aiFeatures" :key="feature.title" class="feature-card border border-blue-300 rounded p-4">
                <h4 class="font-semibold mb-2">{{ feature.title }}</h4>
                <p class="text-sm">{{ feature.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Features Section -->
<section class="features-section py-12 px-4 bg-blue-50">
  <div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- Feature Card 1 -->
      <div class="feature-card bg-white rounded-lg shadow-md overflow-hidden">
        <div class="h-2 bg-blue-500"></div>
        <div class="p-6 flex flex-col items-center text-center">
          <div class="icon-container mb-4">
            <img src="image/startup2/a.gif" alt="Suivi" class="h-16">
          </div>
          <h3 class="text-xl font-bold text-blue-600 mb-3">Suivi et Ajustement Continu</h3>
          <p class="text-gray-700">
            Analysez les performances de votre startup en temps réel grâce à des indicateurs clés. Ajustez votre stratégie en fonction des données et optimisez votre croissance à chaque étape.
          </p>
        </div>
      </div>
      
      <!-- Feature Card 2 -->
      <div class="feature-card bg-white rounded-lg shadow-md overflow-hidden">
        <div class="h-2 bg-blue-500"></div>
        <div class="p-6 flex flex-col items-center text-center">
          <div class="icon-container mb-4">
            <img src="image/startup2/aa.gif" alt="Système" class="h-16">
          </div>
          <h3 class="text-xl font-bold text-blue-600 mb-3">Système de Recommandation Intelligent</h3>
          <p class="text-gray-700">
            Profitez d'un algorithme avancé qui vous suggère les meilleures ressources, formations et experts en fonction de vos besoins et de votre progression.
          </p>
        </div>
      </div>
      
      <!-- Feature Card 3 -->
      <div class="feature-card bg-white rounded-lg shadow-md overflow-hidden">
        <div class="h-2 bg-blue-500"></div>
        <div class="p-6 flex flex-col items-center text-center">
          <div class="icon-container mb-4">
            <img src="image/startup2/aaa.gif" alt="Accompagnement" class="h-16">
          </div>
          <h3 class="text-xl font-bold text-blue-600 mb-3">Accompagnement en Temps Réel</h3>
          <p class="text-gray-700">
            Bénéficiez d'un soutien instantané grâce aux coachs et outils intelligents. Obtenez des réponses rapides et des conseils adaptés à chaque étape de votre parcours entrepreneurial.
          </p>
        </div>
      </div>
      
      <!-- Feature Card 4 -->
      <div class="feature-card bg-white rounded-lg shadow-md overflow-hidden">
        <div class="h-2 bg-blue-500"></div>
        <div class="p-6 flex flex-col items-center text-center">
          <div class="icon-container mb-4">
            <img src="image/startup2/aaaa.gif" alt="Guide" class="h-16">
          </div>
          <h3 class="text-xl font-bold text-blue-600 mb-3">Guide Personnalisé à Chaque Étape</h3>
          <p class="text-gray-700">
            Un accompagnement sur mesure à chaque phase de votre projet, de l'idée à la mise en marché, pour vous aider à surmonter les défis et maximiser votre succès.
          </p>
        </div>
      </div>
      
    </div>
  </div>
</section>
    </div>
    <Footer />
  </template>
  
  <script>
import { Head } from "@inertiajs/inertia-vue3";
import { defineAsyncComponent, ref, onMounted } from 'vue';
import Footer from "@/Components/Footer.vue";

const AppHeader = defineAsyncComponent(() => 
  import("@/Components/Welcome/AppHeader.vue")
);

  export default {
    name: 'ImmersiveWorkspacePage',
    components: {
      AppHeader,
      Footer,
      Head
    },
    data() {
      return {
        coaches: [
          {
            id: 1,
            name: 'Ahmed Lorenzo',
            specialty: 'Expert en développement personnel',
            image: 'image/startup2/2.jpeg'
          },
          {
            id: 2,
            name: 'Monique Lorenzo',
            specialty: 'Spécialiste en productivité',
            image: 'image/startup2/expert.jpeg'
          },
          {
            id: 3,
            name: 'Nadia Francati',
            specialty: 'Coach en management',
            image: 'image/startup2/3.jpeg'
          },
          {
            id: 4,
            name: 'Ahmad T.',
            specialty: 'Expert technique',
            image: 'image/startup2/expert.jpeg'
          }
        ],
        aiFeatures: [
          {
            title: 'Assistance 24/7',
            description: 'Une disponibilité constante pour répondre à vos questions et vous accompagner dans votre progression.'
          },
          {
            title: 'Apprentissage adaptatif',
            description: 'Système intelligent qui s\'adapte à votre rythme et propose des contenus personnalisés.'
          },
          {
            title: 'Ressources intelligentes',
            description: 'Accès à des ressources pédagogiques adaptées à votre niveau et à vos objectifs spécifiques.'
          },
          {
            title: 'Suivi de progression détaillé',
            description: 'Analyses et rapports pour visualiser vos avancées et identifier les points d\'amélioration.'
          }
        ]
      }
    },
    methods: {
      getTimelineStep(index) {
        const steps = [
          'Évaluation initiale', 
          'Définition des objectifs', 
          'Plan personnalisé', 
          'Fondamentaux', 
          'Concepts avancés', 
          'Mise en pratique',
          'Analyse de cas', 
          'Projets collaboratifs', 
          'Évaluation intermédiaire', 
          'Spécialisation', 
          'Projet final', 
          'Certification'
        ];
        return steps[index - 1] || `Étape ${index}`;
      }
    }
  }
  </script>
  
  <style scoped>
  .hero {
    background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/workspace-bg.jpg');
    background-size: cover;
    background-position: center;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .timeline-container {
    position: relative;
  }
  
  .timeline-line {
    position: relative;
  }
  
  .timeline-point {
    flex: 1;
    position: relative;
  }
  
  .timeline-connector {
    width: 1px;
    margin-left: 50%;
  }
  
  @media (max-width: 768px) {
    .timeline-line {
      flex-direction: column;
    }
  
    .timeline-point {
      margin-bottom: 1rem;
    }
  
    .timeline-connector {
      display: none;
    }
  }
  </style>