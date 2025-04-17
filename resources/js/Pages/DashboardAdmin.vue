<script setup>
import PaymentTransactionsAdmin from '@/Components/Admin/PaymentTransactionsAdmin.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

// Import our components 
import StatCard from '@/Components/Admin/StatCard.vue';
import QuickActions from '@/Components/Admin/QuickActions.vue';
import PendingFormations from '@/Components/Admin/PendingFormations.vue';
import RecentActivities from '@/Components/Admin/RecentActivities.vue';

const user = usePage().props.auth.user;
const loading = ref(true);
const error = ref(null);

const stats = ref({
  totalUsers: 0,
  totalFormations: 0,
  formationsEnAttente: 0,
  revenus: 0
});

const payments = ref([]);
const pendingFormations = ref([]);
const recentActivities = ref([]);

// Format currency
const formatCurrency = (amount, currency = 'EUR') => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: currency.toUpperCase()
  }).format(amount);
};

// Charger les statistiques et données
onMounted(async () => {
  try {
    loading.value = true;
    
    // Fetch payment data for revenue calculation
    const paymentsResponse = await axios.get('/payments');
    payments.value = paymentsResponse.data;
    
    // Calculate total revenue from completed payments
    const completedPayments = payments.value.filter(p => p.status === 'completed');
    const totalRevenue = completedPayments.reduce((sum, p) => sum + parseFloat(p.amount), 0);

    // Fetch user count data
    const usersResponse = await axios.get('/users/count');
    const totalUsers = usersResponse.data.count;

    // fetch formations count data
    const formationsResponse = await axios.get('/formations/count');
    const totalFormations = formationsResponse.data.count;
    
    //
    const formateurEnAttenteResponse = await axios.get('/api/formateur-en-attente');
    const totalFormateurEnAttente = formateurEnAttenteResponse.data.count;

    // Fetch formations en attente using your existing API endpoint
    const pendingFormationsResponse = await axios.get('/api/formations-en-attente');
    let formationsData = pendingFormationsResponse.data;
    
    // If the data is wrapped in a 'formations' property (common with Inertia responses)
    if (formationsData && typeof formationsData === 'object' && formationsData.formations) {
      formationsData = formationsData.formations;
    }
    
    // Ensure we have an array before using map
    if (Array.isArray(formationsData)) {
      pendingFormations.value = formationsData.map(formation => ({
        id: formation.id,
        titre: formation.titre,
        auteur: formation.user ? `${formation.user.first_name} ${formation.user.last_name}` : 'Unknown',
        date: formation.created_at,
        category: formation.category ? formation.category.name : 'Non catégorisée',
      }));
    } else {
      console.error('Expected an array for formations but got:', formationsData);
      pendingFormations.value = [];
    }
   
    // Update stats with real data
    stats.value = {
      totalUsers: totalUsers,
      totalFormations: totalFormations,
      formationsEnAttente: totalFormateurEnAttente,
      revenus: totalRevenue
    };
    
    // Activités récentes
    recentActivities.value = [
      { type: 'inscription', user: 'Ahmed Benali', date: '2025-04-03', details: 's\'est inscrit à la plateforme' },
      { type: 'formation', user: 'Laura Petit', date: '2025-04-03', details: 'a créé une nouvelle formation "Cybersécurité 101"' },
      { type: 'achat', user: 'Marc Dupont', date: '2025-04-02', details: 'a acheté la formation "Marketing Digital"' },
      { type: 'validation', user: 'Admin', date: '2025-04-01', details: 'a validé la formation "Développement Mobile avec Flutter"' },
    ];
  } catch (error) {
    console.error('Erreur lors du chargement des données', error);
    error.value = "Une erreur s'est produite lors du chargement des données.";
  } finally {
    loading.value = false;
  }
});

// Alternative approach: use your existing getFormationsEnAttente method
const loadPendingFormations = async () => {
  try {
    const response = await axios.get('/formations-en-attente');
    const data = response.data;
    
    // If the data contains the props (Inertia structure)
    if (data && data.props && data.props.formations) {
      pendingFormations.value = data.props.formations.map(formation => ({
        id: formation.id,
        titre: formation.titre,
        auteur: formation.user ? `${formation.user.first_name} ${formation.user.last_name}` : 'Unknown',
        date: formation.created_at,
        category: formation.category ? formation.category.name : 'Non catégorisée',
        image: formation.image_formation
      }));
    }
  } catch (error) {
    console.error('Error loading pending formations', error);
  }
};

// Fonction pour valider une formation
const validateFormation = async (data) => {
  const { id, isValid } = data;
  try {
    if (isValid) {
      // Utiliser votre endpoint de validation existant
      await axios.post(`/formations/${id}/valider`);
    } else {
      // Utiliser votre endpoint de rejet existant
      await axios.post(`/formations/${id}/rejeter`);
    }
    
    // Mettre à jour la liste des formations en attente
    pendingFormations.value = pendingFormations.value.filter(f => f.id !== id);
    
    // Mettre à jour les statistiques
    stats.value.formationsEnAttente = pendingFormations.value.length;
    
    // Ajouter une notification
    alert(`Formation ${isValid ? 'validée' : 'rejetée'} avec succès!`);
    
    // Ajouter cette action aux activités récentes
    recentActivities.value.unshift({
      type: 'validation',
      user: user.first_name + ' ' + user.last_name,
      date: new Date().toISOString().split('T')[0],
      details: `a ${isValid ? 'validé' : 'rejeté'} la formation "${pendingFormations.value.find(f => f.id === id)?.titre}"`
    });
    
  } catch (error) {
    console.error('Erreur lors de la validation', error);
    alert('Une erreur est survenue lors de la validation');
  }
};

// Icons for StatCard components - using the SVGs directly from your first file
const usersIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>`;

const formationsIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>`;

const pendingIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`;

const revenueIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`;
</script>

<template>
  <Head title="Dashboard Administrateur" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Tableau de bord administrateur
        </h2>
        <div class="flex items-center space-x-2">
          <span class="text-sm text-gray-600">Dernière connexion: Aujourd'hui, 09:45</span>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div v-if="loading" class="flex justify-center items-center h-64">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>
        
        <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ error }}
        </div>
        
        <div v-else>
          <!-- Cartes de statistiques -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <StatCard title="Utilisateurs" :value="stats.totalUsers" :iconHtml="usersIcon" color="blue" trend="+12% ce mois" />
            <StatCard title="Formations" :value="stats.totalFormations" :iconHtml="formationsIcon" color="purple" trend="+5 nouvelles" />
            <StatCard title="En attente" :value="stats.formationsEnAttente" :iconHtml="pendingIcon" color="amber">
              <template #extra>
                <Link href="/formateurs-en-attente" class="mt-2 block text-amber-600 text-sm font-medium hover:underline">
                  Voir les formations en attente →
                </Link>
              </template>
            </StatCard>
            <StatCard title="Revenus" :value="`${stats.revenus}€`" :iconHtml="revenueIcon" color="green" trend="+23% ce mois" />
          </div>

          <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <QuickActions />
            <PendingFormations :formations="pendingFormations" @validate-formation="validateFormation"/>
          </div>
          <div class="mt-6">
            <RecentActivities :activities="recentActivities"/>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>