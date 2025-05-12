<template>
  <section class="investor-features-heading bg-white pt-20 pb-6">
    <div class="container mx-auto px-4 md:px-8">
      <h2 class="heading-title">Devenez coach chez BrainCode !</h2>
      <p class="heading-description">Vous avez de l'expertise en entrepreneuriat, innovation ou financement ? Rejoignez BrainCode Startup Studio en tant que coach et accompagnez les startups dans leur croissance !</p>
      
      <!-- Optional animated underline element -->
      <div class="animated-underline"></div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'InvestorFeaturesSection'
}
</script>

<style scoped>
.investor-features-heading {
  position: relative;
  overflow: hidden;
}

.heading-title {
  font-size: 2rem;
  font-weight: bold;
  color: #2563eb; /* blue-600 */
  text-align: center;
  margin-bottom: 1rem;
  animation: fadeInDown 1s ease-out forwards;
  opacity: 0;
}

.heading-description {
  color: #4b5563; /* gray-600 */
  text-align: center;
  max-width: 42rem;
  margin-left: auto;
  margin-right: auto;
  animation: fadeInUp 1s ease-out forwards;
  animation-delay: 0.3s;
  opacity: 0;
}

.animated-underline {
  height: 3px;
  width: 0;
  background: linear-gradient(90deg, transparent, #2563eb, transparent);
  margin: 1rem auto 0;
  animation: expandWidth 1.5s ease forwards;
  animation-delay: 0.6s;
  max-width: 150px;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes expandWidth {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}

/* Add responsive adjustments */
@media (max-width: 768px) {
  .heading-title {
    font-size: 1.75rem;
  }
  
  .heading-description {
    font-size: 0.95rem;
  }
}
</style>