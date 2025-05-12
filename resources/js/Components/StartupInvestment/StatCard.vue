<template>
  <div class="text-center p-6 rounded-lg hover:shadow-md transition duration-300">
    <p class="text-4xl font-bold text-blue-600 mb-2">{{ number }}</p>
    <p class="text-gray-600">{{ description }}</p>
  </div>
</template>

<script>
export default {
  name: 'StatCard',
  props: {
    number: {
      type: String,
      required: true
    },
    description: {
      type: String,
      required: true
    }
  }
}
</script>
