<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600">
      <h3 class="text-white font-semibold text-lg">Actions rapides</h3>
    </div>
    <div class="p-6 grid grid-cols-2 gap-4">
      <Link href="/formations/create" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span class="text-sm font-medium text-gray-800">Nouvelle formation</span>
      </Link>
      
      <Link href="/admin/users" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span class="text-sm font-medium text-gray-800">Gérer utilisateurs</span>
      </Link>
      
      <Link href="/categorie" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
        <span class="text-sm font-medium text-gray-800">Catégories</span>
      </Link>
      
      <Link href="/coach/earnings" class="flex flex-col items-center justify-center p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <span class="text-sm font-medium text-gray-800">Gains des entraîneurs</span>
      </Link>
    </div>
  </div>
</template>