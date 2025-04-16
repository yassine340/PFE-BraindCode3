<script setup>
import { ref, onMounted, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import { Pagination, Autoplay } from "swiper/modules";
import Footer from "@/Components/Footer.vue";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});

const modules = [Pagination, Autoplay];

// Theme state with improved persistence
const isDarkMode = ref(false);

// Enhanced theme detection and persistence
onMounted(() => {
  const savedTheme = localStorage.getItem("theme");
  const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  // Priority: Saved preference > System preference
  isDarkMode.value = savedTheme 
    ? savedTheme === "dark" 
    : prefersDarkMode;

  document.documentElement.classList.toggle('dark', isDarkMode.value);
});

// Advanced theme toggle with system preference consideration
const toggleTheme = () => {
  isDarkMode.value = !isDarkMode.value;
  localStorage.setItem("theme", isDarkMode.value ? "dark" : "light");
  document.documentElement.classList.toggle('dark', isDarkMode.value);
};

// Enhanced slide content with more descriptive information
const slides = [
  {
    title: "Apprenez sans limites",
    description: "Des cours accessibles partout, à tout moment.",
    bgClass: "bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-800 dark:to-blue-900",
    icon: "🌐",
    benefits: [
      "Accès 24/7",
      "Multi-appareils",
      "Flexibilité maximale"
    ]
  },
  {
    title: "Experts de l'industrie",
    description: "Formez-vous avec les meilleurs professionnels.",
    bgClass: "bg-gradient-to-r from-green-600 to-green-800 dark:from-green-800 dark:to-green-900",
    icon: "👩‍🏫",
    benefits: [
      "Instructeurs certifiés",
      "Expérience terrain",
      "Conseils pratiques"
    ]
  },
  {
    title: "Apprentissage Dynamique",
    description: "Des méthodes interactives pour maximiser votre apprentissage.",
    bgClass: "bg-gradient-to-r from-purple-600 to-purple-800 dark:from-purple-800 dark:to-purple-900",
    icon: "🧩",
    benefits: [
      "Quiz interactifs",
      "Projets concrets",
      "Progression personnalisée"
    ]
  },
  {
    title: "Certification Professionnelle",
    description: "Des diplômes reconnus pour booster votre carrière.",
    bgClass: "bg-gradient-to-r from-red-600 to-red-800 dark:from-red-800 dark:to-red-900",
    icon: "🏆",
    benefits: [
      "Certificats officiels",
      "Validation des compétences",
      "Reconnaissance industrielle"
    ]
  }
];

// More sophisticated theme management
const themeClasses = computed(() => ({
  background: isDarkMode.value 
    ? 'bg-gray-900 text-gray-100 bg-noise' 
    : 'bg-gray-50 text-gray-900',
  header: isDarkMode.value 
    ? 'border-gray-800 bg-gray-900/80' 
    : 'border-gray-200 bg-white/80',
  text: {
    primary: isDarkMode.value ? 'text-gray-100' : 'text-gray-900',
    secondary: isDarkMode.value ? 'text-gray-300' : 'text-gray-600'
  }
}));
</script>

<template>
  <Head title="Braindcode - Plateforme E-Learning" />

  <div 
    class="min-h-screen flex flex-col relative overflow-hidden"
    :class="themeClasses.background"
  >
    <!-- Subtle background pattern for depth -->
    <div 
      class="absolute inset-0 opacity-[0.03] pointer-events-none" 
      :class="isDarkMode 
        ? 'bg-[radial-gradient(#ffffff0d_1px,transparent_1px)] [background-size:16px_16px]' 
        : 'bg-[radial-gradient(#0000000d_1px,transparent_1px)] [background-size:16px_16px]'"
    ></div>

    <!-- Header with improved visibility and interaction -->
    <header 
      class="sticky top-0 z-50 py-4 px-8 flex justify-between items-center border-b backdrop-blur-md transition-all duration-300"
      :class="[themeClasses.header, 'shadow-sm']"
    >
      <h1 
        class="text-3xl font-black tracking-tight transform transition hover:scale-105 cursor-pointer" 
        :class="themeClasses.text.primary"
      >
      <img src="/image/logos/BraindCode.png" class="img-fluid" style="width: 200px;" alt="Logo" />
      </h1>

      <div class="flex items-center space-x-1">
        <!-- Theme Toggle with more sophisticated design -->
        <button
          @click="toggleTheme"
          aria-label="Changer de thème"
          class="relative w-12 h-6 bg-gray-200 dark:bg-gray-700 rounded-full p-1 transition-colors duration-300 ease-in-out focus:outline-none"
        >
          <span 
            class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white dark:bg-gray-900 rounded-full shadow-md transform transition-transform duration-300"
            :class="isDarkMode ? 'translate-x-6' : 'translate-x-0'"
          >
            {{ isDarkMode ? '🌙' : '☀️' }}
          </span>
        </button>

        <nav v-if="canLogin" class="flex items-center space-x-4">
          <Link 
            v-if="$page.props.auth.user" 
            :href="route('dashboard')" 
            class="px-3 py-2 rounded-md transition hover:bg-gray-200 dark:hover:bg-gray-800"
            :class="themeClasses.text.secondary"
          >
            Tableau de bord
          </Link>
          <template v-else>
            <Link 
              :href="route('login')" 
              class="px-4 py-2 rounded-md transition hover:bg-gray-200 dark:hover:bg-gray-800"
              :class="themeClasses.text.secondary"
            >
              Connexion
            </Link>
            <Link 
              v-if="canRegister" 
              :href="route('register')" 
              class="px-4 py-2 rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-md"
            >
              Inscription
            </Link>
          </template>
        </nav>
      </div>
    </header>

    <!-- Enhanced Slider with more interactive design -->
    <section class="w-full max-w-6xl mx-auto my-12 px-4">
      <Swiper
        :modules="modules"
        :spaceBetween="30"
        :pagination="{ clickable: true }"
        :autoplay="{ delay: 5000, disableOnInteraction: false }"
        class="rounded-3xl overflow-hidden shadow-2xl"
      >
        <SwiperSlide 
          v-for="(slide, index) in slides" 
          :key="index"
          :class="[
            slide.bgClass, 
            'text-center py-20 transform transition-all duration-300 hover:scale-[1.01] group'
          ]"
        >
          <div class="max-w-3xl mx-auto relative">
            <span class="text-6xl mb-6 block opacity-80 group-hover:scale-110 transition-transform">
              {{ slide.icon }}
            </span>
            <h2 class="text-4xl font-bold text-white mb-4 tracking-tight">
              {{ slide.title }}
            </h2>
            <p class="text-xl text-white opacity-80 mb-6">
              {{ slide.description }}
            </p>
            <div class="flex justify-center space-x-4">
              <span 
                v-for="(benefit, benefitIndex) in slide.benefits" 
                :key="benefitIndex"
                class="px-3 py-1 bg-white/20 text-white rounded-full text-sm"
              >
                {{ benefit }}
              </span>
            </div>
          </div>
        </SwiperSlide>
      </Swiper>
    </section>

    <!-- Rest of the content remains similar to previous version -->
    <!-- Additional improvements can be made to subsequent sections -->

    <!-- Main Content -->
    <main class="flex-grow max-w-5xl mx-auto px-6 py-12">
      <section class="text-center mb-16">
        <h2 
          class="text-4xl font-bold mb-6 tracking-tight" 
          :class="themeClasses.title"
        >
          Bienvenue sur notre plateforme E-Learning
        </h2>
        <p 
          class="text-xl leading-relaxed max-w-3xl mx-auto" 
          :class="themeClasses.text"
        >
          Apprendre n'a jamais été aussi simple ! Nos cours interactifs, nos experts et notre plateforme engageante vous offrent une expérience d'apprentissage unique.
        </p>
      </section>

      <section class="grid md:grid-cols-2 gap-8">
        <div 
          class="p-8 rounded-2xl shadow-lg transform transition hover:scale-105" 
          :class="isDarkMode ? 'bg-gray-800' : 'bg-white border'"
        >
          <h3 class="text-2xl font-bold mb-6" :class="themeClasses.title">
            Pourquoi choisir notre plateforme ?
          </h3>
          <ul class="space-y-4" :class="themeClasses.text">
            <li class="flex items-center space-x-3">
              <span class="text-blue-500">✓</span>
              <span>Cours vidéo de haute qualité</span>
            </li>
            <li class="flex items-center space-x-3">
              <span class="text-green-500">✓</span>
              <span>Experts et projets concrets</span>
            </li>
            <li class="flex items-center space-x-3">
              <span class="text-purple-500">✓</span>
              <span>Apprenez à votre rythme</span>
            </li>
            <li class="flex items-center space-x-3">
              <span class="text-red-500">✓</span>
              <span>Certifications reconnues</span>
            </li>
          </ul>
        </div>

        <div 
          class="p-8 rounded-2xl shadow-lg transform transition hover:scale-105" 
          :class="isDarkMode ? 'bg-gray-800' : 'bg-white border'"
        >
          <h3 class="text-2xl font-bold mb-6" :class="themeClasses.title">
            Rencontrez notre équipe
          </h3>
          <p :class="themeClasses.text">
            Une équipe passionnée d'éducateurs et de développeurs dédiée à votre apprentissage, avec l'objectif de transformer votre potentiel en réussite.
          </p>
        </div>
      </section>

      <section class="text-center mt-16">
        <h3 
          class="text-3xl font-bold mb-6 tracking-tight" 
          :class="themeClasses.title"
        >
          Commencez dès aujourd'hui !
        </h3>
        <p 
          class="text-xl mb-8" 
          :class="themeClasses.text"
        >
          Rejoignez des milliers d'étudiants et développez vos compétences.
        </p>
        <Link 
          :href="route('register')" 
          class="px-8 py-4 rounded-full text-lg font-bold shadow-lg transform transition hover:scale-105 focus:outline-none focus:ring-2"
          :class="isDarkMode 
            ? 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500' 
            : 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-400'"
        >
          Inscrivez-vous maintenant
        </Link>
      </section>
    </main>

    <!-- Footer -->
    <Footer />
  </div>
</template>
<style>
/* Optional: Add a subtle noise texture */
.bg-noise {
  background-image: 
    linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
  background-size: 30px 30px;
}
</style>