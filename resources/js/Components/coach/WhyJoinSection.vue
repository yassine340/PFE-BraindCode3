<template>
  <section class="why-join-section bg-blue-50 py-16">
    <div class="container mx-auto px-4 md:px-8">
      <h2 class="section-heading">Pourquoi nous rejoindre ?</h2>
      
      <!-- Animated underline element -->
      <div class="animated-underline"></div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'WhyJoinSection'
}
</script>

<style scoped>
.why-join-section {
  position: relative;
  overflow: hidden;
}

.section-heading {
  font-size: 2rem;
  font-weight: bold;
  color: #2563eb; /* blue-600 */
  text-align: center;
  margin-bottom: 3rem;
  position: relative;
  animation: fadeInUp 1s ease-out forwards;
}

.animated-underline {
  height: 4px;
  background: linear-gradient(90deg, transparent, #2563eb, transparent);
  width: 0;
  max-width: 200px;
  margin: -2rem auto 3rem;
  animation: expandFromCenter 1.5s ease-out forwards;
  animation-delay: 0.5s;
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

@keyframes expandFromCenter {
  from {
    width: 0;
  }
  to {
    width: 200px;
  }
}

/* Add a subtle background animation */
.why-join-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at center, rgba(219, 234, 254, 0.8) 0%, rgba(219, 234, 254, 0) 70%);
  opacity: 0;
  animation: pulseBackground 10s ease-in-out infinite;
}

@keyframes pulseBackground {
  0%, 100% {
    opacity: 0;
    transform: scale(0.8);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.2);
  }
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .section-heading {
    font-size: 1.75rem;
  }
  
  .animated-underline {
    max-width: 150px;
  }
  
  @keyframes expandFromCenter {
    from {
      width: 0;
    }
    to {
      width: 150px;
    }
  }
}
</style>