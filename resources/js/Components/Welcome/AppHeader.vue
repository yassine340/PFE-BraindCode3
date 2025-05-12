<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onBeforeMount, onMounted, onUnmounted } from "vue";


const props = defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
    User: {
      type: Object,
      default: () => ({})
    }
  });
  
  const getDashboardRoute = (user) => {
    if (user.role === 'admin') {
      return '/dashboardAdmin';
    } else if (user.role === 'startup') {
      return '/startup/dashboard';
    } else if (user.role === 'formateur') {
      return '/DashboardFormateur';
    }
    return '/dashboard'; // Default route
  }


const isMenuOpen = ref(false);
const isScrolled = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
  if (isMenuOpen.value) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
};

const checkScroll = () => {
  isScrolled.value = window.scrollY > 20;
};

onBeforeMount(() => {
  isScrolled.value = false;
});

onMounted(() => {
  window.addEventListener('scroll', checkScroll);
  checkScroll();
});

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll);
});
</script>

<template>
  <header 
    :class="[
      'sticky top-0 z-50 w-full transition-all duration-300 border-b backdrop-blur-md bg-white',
      isScrolled ? 'py-2 shadow-md bg-opacity-95' : 'py-4 bg-opacity-90'
    ]"
  >
    <div class="container mx-auto px-4 flex justify-between items-center">
      <Link href="/" class="transform transition hover:scale-105 z-10">
        <h1 class="text-3xl font-black tracking-tight cursor-pointer text-gray-900">
          <img src="/image/logos/BraindCode.png" class="logo-image" alt="Logo" />
        </h1>
      </Link>

      <!-- Mobile Menu Button -->
      <button 
        @click="toggleMenu" 
        class="lg:hidden flex items-center z-10"
        aria-label="Toggle menu"
      >
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          class="h-6 w-6 text-gray-800" 
          fill="none" 
          viewBox="0 0 24 24" 
          stroke="currentColor"
        >
          <path 
            v-if="!isMenuOpen" 
            stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            d="M4 6h16M4 12h16M4 18h16" 
          />
          <path 
            v-else 
            stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            d="M6 18L18 6M6 6l12 12" 
          />
        </svg>
      </button>

      <!-- Desktop Menu -->
      <div v-if="canLogin" class="hidden lg:flex items-center space-x-6">
        <Link 
          href="/startup" 
          class="nav-link"
        >
          startup
        </Link>
        <Link 
          href="/startup-investment" 
          class="nav-link"
        >
          Investisseur
        </Link>
        <Link 
          href="/coach" 
          class="nav-link"
        >
          Coach
        </Link>
        <Link 
          href="/forum" 
          class="nav-link"
        >
          Forum
        </Link>
        <Link 
          v-if="$page.props.auth.user" 
          :href="getDashboardRoute($page.props.auth.user)" 
          class="nav-link"
        >
          <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-300 font-semibold mr-3">
            <div v-if="$page.props.auth.user.profile_image" class="flex-shrink-0 h-10 w-10">
              <img class="h-10 w-10 rounded-full" :src="$page.props.auth.user.profile_image" alt="" />
            </div>
            <template v-else>
              {{ $page.props.auth.user.first_name?.charAt(0) || 'U' }}
            </template>
          </div>
        </Link>
        <template v-else>
          <Link 
            :href="route('login')" 
            class="nav-link"
          >
            Connexion
          </Link>
          <Link 
            v-if="canRegister" 
            :href="route('register')" 
            class="btn-primary"
          >
            Inscription
          </Link>
        </template>
      </div>

      <!-- Mobile Menu Overlay -->
      <div 
        v-if="isMenuOpen" 
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm lg:hidden z-0"
        @click="toggleMenu"
      ></div>

      <!-- Mobile Menu Panel -->
      <div 
        :class="[
          'fixed top-0 right-0 h-full w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out z-50 lg:hidden',
          isMenuOpen ? 'translate-x-0' : 'translate-x-full'
        ]"
      >
        <div class="flex flex-col p-6 h-full">
          <div class="flex justify-end mb-6">
            <button @click="toggleMenu" aria-label="Close menu">
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-6 w-6 text-gray-800" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M6 18L18 6M6 6l12 12" 
                />
              </svg>
            </button>
          </div>
          
          <nav v-if="canLogin" class="flex flex-col space-y-4 mt-6">
            <Link 
              v-if="$page.props.auth.user" 
              :href="route('dashboard')" 
              class="mobile-nav-link"
              @click="toggleMenu"
            >
              Tableau de bord
            </Link>
            <template v-else>
              <Link 
                :href="route('login')" 
                class="mobile-nav-link"
                @click="toggleMenu"
              >
                Connexion
              </Link>
              <Link 
                v-if="canRegister" 
                :href="route('register')" 
                class="mobile-btn-primary"
                @click="toggleMenu"
              >
                Inscription
              </Link>
            </template>
          </nav>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
/* Base styles */
.logo-image {
  width: 200px;
  height: auto;
  transition: all 0.3s ease;
}

.nav-link {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  color: rgb(55, 65, 81);
  transition: all 0.2s ease;
}

.nav-link:hover {
  background-color: rgb(229, 231, 235);
}

.btn-primary {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  color: white;
  background-image: linear-gradient(to right, rgb(37, 99, 235), rgb(29, 78, 216));
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.btn-primary:hover {
  background-image: linear-gradient(to right, rgb(29, 78, 216), rgb(30, 64, 175));
}

.mobile-nav-link {
  display: block;
  padding: 0.75rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  color: rgb(55, 65, 81);
  transition: all 0.2s ease;
}

.mobile-nav-link:hover {
  background-color: rgb(243, 244, 246);
}

.mobile-btn-primary {
  display: block;
  padding: 0.75rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  color: white;
  background-image: linear-gradient(to right, rgb(37, 99, 235), rgb(29, 78, 216));
  transition: all 0.3s ease;
  text-align: center;
}

/* Desktop-first responsive design */
@media (max-width: 1280px) {
  .logo-image {
    width: 180px;
  }
}

@media (max-width: 1024px) {
  .logo-image {
    width: 160px;
  }
}

@media (max-width: 768px) {
  .logo-image {
    width: 140px;
  }
}

@media (max-width: 640px) {
  .logo-image {
    width: 120px;
  }
}

@media (max-width: 480px) {
  .logo-image {
    width: 110px;
  }
}

/* Print styles */ 
@media print {
  header {
    position: static;
    box-shadow: none;
    border: none;
  }
}

</style>