<script setup>
import { ref, onMounted, computed } from "vue";
import { Head } from "@inertiajs/vue3";
import AppHeader from "@/Components/Welcome/AppHeader.vue";
import HeroSection from "@/Components/Welcome/HeroSection.vue";
import AboutSection from "@/Components/Welcome/AboutSection.vue";
import ServicesSection from "@/Components/Welcome/ServicesSection.vue";
import BeneficiariesSection from "@/Components/Welcome/BeneficiariesSection.vue";
import Footer from "@/Components/Footer.vue";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});

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

// More sophisticated theme management
const themeClasses = computed(() => ({
  background: isDarkMode.value 
    ? 'bg-gray-900 text-gray-100' 
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
  <Head title="BraindCode - Startup Studio" />

  <div class="min-h-screen flex flex-col relative overflow-hidden" :class="themeClasses.background">
    <!-- Header -->
    <AppHeader 
      :canLogin="canLogin" 
      :canRegister="canRegister" 
      :isDarkMode="isDarkMode" 
      :themeClasses="themeClasses" 
      @toggle-theme="toggleTheme" 
    />

    <!-- Hero Section -->
    <HeroSection />

    <!-- What is BrainCode Startup Studio Section -->
    <AboutSection />

    <!-- Services Section -->
    <ServicesSection />

    <!-- Beneficiaries Section -->
    <BeneficiariesSection />

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