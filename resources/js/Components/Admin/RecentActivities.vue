<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  activities: {
    type: Array,
    default: () => []
  }
});
</script>

<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-teal-600">
      <h3 class="text-white font-semibold text-lg">Activités récentes</h3>
    </div>
    <div class="p-6">
      <div class="flow-root">
        <ul class="-mb-8">
          <li v-for="(activity, activityIdx) in activities" :key="activityIdx">
            <div class="relative pb-8">
              <span v-if="activityIdx !== activities.length - 1" class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
              <div class="relative flex items-start space-x-3">
                <!-- Icône d'activité -->
                <div class="relative">
                  <div class="h-10 w-10 rounded-full flex items-center justify-center" 
                        :class="{
                          'bg-blue-100': activity.type === 'inscription',
                          'bg-purple-100': activity.type === 'formation',
                          'bg-green-100': activity.type === 'achat',
                          'bg-amber-100': activity.type === 'validation'
                        }">
                    <svg v-if="activity.type === 'inscription'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <svg v-if="activity.type === 'formation'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <svg v-if="activity.type === 'achat'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <svg v-if="activity.type === 'validation'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                
                <!-- Contenu de l'activité -->
                <div class="min-w-0 flex-1">
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ activity.user }}</div>
                    <p class="mt-0.5 text-sm text-gray-500">{{ activity.date }}</p>
                  </div>
                  <div class="mt-2 text-sm text-gray-700">
                    <p>{{ activity.details }}</p>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="mt-4 text-right">
        <Link href="/activities" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
          Voir toutes les activités →
        </Link>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Barre latérale de l'historique des activités */
.flow-root ul li:last-child .absolute.h-full {
  display: none;
}
</style>