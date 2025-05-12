<script setup>
// Services section data
const services = [
  {
    icon: "/image/startup/mentoring.png",
    title: "Accompagnement personnalisé",
    description: "Coaching par des entrepreneurs expérimentés"
  },
  {
    icon: "/image/startup/chart.png",
    title: "Suivi précis d'indicateurs",
    description: "KPIs et tableaux de bord pour mesurer votre progression"
  },
  {
    icon: "/image/startup/network.png",
    title: "Réseau d'experts",
    description: "Accès à notre communauté de spécialistes par domaine"
  },
  {
    icon: "/image/startup/funding.png",
    title: "Accès aux financements",
    description: "Mise en relation avec investisseurs et business angels"
  }
];
</script>

<template>
  <section class="py-16 bg-gray-50" style="background: linear-gradient(to bottom, #F1F9FF)">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 text-custom-blue">
        Que proposons-nous?
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="(service, index) in services" 
          :key="index"
          class="bg-white  rounded-lg shadow-md p-6 transition hover:shadow-lg hover:transform hover:scale-105"
        >
          <div class="flex justify-center mb-4">
            <img :src="service.icon" :alt="service.title" class="w-16 h-16" />
          </div>
          <h3 class="text-lg font-semibold text-center mb-2 text-gray-900 text-custom-blue">
            {{ service.title }}
          </h3>
          <p class="text-gray-600  text-center">
            {{ service.description }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>
<style>
  .text-custom-blue {
    color: #003E64;
  }
</style> 