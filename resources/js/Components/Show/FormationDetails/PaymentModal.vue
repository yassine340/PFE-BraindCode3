<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Paiement pour la formation de {{ formation.titre }}</h2>
          <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="mb-6">
          <div class="text-lg font-semibold mb-2">Prix: {{ formation.prix }}€</div>
          
          <!-- Payment Method Selection Tabs -->
          <PaymentMethodTabs 
            :payment-method="paymentMethod" 
            @change-method="changePaymentMethod" 
          />
          
          <!-- Payment Error Messages -->
          <div v-if="paymentError" class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            {{ paymentError }}
          </div>
          
          <!-- Stripe Payment Form -->
          <StripePaymentForm 
            v-if="paymentMethod === 'stripe'"
            :payment-info="paymentInfo"
            :payment-processing="paymentProcessing"
            :card-element-complete="cardElementComplete"
            :countries="countries"
            :cities="cities"
            :loading-countries="loadingCountries"
            :loading-cities="loadingCities"
            @process-payment="processStripePayment"
          />
          
          <!-- PayPal Payment -->
          <!-- PayPal Payment -->
          <PaypalPaymentForm
            v-else-if="paymentMethod === 'paypal'"
            :payment-processing="paymentProcessing"
            :paypal-approval-url="paypalApprovalUrl"
            :payment-info="paymentInfo"
            :countries="countries"
            :cities="cities"
            :loading-countries="loadingCountries"
            :loading-cities="loadingCities"
            @redirect-to-paypal="redirectToPayPal"
          />
        </div>
        
        <div class="text-xs text-gray-500 text-center">
          <p>Tous les paiements sont sécurisés et cryptés.</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, reactive, onMounted, watch } from 'vue';
  import axios from 'axios';
  import { loadStripe } from '@stripe/stripe-js';
  import PaymentMethodTabs from './PaymentMethodTabs.vue';
  import StripePaymentForm from './StripePaymentForm.vue';
  import PaypalPaymentForm from './PaypalPaymentForm.vue';
  
  // Initialize Stripe with your publishable key
  const stripePromise = loadStripe('pk_test_51R9NvWKl52qI29Hq2IJtzjQIqtqc4lTBar7hDW0jPkPlrdCiX7x8LHVb35ZtwTCG0wOoSfk6SSj6G8Jq6tJQTihO00Xet74o7d');
  
  interface Formation {
    id: number;
    titre: string;
    prix: number;
  }
  
  const props = defineProps<{
    show: boolean;
    formation: Formation;
    userId: number;
    paymentProcessing: boolean;
  }>();
  
  const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'payment-success'): void;
  }>();
  
  // Payment state variables
  const paymentError = ref('');
  const clientSecret = ref('');
  const paymentMethod = ref('stripe'); // 'stripe' or 'paypal'
  const paypalApprovalUrl = ref('');
  const cardElementComplete = ref(false);
  const cardElement = ref(null);
  const stripe = ref(null);
  const elements = ref(null);
  
  // Location data
  const countries = ref([]);
  const cities = ref([]);
  const loadingCountries = ref(false);
  const loadingCities = ref(false);
  
  // Payment info form
  const paymentInfo = reactive({
    name: '',
    address: '',
    postal_code: '',
    city: '',
    country: '',
  });
  
  // Function to fetch countries list
  const fetchCountries = async () => {
    loadingCountries.value = true;
    try {
      const response = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2');
      const data = await response.json();
      
      countries.value = data
        .map(country => ({
          code: country.cca2,
          name: country.name.common
        }))
        .sort((a, b) => a.name.localeCompare(b.name));
    } catch (error) {
      console.error('Erreur lors de la récupération des pays:', error);
    } finally {
      loadingCountries.value = false;
    }
  };
  
  // Function to fetch cities based on country
  const fetchCities = async (countryCode) => {
    if (!countryCode) return;
    
    loadingCities.value = true;
    cities.value = [];
    paymentInfo.city = ''; // Reset selected city
    
    try {
      const username = 'fakhri'; // Replace with your geonames.org username
      const response = await fetch(`http://api.geonames.org/searchJSON?country=${countryCode}&featureClass=P&maxRows=1000&username=${username}`);
      const data = await response.json();
      
      cities.value = data.geonames
        .map(city => ({
          name: city.name,
          id: city.geonameId
        }))
        .sort((a, b) => a.name.localeCompare(b.name));
    } catch (error) {
      console.error('Erreur lors de la récupération des villes:', error);
    } finally {
      loadingCities.value = false;
    }
  };
  
  // Method to change payment method
  const changePaymentMethod = (method) => {
    paymentMethod.value = method;
    
    if (method === 'stripe') {
      setupStripe();
    } else if (method === 'paypal') {
      setupPayPal();
    }
  };
  
  // Setup Stripe payment
  const setupStripe = async () => {
    paymentError.value = '';
    
    // Reset form fields
    paymentInfo.name = '';
    paymentInfo.address = '';
    paymentInfo.postal_code = '';
    paymentInfo.city = '';
    paymentInfo.country = '';
    cardElementComplete.value = false;
    clientSecret.value = '';
    cardElement.value = null;
    stripe.value = null;
    elements.value = null;
    
    if (!props.userId || !props.formation?.id) {
      paymentError.value = "Erreur: Utilisateur ou formation non disponible";
      return;
    }
    
    try {
      // Create payment intent on the server
      const response = await axios.post('/stripe/create-intent', {
        userId: props.userId,
        formationId: props.formation.id,
        amount: props.formation.prix * 100, // Convert to cents
        billing_details: {
          name: paymentInfo.name,
          address: {
            line1: paymentInfo.address,
            city: paymentInfo.city,
            postal_code: paymentInfo.postal_code,
            country: paymentInfo.country
          }
        }
      });
      
      clientSecret.value = response.data.clientSecret;
      
      // Initialize Stripe
      stripe.value = await stripePromise;
      elements.value = stripe.value.elements();
      
      // Create card element
      cardElement.value = elements.value.create('card', {
        style: {
          base: {
            fontSize: '16px',
            color: '#32325d',
            '::placeholder': {
              color: '#aab7c4',
            },
          },
          invalid: {
            color: '#fa755a',
            iconColor: '#fa755a',
          },
        },
      });
      
      // Mount the card element to the DOM
      setTimeout(() => {
        const cardElementContainer = document.getElementById('card-element');
        if (cardElementContainer && cardElement.value) {
          cardElement.value.mount('#card-element');
          cardElement.value.on('change', (event) => {
            cardElementComplete.value = event.complete;
            if (event.error) {
              paymentError.value = event.error.message;
            } else {
              paymentError.value = '';
            }
          });
        }
      }, 100);
      
    } catch (error) {
      console.error('Error setting up Stripe:', error);
      paymentError.value = "Erreur lors de l'initialisation du paiement";
    }
  };
  
  // Setup PayPal payment
  const setupPayPal = async () => {
    try {
      const response = await axios.post('/paypal/create-order', {
        userId: props.userId,
        formationId: props.formation.id,
        amount: props.formation.prix * 100 // Convert to cents like Stripe
      });
      
      if (response.data.approvalUrl) {
        paypalApprovalUrl.value = response.data.approvalUrl;
      } else {
        paymentError.value = 'Erreur lors de la création de la commande PayPal';
      }
    } catch (error) {
      console.error('PayPal setup error:', error);
      paymentError.value = 'Erreur lors de la préparation du paiement PayPal';
    }
  };
  
  // Redirect to PayPal for payment
  const redirectToPayPal = () => {
    if (paypalApprovalUrl.value) {
      // Store necessary info in localStorage before redirecting
      localStorage.setItem('pendingPayment', JSON.stringify({
        userId: props.userId,
        formationId: props.formation.id,
        paymentMethod: 'paypal',
        formationTitle: props.formation.titre,
        amount: props.formation.prix
      }));
      
      // Redirect to PayPal
      window.location.href = paypalApprovalUrl.value;
    }
  };
  
  // Process payment with Stripe
  const processStripePayment = async () => {
    if (!cardElementComplete.value) {
      paymentError.value = "Veuillez compléter les informations de carte";
      return;
    }
    
    if (!paymentInfo.address || !paymentInfo.postal_code || !paymentInfo.city || !paymentInfo.country) {
      paymentError.value = "Veuillez entrer l'adresse de facturation complète";
      return;
    }
    
    paymentError.value = '';
    
    try {
      // Confirm payment with Stripe.js
      const result = await stripe.value.confirmCardPayment(clientSecret.value, {
        payment_method: {
          card: cardElement.value,
          billing_details: {
            name: paymentInfo.name,
          }
        },
      });
      
      if (result.error) {
        // Show error to customer
        console.error("Stripe error:", result.error);
        paymentError.value = result.error.message;
      } else {
        if (result.paymentIntent.status === 'succeeded') {
          // Payment successful - confirm on server
          const confirmResponse = await axios.post('/stripe/confirm-payment', {
            paymentIntentId: result.paymentIntent.id,
            userId: props.userId,
            formationId: props.formation.id,
            Pays: paymentInfo.country,
            ville: paymentInfo.city,
            adresse: paymentInfo.address,
            code_postal: paymentInfo.postal_code        
          });
          
          if (confirmResponse.data.success) {
            emit('payment-success');
            alert("Paiement réussi ! Vous avez maintenant accès au contenu complet de la formation.");
          } else {
            paymentError.value = "Le paiement a été effectué mais une erreur est survenue lors de l'enregistrement.";
          }
        } else {
          paymentError.value = "Le paiement est en attente ou a échoué.";
        }
      }
    } catch (error) {
      console.error('Error processing payment:', error);
      paymentError.value = "Une erreur est survenue lors du traitement du paiement: " + (error.message || "Erreur inconnue");
    }
  };
  
  // Watch for changes in country to update cities
  watch(() => paymentInfo.country, (newCountry) => {
    if (newCountry) {
      fetchCities(newCountry);
    }
  });
  
  // Initialize on component mount
  onMounted(() => {
    fetchCountries();
    if (paymentMethod.value === 'stripe') {
      setupStripe();
    } else if (paymentMethod.value === 'paypal') {
      setupPayPal();
    }
  });
  </script>