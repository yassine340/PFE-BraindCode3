<template>
    <div class="bg-white rounded-lg p-6 shadow-md testimonial-card">
      <!-- Avatar and user info with hover animation -->
      <div class="flex items-center mb-4">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4 avatar-container">
          <span class="text-blue-600 font-bold initials-text">{{ initials }}</span>
        </div>
        <div class="user-info">
          <h4 class="font-medium name-text">{{ name }}</h4>
          <p class="text-gray-500 text-sm role-text">{{ role }}</p>
        </div>
      </div>
      
      <!-- Quote with typing effect -->
      <p class="text-gray-600 italic quote-text">"{{ quote }}"</p>
      
      <!-- Stars with pop animation -->
      <div class="flex mt-4 stars-container">
        <svg 
          v-for="i in rating" 
          :key="i" 
          xmlns="http://www.w3.org/2000/svg" 
          class="h-5 w-5 text-yellow-400 star"
          :style="{ animationDelay: `${(i - 1) * 100}ms` }"
          viewBox="0 0 20 20" 
          fill="currentColor"
        >
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
      </div>
      
      <!-- Subtle quotation marks -->
      <div class="absolute top-2 left-2 text-6xl text-blue-100 opacity-70 quote-mark">"</div>
      <div class="absolute bottom-2 right-4 text-6xl text-blue-100 opacity-70 quote-mark">"</div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'TestimonialCard',
    props: {
      initials: {
        type: String,
        required: true
      },
      name: {
        type: String,
        required: true
      },
      role: {
        type: String,
        required: true
      },
      quote: {
        type: String,
        required: true
      },
      rating: {
        type: Number,
        default: 5
      }
    }
  }
  </script>
  
  <style scoped>
  .testimonial-card {
    position: relative;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    overflow: hidden;
  }
  
  /* Card hover animation */
  .testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  }
  
  /* Avatar container animations */
  .avatar-container {
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
  }
  
  .avatar-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.5);
    transform: scale(0);
    transition: transform 0.3s ease;
    z-index: -1;
  }
  
  .testimonial-card:hover .avatar-container {
    transform: scale(1.1);
  }
  
  .testimonial-card:hover .avatar-container::after {
    transform: scale(1.3);
    opacity: 0;
    transition: transform 0.8s ease, opacity 0.8s ease;
  }
  
  /* Initials animation */
  .initials-text {
    transition: all 0.3s ease;
  }
  
  .testimonial-card:hover .initials-text {
    transform: scale(1.2);
    text-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
  }
  
  /* Name and role animation */
  .name-text {
    transition: all 0.3s ease;
    position: relative;
    display: inline-block;
  }
  
  .name-text::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background-color: #3b82f6;
    transition: width 0.3s ease;
  }
  
  .testimonial-card:hover .name-text::after {
    width: 100%;
  }
  
  .role-text {
    transition: all 0.3s ease;
  }
  
  .testimonial-card:hover .role-text {
    color: #3b82f6;
  }
  
  /* Quote text animation */
  .quote-text {
    position: relative;
    transition: all 0.3s ease;
    z-index: 1;
  }
  
  .testimonial-card:hover .quote-text {
    transform: scale(1.02);
  }
  
  /* Star rating animation */
  .star {
    opacity: 0;
    transform: scale(0);
    animation: popIn 0.5s ease forwards;
  }
  
  @keyframes popIn {
    0% {
      opacity: 0;
      transform: scale(0);
    }
    70% {
      transform: scale(1.2);
    }
    100% {
      opacity: 1;
      transform: scale(1);
    }
  }
  
  /* Quote marks animation */
  .quote-mark {
    font-family: serif;
    transition: all 0.5s ease;
    z-index: 0;
  }
  
  .testimonial-card:hover .quote-mark:first-child {
    transform: translateX(-5px) translateY(-5px) scale(1.1);
  }
  
  .testimonial-card:hover .quote-mark:last-child {
    transform: translateX(5px) translateY(5px) scale(1.1);
  }
  
  /* Shine effect on hover */
  .testimonial-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
      to right,
      rgba(255, 255, 255, 0) 0%,
      rgba(255, 255, 255, 0.3) 50%,
      rgba(255, 255, 255, 0) 100%
    );
    transform: skewX(-25deg);
    transition: all 0s;
  }
  
  .testimonial-card:hover::before {
    left: 100%;
    transition: all 0.7s ease;
  }
  </style>