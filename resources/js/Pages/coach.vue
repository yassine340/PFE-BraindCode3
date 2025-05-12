<template>
    <Head title="BrainCode Coach" />
    <AppHeader :canLogin="true" :canRegister="true" />
    <div class="braincode-coach-page">
      <!-- Hero Section - Improved with gradient background and better visual hierarchy -->
      <section class="hero-section bg-gradient-to-r from-blue-50 to-indigo-50 py-16">
        <div class="container mx-auto hero-container px-4">
          <div class="hero-content flex flex-col md:flex-row items-center">
            <div class="hero-text md:w-1/2 text-left">
              <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider mb-2 block">Programme de mentorat</span>
              <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 leading-tight">Encadré par les <span class="text-blue-600">meilleurs experts</span> et mentors</h1>
              <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                BrainCode Startup Studio réunit des experts et mentors de haut niveau investis sur le projet pour maximiser les chances de succès des entrepreneurs.
              </p>
              <button class="bg-blue-600 text-white py-3 px-8 rounded-lg hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center">
                <span>Devenir mentor</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
            <div class="hero-image md:w-1/2 flex justify-center mt-8 md:mt-0">
              <img src="/image/coach/a.png" alt="Expert mentor illustration" class="h-64 md:h-80 transform hover:scale-105 transition-transform duration-300 filter drop-shadow-xl" />
            </div>
          </div>
        </div>
      </section>
  
      <!-- Why Become a Coach Section - Improved cards with hover effects -->
      <section class="why-section bg-white py-16">
        <div class="container mx-auto container-context px-4">
          <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider text-center mb-2 block">Opportunités</span>
          <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-6">
            Pourquoi Devenir Coach sur <span class="text-blue-600">Braincode Startup Studio</span> ?
          </h2>
          <p class="text-center mb-12 text-gray-600 max-w-2xl mx-auto">Partagez votre expertise, écoutez les entrepreneurs et façonnez l'avenir des startups !</p>
  
          <div class="responsive-grid">
            <!-- Card 1 - Improved with hover effects and icons -->
            <div class="card-container">
              <div class="feature-card bg-white rounded-xl shadow-md hover:shadow-xl transition-all p-6 border-t-4 border-blue-500 transform hover:-translate-y-2">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Aidez les startups à réussir</h3>
                <p class="text-gray-600">
                  Apportez votre savoir-faire aux jeunes entrepreneurs et contribuez à leur succès
                </p>
              </div>
            </div>
  
            <!-- Card 2 -->
            <div class="card-container">
              <div class="feature-card bg-white rounded-xl shadow-md hover:shadow-xl transition-all p-6 border-t-4 border-blue-500 transform hover:-translate-y-2">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Boostez votre visibilité</h3>
                <p class="text-gray-600">
                  Faites-vous connaître auprès de l'écosystème d'innovateurs et élargissez votre réseau
                </p>
              </div>
            </div>
  
            <!-- Card 3 -->
            <div class="card-container">
              <div class="feature-card bg-white rounded-xl shadow-md hover:shadow-xl transition-all p-6 border-t-4 border-blue-500 transform hover:-translate-y-2">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Monétisez votre expertise</h3>
                <p class="text-gray-600">
                  Recevez des commissions sur les succès commerciaux des startups que vous accompagnez
                </p>
              </div>
            </div>
  
            <!-- Card 4 -->
            <div class="card-container">
              <div class="feature-card bg-white rounded-xl shadow-md hover:shadow-xl transition-all p-6 border-t-4 border-blue-500 transform hover:-translate-y-2">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Accédez à un réseau dynamique</h3>
                <p class="text-gray-600">
                  Échangez avec d'autres experts et saisissez de nouvelles opportunités de collaboration
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Key Features Section - Improved with better spacing and visual design -->
      <section class="features-section bg-gray-50 py-16">
        <div class="container mx-auto container-context px-4">
          <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider text-center mb-2 block">Plateforme</span>
          <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-12">
            Fonctionnalités Clés de l'Espace Coach
          </h2>
  
          <div class="responsive-grid">
            <!-- Feature 1 - Improved with better styling -->
            <div class="text-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-all">
              <div class="flex justify-center mb-6">
                <div class="bg-blue-50 p-4 rounded-full">
                  <img src="/image/coach/1.png" alt="Gestion des startups" class="h-14 w-14" />
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-3">Suivi & gestion des startups</h3>
              <p class="text-gray-600">Suivez la progression des startups que vous accompagnez</p>
            </div>
  
            <!-- Feature 2 -->
            <div class="text-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-all">
              <div class="flex justify-center mb-6">
                <div class="bg-blue-50 p-4 rounded-full">
                  <img src="/image/coach/2.png" alt="Networking" class="h-14 w-14" />
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-3">Networking & mise en relation</h3>
              <p class="text-gray-600">Connectez-vous avec l'écosystème entrepreneurial</p>
            </div>
  
            <!-- Feature 3 -->
            <div class="text-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-all">
              <div class="flex justify-center mb-6">
                <div class="bg-blue-50 p-4 rounded-full">
                  <img src="/image/coach/3.png" alt="Évaluation" class="h-14 w-14" />
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-3">Évaluation & validation</h3>
              <p class="text-gray-600">Analysez et validez les modèles économiques</p>
            </div>
  
            <!-- Feature 4 -->
            <div class="text-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-all">
              <div class="flex justify-center mb-6">
                <div class="bg-blue-50 p-4 rounded-full">
                  <img src="/image/coach/4.png" alt="Planification" class="h-14 w-14" />
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-3">Planification des sessions</h3>
              <p class="text-gray-600">Organisez vos séances de coaching efficacement</p>
            </div>
          </div>
        </div>
      </section>
      <!-- Experts Section - Redesigned to match tutorials style -->
      <section class="experts-section">
        <div class="container">
            <h2>Nos experts :</h2>
            
            <div class="experts-grid">
            <!-- Expert 1 -->
            <div class="expert-card">
                <div class="expert-image">
                <img src="/image/coach/expert.jpeg" alt="Ahmed Landolsi" />
                </div>
                <p class="expert-title">Ahmed Landolsi</p>
            </div>
            
            <!-- Expert 2 -->
            <div class="expert-card">
                <div class="expert-image">
                <img src="/image/coach/expert.jpeg" alt="Sophie Martin" />
                </div>
                <p class="expert-title">Sophie Martin</p>
            </div>
            
            <!-- Expert 3 -->
            <div class="expert-card">
                <div class="expert-image">
                <img src="/image/coach/expert.jpeg" alt="Thomas Dubois" />
                </div>
                <p class="expert-title">Thomas Dubois</p>
            </div>
            
            <!-- Expert 4 -->
            <div class="expert-card">
                <div class="expert-image">
                <img src="/image/coach/expert.jpeg" alt="Marie Chen" />
                </div>
                <p class="expert-title">Marie Chen</p>
            </div>
            </div>
        </div>
        <section class="investor-features-heading bg-white pt-20 pb-6">
        <div class="container mx-auto px-4 md:px-8">
          <h2 class="text-3xl font-bold text-blue-600 text-center mb-4">Devenez coach chez BrainCode !</h2>
          <p class="text-gray-600 text-center max-w-2xl mx-auto">Vous avez de l’expertise en entrepreneuriat, innovation ou financement ? Rejoignez BrainCode Startup Studio en tant que coach et accompagnez les startups dans leur croissance !</p>
        </div>
      </section>
    </section>
    <section class="bg-blue-50 py-16">
        <div class="container mx-auto px-4 md:px-8">
          <h2 class="text-3xl font-bold text-blue-600 text-center mb-12">Pourquoi nous rejoinder ?</h2>
      </div>
    </section>
    <!-- Features Section -->
        <section class="features-section">
        <div class="container">
            <div class="features-grid">
            <div class="feature-item">
                <span class="feature-bullet"></span>
                <p>Partagez votre savoir-faire avec des entrepreneurs ambitieux.</p>
            </div>
            
            <div class="feature-item">
                <span class="feature-bullet"></span>
                <p>Accédez à des événements et collaborations exclusives.</p>
            </div>
            
            <div class="feature-item">
                <span class="feature-bullet"></span>
                <p>Développement de votre expertise</p>
            </div>
            
            <div class="feature-item">
                <span class="feature-bullet"></span>
                <p>Opportunités de collaboration</p>
            </div>
            </div>
            
            <div class="button-container">
            <a href="#" class="cta-button">Postuler en tant que coach</a>
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
  name: 'InvestmentLandingPage',
  components: {
    Head,
    AppHeader,
    Footer
  },
  setup() {
    // Reactive data for potential future functionality
    const isModalOpen = ref(false);
    
    // Could add more reactive data and methods as needed
    
    return {
      isModalOpen
    };
  }
}
</script>
<style>
/* Container Styles */
.experts-section {
  background-color: white;
  padding: 60px 0;
}



/* Heading Styles */
.experts-section h2 {
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 30px;
  color: #333;
}

/* Grid Layout */
.experts-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

/* Expert Card Styles */
.expert-card {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease;
}

.expert-card:hover {
  transform: translateY(-5px);
}

.expert-image {
  width: 100%;
  height: 200px;
  overflow: hidden;
}

.expert-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.expert-title {
  font-weight: bold;
  text-align: center;
  padding: 16px;
  font-size: 18px;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .experts-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .experts-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .experts-grid {
    grid-template-columns: 1fr;
  }
  
  .expert-image {
    height: 240px;
  }
}
.features-section {
  padding: 60px 0;
  background-color: white;
}

/* Features Grid Layout */
.features-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 30px 60px;
  margin-bottom: 50px;
}

/* Feature Item Styling */
.feature-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
}

/* Blue Bullet Point */
.feature-bullet {
  min-width: 16px;
  height: 16px;
  background-color: #0099ff;
  border-radius: 2px;
  margin-top: 5px;
}

/* Feature Text */
.feature-item p {
  margin: 0;
  font-size: 16px;
  line-height: 1.5;
  color: #333;
}

/* Button Container */
.button-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

/* CTA Button */
.cta-button {
  display: inline-block;
  background-color: #0099ff;
  color: white;
  font-weight: 500;
  padding: 12px 24px;
  border-radius: 50px;
  text-decoration: none;
  text-align: center;
  transition: background-color 0.3s ease;
}

.cta-button:hover {
  background-color: #007ecc;
}

/* Responsive Design */
@media (max-width: 768px) {
  .features-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
}
</style>