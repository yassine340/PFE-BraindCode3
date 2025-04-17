<template>
    <Link :href="href" class="inline-flex items-center justify-center px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
      <PlusIcon v-if="icon === 'plus'" class="w-5 h-5 mr-2" />
      <ArrowUpOnSquareIcon v-else-if="icon === 'upload'" class="w-5 h-5 mr-2" />
      <DocumentDuplicateIcon v-else-if="icon === 'document'" class="w-5 h-5 mr-2" />
      {{ text }}
    </Link>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  // Updated Heroicons v2 imports
  import { PlusIcon, ArrowUpOnSquareIcon, DocumentDuplicateIcon } from '@heroicons/vue/24/outline';
  
  defineProps({
    href: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      default: 'plus'
    },
    text: {
      type: String,
      required: true
    }
  });
  </script>