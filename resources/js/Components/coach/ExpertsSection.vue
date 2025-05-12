<!-- ExpertsSection.vue with staggered animation -->
<template>
  <section class="experts-section">
    <div class="container mx-auto px-4">
      <h2 class="section-title">Nos experts :</h2>
      
      <transition-group 
        tag="div" 
        class="experts-grid" 
        name="expert-list"
        appear
      >
        <div 
          v-for="(expert, index) in experts" 
          :key="index"
          class="expert-card"
          :style="{ animationDelay: `${index * 0.15}s` }"
        >
          <div class="expert-image-container">
            <img :src="expert.imageUrl" :alt="expert.name" class="expert-image">
          </div>
          <h3 class="expert-name">{{ expert.name }}</h3>
        </div>
      </transition-group>
    </div>
  </section>
</template>

<script>
export default {
  name: 'ExpertsSection',
  data() {
    return {
      experts: [
        { name: 'Ahmed Landolsi', imageUrl: '/image/coach/expert.jpeg' },
        { name: 'Sophie Martin', imageUrl: '/image/coach/expert.jpeg' },
        { name: 'Thomas Dubois', imageUrl: '/image/coach/expert.jpeg' },
        { name: 'Marie Chen', imageUrl: '/image/coach/expert.jpeg' }
      ]
    }
  }
}
</script>

<style scoped>
.experts-section {
  background-color: white;
  padding: 60px 0;
}

/* Heading Styles */
.section-title {
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 30px;
  color: #333;
  position: relative;
  display: inline-block;
  animation: slideInFromLeft 1s ease-out;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 0;
  height: 3px;
  background-color: #0099ff;
  animation: expandWidth 1.5s ease forwards;
  animation-delay: 0.5s;
}

/* Grid Layout */
.experts-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

/* Expert Card Styling */
.expert-card {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 20px;
  text-align: center;
  transition: all 0.3s ease;
  animation: fadeInUp 0.8s ease forwards;
  opacity: 0;
}

.expert-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
}

.expert-image-container {
  width: 120px;
  height: 120px;
  margin: 0 auto 15px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #0099ff;
}

.expert-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.expert-name {
  font-size: 18px;
  font-weight: 600;
  color: #333;
  margin: 0;
}

/* Animation keyframes */
@keyframes slideInFromLeft {
  from {
    transform: translateX(-50px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes expandWidth {
  from {
    width: 0;
  }
  to {
    width: 100px;
  }
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

/* Transition Group Animations */
.expert-list-enter-active, .expert-list-leave-active {
  transition: all 0.5s ease;
}

.expert-list-enter-from, .expert-list-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

/* Responsive Design */
@media (max-width: 1024px) {
  .experts-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .experts-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .experts-grid {
    grid-template-columns: 1fr;
  }
}
</style>