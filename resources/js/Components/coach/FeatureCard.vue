<!-- FeatureCard.vue with improved animations -->
<template>
  <div class="card-container">
    <div class="feature-card bg-white rounded-xl shadow-md hover:shadow-xl transition-all p-6 border-t-4 border-blue-500 transform hover:-translate-y-2">
      <div class="icon-container bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
        <svg v-if="icon === 'bolt'" xmlns="http://www.w3.org/2000/svg" class="icon h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        <svg v-else-if="icon === 'eye'" xmlns="http://www.w3.org/2000/svg" class="icon h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <svg v-else-if="icon === 'money'" xmlns="http://www.w3.org/2000/svg" class="icon h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <svg v-else-if="icon === 'network'" xmlns="http://www.w3.org/2000/svg" class="icon h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
      </div>
      <h3 class="text-xl font-bold text-gray-800 mb-3">{{ title }}</h3>
      <p class="text-gray-600">
        {{ description }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FeatureCard',
  props: {
    icon: {
      type: String,
      required: true,
      validator: (value) => ['bolt', 'eye', 'money', 'network'].includes(value)
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

<style scoped>
.card-container {
  animation: fadeInUp 0.8s ease forwards;
  opacity: 0;
}

.feature-card {
  transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  position: relative;
  overflow: hidden;
}

.feature-card:hover {
  border-color: #0099ff;
}

.feature-card:hover .icon-container {
  background-color: #0099ff;
}

.feature-card:hover .icon {
  color: white;
  transform: rotateY(360deg);
}

.icon-container {
  transition: background-color 0.5s ease;
}

.icon {
  transition: color 0.5s ease, transform 1s ease;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulseIcon {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}
</style>