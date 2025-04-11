<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  links: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <div class="mt-6">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, index) in links" :key="index">
        <div v-if="link.url === null" 
             class="mr-1 mb-1 px-4 py-2 text-sm text-gray-400 border rounded"
             v-html="link.label">
        </div>
        
        <Link v-else
              :href="link.url" 
              class="mr-1 mb-1 px-4 py-2 text-sm border rounded hover:bg-blue-50 focus:outline-none" 
              :class="{ 'bg-blue-500 text-white hover:bg-blue-600': link.active }"
              v-html="link.label">
        </Link>
      </template>
    </div>
  </div>
</template>