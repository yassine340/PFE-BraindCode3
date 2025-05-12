<template>
    <Head title="404 Not Found" />
    <!-- Header -->
    <AppHeader 
      :canLogin="canLogin" 
      :canRegister="canRegister"  
    />
    <div class="container">
      <h1 class="first-four">4</h1>
      <div class="cog-wheel1">
        <div class="cog1">
          <div class="top"></div>
          <div class="down"></div>
          <div class="left-top"></div>
          <div class="left-down"></div>
          <div class="right-top"></div>
          <div class="right-down"></div>
          <div class="left"></div>
          <div class="right"></div>
        </div>
      </div>
      
      <div class="cog-wheel2"> 
        <div class="cog2">
          <div class="top"></div>
          <div class="down"></div>
          <div class="left-top"></div>
          <div class="left-down"></div>
          <div class="right-top"></div>
          <div class="right-down"></div>
          <div class="left"></div>
          <div class="right"></div>
        </div>
      </div>
      <h1 class="second-four">4</h1>
      <p class="wrong-para">Oh oh ! Page non trouvée !</p>
      
      <!-- Button Container -->
      <div class="button-container">
        <button @click="goBack" class="back-button pulse">Retour</button>
      </div>
    </div>
    <Footer />
  </template>
  
  <script>
  import { onMounted } from 'vue';
  import Footer from "@/Components/Footer.vue";
  import { Head } from "@inertiajs/inertia-vue3";
  import AppHeader from "@/Components/Welcome/AppHeader.vue";
  
export default {
  name: 'ErrorPage404',
  props: {
    canLogin: Boolean,
    canRegister: Boolean,
  },
  components: {
      Footer,
      Head,
    AppHeader
    },
    setup() {
      onMounted(() => {
        // Initialize animations with CSS instead of GSAP
        // The animations are now handled by CSS
      });
      
      // Function to go back to previous page
      const goBack = () => {
        window.history.back();
      };
      
      return {
        goBack
      };
    }
  }
  </script>
  
  <style scoped>
  @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap");
  @import url("https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700");
  
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    overflow: hidden;
    background-color: #f4f6ff;
  }
  
  .container {
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: "Poppins", sans-serif;
    position: relative;
    left: 6vmin;
    text-align: center;
  }
  
  .cog-wheel1, .cog-wheel2 {
    transform: scale(0.7);
  }
  
  .cog1, .cog2 {
    width: 40vmin;
    height: 40vmin;
    border-radius: 50%;
    border: 6vmin solid #f3c623;
    position: relative;
  }
  
  .cog2 {
    border: 6vmin solid #4f8a8b;
  }
  
  .top, .down, .left, .right, .left-top, .left-down, .right-top, .right-down {
    width: 10vmin;
    height: 10vmin;
    background-color: #f3c623;
    position: absolute;
  }
  
  .cog2 .top, .cog2 .down, .cog2 .left, .cog2 .right, .cog2 .left-top, .cog2 .left-down, .cog2 .right-top, .cog2 .right-down {
    background-color: #4f8a8b;
  }
  
  .top {
    top: -14vmin;
    left: 9vmin;
  }
  
  .down {
    bottom: -14vmin;
    left: 9vmin;
  }
  
  .left {
    left: -14vmin;
    top: 9vmin;
  }
  
  .right {
    right: -14vmin;
    top: 9vmin;
  }
  
  .left-top {
    transform: rotateZ(-45deg);
    left: -8vmin;
    top: -8vmin;
  }
  
  .left-down {
    transform: rotateZ(45deg);
    left: -8vmin;
    top: 25vmin;
  }
  
  .right-top {
    transform: rotateZ(45deg);
    right: -8vmin;
    top: -8vmin;
  }
  
  .right-down {
    transform: rotateZ(-45deg);
    right: -8vmin;
    top: 25vmin;
  }
  
  .cog2 {
    position: relative;
    left: -10.2vmin;
    bottom: 10vmin;
  }
  
  h1 {
    color: #142833;
  }
  
  .first-four {
    position: relative;
    left: 6vmin;
    font-size: 40vmin;
  }
  
  .second-four {
    position: relative;
    right: 18vmin;
    z-index: -1;
    font-size: 40vmin;
  }
  
  .wrong-para {
    font-family: "Montserrat", sans-serif;
    position: absolute;
    bottom: 15vmin;
    padding: 3vmin 12vmin 3vmin 3vmin;
    font-weight: 600;
    color: #092532;
  }
  
  /* Button Container Styles */
  .button-container {
    position: absolute;
    bottom: 5vmin;
    width: 100%;
    display: flex;
    justify-content: center;
    z-index: 10;
  }
  
  /* Back Button Styles - Matching your contact page style */
  .back-button {
    background-color: #0088ff;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.1s;
  }
  
  .back-button:hover {
    background-color: #0077e6;
  }
  
  .back-button:active {
    transform: scale(0.98);
  }
  
  /* CSS Animations to replace GSAP */
  .cog1 {
    animation: spin 8s linear infinite;
    transform-origin: center;
  }
  
  .cog2 {
    animation: spin-reverse 8s linear infinite;
    transform-origin: center;
  }
  
  .wrong-para {
    animation: blink 2s ease-in-out infinite;
  }
  
  /* Pulse animation for the button */
  .pulse:hover {
    animation: pulse 1.5s infinite;
  }
  
  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
  
  @keyframes spin-reverse {
    to {
      transform: rotate(-360deg);
    }
  }
  
  @keyframes blink {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: 0.7;
    }
  }
  
  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(0, 136, 255, 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(0, 136, 255, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(0, 136, 255, 0);
    }
  }
  
  /* Media queries */
  @media (max-width: 768px) {
    .back-button {
      padding: 10px 20px;
      font-size: 14px;
    }
  }
  
  @media (max-width: 480px) {
    .button-container {
      padding: 0 10%;
    }
    
    .back-button {
      width: 100%;
    }
  }
  </style>