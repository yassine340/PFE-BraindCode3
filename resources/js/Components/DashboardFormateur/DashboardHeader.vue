<template>
    <div class="flex items-center">
      <Link class="mr-2 text-blue-600" href="/dashboard">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
      </Link>
      <h2 class="text-xl font-semibold leading-tight text-black dark:text-gray-200">
        Tableau de bord
      </h2>
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  </script>