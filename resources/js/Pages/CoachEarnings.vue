<template>
  <Head title="Gains des entraîneurs" />
  <AuthenticatedLayout>
    <div class="max-w-6xl mx-auto p-4 sm:p-6">
      <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#005183] mb-4 sm:mb-0">
            Gains des entraîneurs
          </h1>
          
          <div class="flex space-x-2">
            <button 
              @click="refreshData" 
              class="flex items-center px-4 py-2 bg-blue-50 text-[#005183] rounded-lg hover:bg-blue-100 transition-colors"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Actualiser
            </button>
            <button 
              @click="exportCSV" 
              class="flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Exporter
            </button>
          </div>
        </div>

        <!-- Stats Cards -->
        <div v-if="coaches.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-blue-50 rounded-xl p-4 shadow-sm">
            <p class="text-sm text-blue-700 font-medium mb-1">Total des entraîneurs</p>
            <p class="text-2xl font-bold text-[#005183]">{{ coaches.length }}</p>
          </div>
          <div class="bg-green-50 rounded-xl p-4 shadow-sm">
            <p class="text-sm text-green-700 font-medium mb-1">Montant total des gains</p>
            <p class="text-2xl font-bold text-green-700">{{ formatCurrency(totalEarnings) }}</p>
          </div>
          <div class="bg-purple-50 rounded-xl p-4 shadow-sm">
            <p class="text-sm text-purple-700 font-medium mb-1">Gain moyen par entraîneur</p>
            <p class="text-2xl font-bold text-purple-700">{{ formatCurrency(averageEarnings) }}</p>
          </div>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
              </svg>
            </div>
            <input 
              type="text" 
              v-model="searchQuery" 
              class="block w-full p-2 pl-10 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" 
              placeholder="Rechercher un entraîneur..."
            />
          </div>
        </div>

        <!-- Table -->
        <div v-if="filteredCoaches.length > 0" class="overflow-x-auto rounded-xl border border-gray-200">
          <table class="min-w-full table-auto border-collapse">
            <thead class="sticky top-0 bg-gradient-to-r from-blue-100 to-blue-50 text-[#005183] text-sm uppercase z-10">
              <tr>
                <th class="px-6 py-4 text-left cursor-pointer" @click="sortBy('last_name')">
                  <div class="flex items-center">
                    Nom de l'entraîneur
                    <svg v-if="sortField === 'last_name'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" :class="{'rotate-180': !sortAsc}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                  </div>
                </th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left cursor-pointer" @click="sortBy('total_money')">
                  <div class="flex items-center">
                    Bénéfice total (€)
                    <svg v-if="sortField === 'total_money'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" :class="{'rotate-180': !sortAsc}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="coach in filteredCoaches"
                :key="coach.id"
                class="border-t hover:bg-blue-50 transition duration-200"
              >
                <td class="px-6 py-4 font-medium text-gray-800 whitespace-nowrap">
                  {{ coach.first_name }} {{ coach.last_name }}
                </td>
                <td class="px-6 py-4 text-gray-600">{{ coach.email }}</td>
                <td class="px-6 py-4">
                  <span class="font-semibold" :class="getAmountColorClass(coach.total_money)">
                    {{ formatCurrency(coach.total_money) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="filteredCoaches.length > 0" class="flex justify-between items-center mt-6">
          <div class="text-sm text-gray-500">
            Affichage de {{ Math.min(currentPage * itemsPerPage, filteredCoaches.length) }} sur {{ filteredCoaches.length }} entraîneurs
          </div>
          <div class="flex space-x-2">
            <button 
              @click="currentPage = Math.max(1, currentPage - 1)" 
              :disabled="currentPage === 1"
              :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
              class="px-3 py-1 border rounded-md"
            >
              Précédent
            </button>
            <button 
              @click="currentPage = Math.min(Math.ceil(filteredCoaches.length / itemsPerPage), currentPage + 1)" 
              :disabled="currentPage >= Math.ceil(filteredCoaches.length / itemsPerPage)"
              :class="currentPage >= Math.ceil(filteredCoaches.length / itemsPerPage) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
              class="px-3 py-1 border rounded-md"
            >
              Suivant
            </button>
          </div>
        </div>

        <div v-else class="bg-blue-50 rounded-xl p-8 text-center mt-8">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-lg font-medium text-gray-700 mb-2">Aucun résultat trouvé</p>
          <p class="text-gray-500">
            {{ coaches.length ? "Essayez d'ajuster votre recherche." : "Aucun gain enregistré pour le moment." }}
          </p>
        </div>
      </div>
    </div>
    
    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-5 rounded-lg shadow-lg flex items-center">
        <svg class="animate-spin h-5 w-5 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Chargement...</span>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const coaches = ref([])
const loading = ref(true)
const searchQuery = ref('')
const sortField = ref('total_money')
const sortAsc = ref(false)
const currentPage = ref(1)
const itemsPerPage = ref(10)

const fetchCoachEarnings = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/coach/earnings')
    coaches.value = response.data
  } catch (error) {
    console.error('Erreur lors du chargement des gains des entraîneurs:', error)
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  fetchCoachEarnings()
}

const exportCSV = () => {
  // Create CSV content
  const headers = ['Nom', 'Email', 'Bénéfice Total (€)']
  const csvContent = [
    headers.join(','),
    ...filteredCoaches.value.map(coach => 
      [
        `${coach.first_name} ${coach.last_name}`, 
        coach.email, 
        coach.total_money
      ].join(',')
    )
  ].join('\n')
  
  // Create download link
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  
  link.setAttribute('href', url)
  link.setAttribute('download', `gains-entraineurs-${new Date().toISOString().slice(0, 10)}.csv`)
  link.style.visibility = 'hidden'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
    maximumFractionDigits: 2
  }).format(Number(value || 0))
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortAsc.value = !sortAsc.value
  } else {
    sortField.value = field
    sortAsc.value = true
  }
}

const getAmountColorClass = (amount) => {
  const num = Number(amount || 0)
  if (num > 5000) return 'text-green-600 text-lg'
  if (num > 1000) return 'text-green-500'
  return 'text-gray-700'
}

const totalEarnings = computed(() => {
  return coaches.value.reduce((sum, coach) => sum + Number(coach.total_money || 0), 0)
})

const averageEarnings = computed(() => {
  if (coaches.value.length === 0) return 0
  return totalEarnings.value / coaches.value.length
})

const filteredCoaches = computed(() => {
  let result = [...coaches.value]
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(coach => 
      `${coach.first_name} ${coach.last_name}`.toLowerCase().includes(query) ||
      coach.email.toLowerCase().includes(query)
    )
  }
  
  // Apply sorting
  result.sort((a, b) => {
    let valueA, valueB
    
    if (sortField.value === 'last_name') {
      valueA = `${a.last_name} ${a.first_name}`.toLowerCase()
      valueB = `${b.last_name} ${b.first_name}`.toLowerCase()
    } else if (sortField.value === 'total_money') {
      valueA = Number(a.total_money || 0)
      valueB = Number(b.total_money || 0)
    } else {
      valueA = a[sortField.value]
      valueB = b[sortField.value]
    }
    
    if (valueA < valueB) return sortAsc.value ? -1 : 1
    if (valueA > valueB) return sortAsc.value ? 1 : -1
    return 0
  })
  
  return result
})

const paginatedCoaches = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredCoaches.value.slice(start, end)
})

onMounted(() => {
  fetchCoachEarnings()
})
</script>