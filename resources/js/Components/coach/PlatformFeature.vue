<template>
    <div class="text-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-all">
      <div class="flex justify-center mb-6">
        <div class="bg-blue-50 p-4 rounded-full">
          <img :src="imageUrl" :alt="title" class="h-14 w-14" />
        </div>
      </div>
      <h3 class="text-xl font-bold text-gray-800 mb-3">{{ title }}</h3>
      <p class="text-gray-600">{{ description }}</p>
    </div>
  </template>
  
  <script>
  export default {
    name: 'PlatformFeature',
    props: {
      imageUrl: {
        type: String,
        required: true
      },
      title: {
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