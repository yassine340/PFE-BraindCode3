<template>
    <Head title="FAQ - BrainCode Startup Studio" />
    <AppHeader :canLogin="canLogin" :canRegister="canRegister" />
    <div class="faq-container">
      <!-- Header Section -->
      <div class="header">
        <h1 class="title">FAQ - BrainCode Startup Studio</h1>
        <p class="subtitle">Questions fréquemment posées</p>
      </div>
  
      <!-- FAQ Section with Accordion -->
      <div class="faq-content">
        <div class="faq-illustration">
          <img src="/api/placeholder/300/400" alt="FAQ Illustration with clipboard" />
          <div class="character">
            <img src="/api/placeholder/150/200" alt="Character pointing to FAQ" />
          </div>
        </div>
  
        <div class="faq-questions">
          <div v-for="(item, index) in faqItems" :key="index" class="faq-item">
            <div class="faq-question" @click="toggleItem(index)">
              <h3>{{ item.question }}</h3>
              <span class="toggle-icon">{{ item.isOpen ? '−' : '+' }}</span>
            </div>
            <div class="faq-answer" v-show="item.isOpen">
              <p>{{ item.answer }}</p>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Tutorials Section -->
      <div class="tutorials-section">
        <h2>Tutoriels :</h2>
        <div class="tutorials-grid">
          <div class="tutorial-card" v-for="(tutorial, index) in tutorials" :key="index">
            <div class="tutorial-image">
              <img :src="tutorial.image" :alt="tutorial.title" />
            </div>
            <p class="tutorial-title">{{ tutorial.title }}</p>
          </div>
        </div>
      </div>
    </div>
    <Footer />
  </template>
  
  <script>
    import { ref, onMounted, computed } from "vue";
    import { Head } from "@inertiajs/vue3";
    
    import AppHeader from "@/Components/Welcome/AppHeader.vue";
    import Footer from "@/Components/Footer.vue";
  export default {
    name: 'FAQPage',
    components: {
      AppHeader,
      Footer
    },
    data() {
      return {
        faqItems: [
          {
            question: "Qu'est-ce que BrainCode Startup Studio ?",
            answer: "BrainCode Startup Studio est une plateforme dédiée aux entrepreneurs pour développer leurs idées et créer des startups innovantes avec un accompagnement complet.",
            isOpen: true
          },
          {
            question: "Quels services propose-t-on ?",
            answer: "Nous proposons du mentorat, des formations, des ressources techniques, un espace de coworking et un réseau d'investisseurs pour les startups.",
            isOpen: false
          },
          {
            question: "Qui peut rejoindre BrainCode Startup Studio ?",
            answer: "Les entrepreneurs, porteurs de projets innovants, développeurs et toute personne souhaitant développer une startup tech.",
            isOpen: false
          },
          {
            question: "Comment postuler pour rejoindre le studio ?",
            answer: "Vous pouvez postuler via notre formulaire en ligne en présentant votre projet et votre équipe. Nous organisons ensuite un entretien.",
            isOpen: false
          },
          {
            question: "Combien de temps dure l'incubation ?",
            answer: "Notre programme d'incubation standard dure 6 mois, avec possibilité d'extension selon l'avancement du projet.",
            isOpen: false
          },
          {
            question: "Comment puis-je contacter BrainCode Startup Studio ?",
            answer: "Vous pouvez nous contacter par email à contact@braincode.studio ou par téléphone au +33 1 23 45 67 89.",
            isOpen: false
          }
        ],
        tutorials: [
          {
            title: "Créer une formation",
            image: "/api/placeholder/200/150"
          },
          {
            title: "Contacter un mentor",
            image: "/api/placeholder/200/150"
          },
          {
            title: "Utiliser le tableau de bord",
            image: "/api/placeholder/200/150"
          }
        ]
      }
    },
    methods: {
      toggleItem(index) {
        this.faqItems[index].isOpen = !this.faqItems[index].isOpen;
      }
    }
  }
  </script>
  
  <style scoped>
  .faq-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    font-family: 'Arial', sans-serif;
    color: #333;
  }
  
  .header {
    text-align: center;
    margin-bottom: 3rem;
  }
  
  .title {
    color: #0078aa;
    font-size: 2rem;
    margin-bottom: 0.5rem;
  }
  
  .subtitle {
    color: #555;
    margin-top: 0;
  }
  
  .faq-content {
    display: flex;
    gap: 2rem;
    margin-bottom: 3rem;
  }
  
  .faq-illustration {
    flex: 1;
    position: relative;
    display: flex;
    justify-content: center;
  }
  
  .character {
    position: absolute;
    right: 0;
    bottom: 0;
  }
  
  .faq-questions {
    flex: 2;
  }
  
  .faq-item {
    border-bottom: 1px solid #e0e0e0;
    margin-bottom: 1rem;
  }
  
  .faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 1rem 0;
  }
  
  .faq-question h3 {
    margin: 0;
    color: #0078aa;
    font-size: 1.1rem;
  }
  
  .toggle-icon {
    font-size: 1.5rem;
    color: #0078aa;
  }
  
  .faq-answer {
    padding: 0 0 1rem 0;
  }
  
  .tutorials-section {
    margin-top: 3rem;
  }
  
  .tutorials-section h2 {
    color: #0078aa;
    margin-bottom: 1.5rem;
  }
  
  .tutorials-grid {
    display: flex;
    gap: 2rem;
    justify-content: space-between;
  }
  
  .tutorial-card {
    flex: 1;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    border-radius: 8px;
  }
  
  .tutorial-image {
    margin-bottom: 1rem;
  }
  
  .tutorial-image img {
    width: 100%;
    height: auto;
    border-radius: 4px;
  }
  
  .tutorial-title {
    font-weight: bold;
    margin: 0;
  }
  
  @media (max-width: 768px) {
    .faq-content {
      flex-direction: column;
    }
    
    .tutorials-grid {
      flex-direction: column;
    }
  }
  </style>