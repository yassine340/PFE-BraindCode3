<script setup>
// Beneficiaries section data
const beneficiaries = [
  {
    image: "/image/startup/startups-group.jpeg",
    title: "Startups",
    description: "Accélérez votre développement et atteignez vos objectifs plus rapidement grâce à notre programme structuré.",
    buttonText: "Candidater au programme"
  },
  {
    image: "/image/startup/coaches-group.jpeg",
    title: "Coachs",
    description: "Rejoignez notre réseau de mentors et partagez votre expertise avec la prochaine génération d'entrepreneurs.",
    buttonText: "Devenir coach"
  },
  {
    image: "/image/startup/investors-group.jpeg",
    title: "Investisseurs",
    description: "Découvrez des opportunités d'investissement présélectionnées dans des startups à fort potentiel de croissance.",
    buttonText: "Explorer les opportunités"
  }
];
</script>

<template>
  <section class="py-16 bg-white ">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 text-custom-blue">
        Qui peut bénéficier de BrainCode Startup Studio
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div 
          v-for="(item, index) in beneficiaries" 
          :key="index"
          class="bg-white  rounded-lg shadow-md overflow-hidden transition hover:shadow-xl"
        >
          <img :src="item.image" :alt="item.title" class="w-full h-48 object-cover" />
          <div class="p-6">
            <h3 class="text-xl font-bold mb-3 text-center text-gray-900 text-custom-blue">
              {{ item.title }}
            </h3>
            <p class="text-gray-600  mb-6 text-center">
              {{ item.description }}
            </p>
            <div class="text-center">
              <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                {{ item.buttonText }}
              </button>
            </div>
          </div>
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