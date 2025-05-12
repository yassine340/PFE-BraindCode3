<template>
    <section class="bg-blue-50 py-16 testimonials-section">
      <div class="container mx-auto px-4 md:px-8">
        <!-- Animated heading with slide-in and underline effect -->
        <h2 class="text-3xl font-bold text-blue-600 text-center mb-12 section-heading">
          Ce que disent nos investisseurs
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div 
            v-for="(testimonial, index) in testimonials" 
            :key="index"
            class="testimonial-wrapper"
            :style="{ animationDelay: `${index * 200}ms` }"
          >
            <TestimonialCard 
              :initials="testimonial.initials"
              :name="testimonial.name"
              :role="testimonial.role"
              :quote="testimonial.quote"
              :rating="testimonial.rating"
            />
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import TestimonialCard from './TestimonialCard.vue';
  
  export default {
    name: 'TestimonialsSection',
    components: {
      TestimonialCard
    },
    data() {
      return {
        testimonials: [
          {
            initials: 'MB',
            name: 'Michel Bouvier',
            role: 'Business Angel',
            quote: 'BrainCode m\'a permis d\'accéder à des startups innovantes qui correspondent parfaitement à ma stratégie d\'investissement. Le processus est fluide et transparent.',
            rating: 5
          },
          {
            initials: 'SL',
            name: 'Sophie Laurent',
            role: 'Fonds d\'investissement',
            quote: 'La qualité des startups proposées et les outils de suivi mis à disposition font de BrainCode une plateforme incontournable pour tout investisseur sérieux.',
            rating: 5
          }
        ]
      }
    }
  }
  </script>
  
  <style scoped>
  /* Background subtle animation */
  .testimonials-section {
    position: relative;
    overflow: hidden;
  }
  
  .testimonials-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(219, 234, 254, 0.8) 0%, rgba(239, 246, 255, 0.5) 50%, rgba(219, 234, 254, 0) 100%);
    animation: rotate 60s linear infinite;
    z-index: 0;
  }
  
  @keyframes rotate {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
  
  .container {
    position: relative;
    z-index: 1;
  }
  
  /* Heading animation */
  .section-heading {
    opacity: 0;
    transform: translateY(-20px);
    animation: fadeInDown 0.8s ease forwards;
    position: relative;
  }
  
  .section-heading::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, rgba(37, 99, 235, 0), rgba(37, 99, 235, 1), rgba(37, 99, 235, 0));
    transform: translateX(-50%);
    animation: expandWidth 1.5s ease forwards 0.8s;
  }
  
  @keyframes fadeInDown {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  @keyframes expandWidth {
    to {
      width: 120px;
    }
  }
  
  /* Testimonial card container animations */
  .testimonial-wrapper {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
    animation: fadeInUp 0.8s ease forwards;
  }
  
  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }
  
  /* Alternate animation for odd/even cards */
  .testimonial-wrapper:nth-child(odd) {
    animation-name: fadeInLeft;
  }
  
  .testimonial-wrapper:nth-child(even) {
    animation-name: fadeInRight;
  }
  
  @keyframes fadeInLeft {
    from {
      opacity: 0;
      transform: translateX(-30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  @keyframes fadeInRight {
    from {
      opacity: 0;
      transform: translateX(30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  /* Responsive animations for mobile */
  @media (max-width: 768px) {
    .testimonial-wrapper {
      animation-name: fadeInUp !important;
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
  }
  </style>