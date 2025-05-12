<template>
  <div>
    <FormationDetails 
      :formation="formation" 
      :hasUserPaid="hasUserPaid" 
      @start-formation="startFormation"
      @show-payment="showPaymentModal = true"
    />
    
    <PaymentModal
      v-if="showPaymentModal"
      :show="showPaymentModal"
      :formation="formation"
      :user-id="userId"
      :payment-processing="paymentProcessing"
      @close="showPaymentModal = false"
      @payment-success="handlePaymentSuccess"
    />
  </div>
  <div class="mt-8">
      <h3 class="text-xl font-bold mb-6">Tu pourrais aussi aimer</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="formation in relatedFormations" :key="formation.id" class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="relative overflow-hidden h-36">
            <img 
              v-if="formation.image_formation" 
              :src="`/storage/${formation.image_formation}`" 
              alt="Image formation" 
              class="w-full h-full object-cover"
            >
            <div v-else class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
          </div>
          <div class="p-4">
            <h4 class="font-bold text-gray-800 line-clamp-2">{{ formation.titre }}</h4>
            <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ formation.description }}</p>
            <Link 
              :href="`/formations/${formation.id}`" 
              class="mt-3 inline-block px-4 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition-colors"
            >
              Voir plus
            </Link>
          </div>
        </div>
      </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { usePage, Link } from '@inertiajs/vue3';
import FormationDetails from './FormationDetails/FormationDetails.vue';
import PaymentModal from './FormationDetails/PaymentModal.vue';

interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  role: string;
}

interface Formation {
  id: number;
  user?: User;
  titre: string;
  description: string;
  duree: string;
  participants: string;
  image_formation: string;
  prix: number;
  modules?: any[];
}

interface PageProps {
  auth: {
    user: User | null;
  };
  [key: string]: any;
}

const props = defineProps<{
  formation: Formation;
}>();

const emit = defineEmits<{
  (e: 'start-formation'): void;
}>();

// User information
const page = usePage<PageProps>();
const user = computed(() => page.props.auth.user);
const userId = computed(() => user.value?.id);

// Payment state
const showPaymentModal = ref(false);
const hasUserPaid = ref(false);
const paymentProcessing = ref(false);

// Related formations
const relatedFormations = ref<Formation[]>([]);

// Start formation after payment
const startFormation = () => {
  emit('start-formation');
};

// Handle successful payment
const handlePaymentSuccess = () => {
  hasUserPaid.value = true;
  showPaymentModal.value = false;
  startFormation();
};

// Check if user has already paid for the formation
const checkUserPaymentStatus = async () => {
  if (!userId.value || !props.formation?.id) {
    hasUserPaid.value = false;
    return;
  }
  
  try {
    const response = await axios.get(`/check-payment-status/${userId.value}/${props.formation.id}`);
    hasUserPaid.value = response.data.hasPaid;
  } catch (error) {
    console.error('Error checking payment status:', error);
    hasUserPaid.value = false;
  }
};

// Fetch related formations
const fetchRelatedFormations = async () => {
  try {
    // Fetch formations similar to the current one, excluding the current formation
    const response = await axios.get(`/api/related-formations/${props.formation.id}`);
    relatedFormations.value = response.data.formations;
  } catch (error) {
    console.error('Error fetching related formations:', error);
    relatedFormations.value = [];
  }
};

// Initialize on component mount
onMounted(() => {
  checkUserPaymentStatus();
  fetchRelatedFormations();
});
</script>