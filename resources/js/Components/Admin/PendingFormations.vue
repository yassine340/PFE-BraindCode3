<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  formations: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['validate-formation']);

const validateFormation = (id, isValid) => {
  emit('validate-formation', { id, isValid });
};
</script>

<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600">
      <h3 class="text-white font-semibold text-lg">Formations en attente de validation</h3>
    </div>
    <div class="p-6">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formation</th>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="formation in formations" :key="formation.id">
              <td class="px-4 py-4 whitespace-nowrap">
                <Link :href="`/formations/${formation.id}`" class="text-indigo-600 hover:text-indigo-900 font-medium">
                  {{ formation.titre }}
                </Link>
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ formation.auteur }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                  {{ formation.category }}
                </span>
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ new Date(formation.date).toLocaleDateString('fr-FR') }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button @click="validateFormation(formation.id, true)" 
                          class="px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200">
                    Valider
                  </button>
                  <button @click="validateFormation(formation.id, false)" 
                          class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                    Rejeter
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="formations.length === 0">
              <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                Aucune formation en attente de validation.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-4 text-right">
        <Link href="/formations-en-attente" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
          Voir toutes les formations en attente →
        </Link>
      </div>
    </div>
  </div>
</template>