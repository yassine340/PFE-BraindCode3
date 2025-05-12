<!-- ExpertCard.vue -->
<template>
  <div class="expert-card">
    <div class="expert-image-container">
      <img :src="imageUrl" :alt="name" class="expert-image">
    </div>
    <h3 class="expert-name">{{ name }}</h3>
  </div>
</template>

<script>
export default {
  name: 'ExpertCard',
  props: {
    name: {
      type: String,
      required: true
    },
    imageUrl: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
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
</style>