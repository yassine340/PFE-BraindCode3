<template>
  <div class="logo-container">
    <!-- Use the <image> element to display the image inside the SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 316 316" fill="none">
      <image href="/image/logos/Icon_BraindCode_Blue.png" width="316" height="316" />
    </svg>
    
    <!-- The name (text) below the SVG icon -->
    <h6 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mt-2">BraindCode</h6>
  </div>
</template>

<style scoped>
  .logo-container {
    text-align: center; /* Center the SVG and text */
    display: flex;
    flex-direction: column; /* Stack SVG and text vertically */
    align-items: center; /* Center items horizontally */
  }

  .logo-container svg {
    margin-bottom: 10px; /* Add some space between the icon and the text */
  }

  .logo-container h6 {
    margin-top: 10px; /* Optional: add margin to the text if you want more space */
  }
</style>
