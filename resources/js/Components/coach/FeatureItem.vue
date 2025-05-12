<template>
  <div class="feature-item">
    <div class="bullet-container">
      <span class="feature-bullet"></span>
    </div>
    <p class="feature-text">{{ text }}</p>
  </div>
</template>

<script>
export default {
  name: 'FeatureItem',
  props: {
    text: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
/* Feature Item Styling */
.feature-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 10px;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.feature-item:hover {
  background-color: rgba(0, 153, 255, 0.05);
  transform: translateX(5px);
}

/* Bullet container to manage the pulse effect */
.bullet-container {
  position: relative;
  width: 16px;
  height: 16px;
  margin-top: 5px;
}

/* Blue Bullet Point with pulse animation */
.feature-bullet {
  position: absolute;
  width: 16px;
  height: 16px;
  background-color: #0099ff;
  border-radius: 2px;
  top: 0;
  left: 0;
}

.feature-bullet::after {
  content: '';
  position: absolute;
  inset: -3px;
  border-radius: 4px;
  background-color: #0099ff;
  opacity: 0;
  z-index: -1;
  animation: pulse 2s infinite;
}

.feature-item:hover .feature-bullet {
  transform: scale(1.1);
}

/* Feature Text */
.feature-text {
  margin: 0;
  font-size: 16px;
  line-height: 1.5;
  color: #333;
}

/* Pulse animation for the bullet point */
@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 0.2;
  }
  70% {
    transform: scale(1.3);
    opacity: 0;
  }
  100% {
    transform: scale(1.3);
    opacity: 0;
  }
}

/* Responsive text size */
@media (max-width: 768px) {
  .feature-text {
    font-size: 15px;
  }
}
</style>