<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

// Slideshow logic
const slideIndex = ref(1);

function showSlides(n) {
    const slides = document.getElementsByClassName("mySlides");
    const dots = document.getElementsByClassName("dot");

    if (n > slides.length) slideIndex.value = 1;
    if (n < 1) slideIndex.value = slides.length;

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    slides[slideIndex.value - 1].style.display = "block";
    dots[slideIndex.value - 1].className += " active";
}

function plusSlides(n) {
    showSlides(slideIndex.value += n);
}

function currentSlide(n) {
    showSlides(slideIndex.value = n);
}

// Initialize slideshow on component mount
onMounted(() => {
    showSlides(slideIndex.value);
});
</script>
<template>
    <Head title="Slideshow" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <!-- Slideshow Container -->
        <div class="slideshow-container">
            <div class="mySlides fade">
                <div class="numbertext">1 / 3</div>
                <img src="https://example.com/images/nature.jpg" style="width:100%" alt="Nature">
                <div class="text">Caption Text</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">2 / 3</div>
                <img src="https://example.com/images/snow.jpg" style="width:100%" alt="Snow">
                <div class="text">Caption Two</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">3 / 3</div>
                <img src="https://example.com/images/mountains.jpg" style="width:100%" alt="Mountains">
                <div class="text">Caption Three</div>
            </div>

            <!-- Next and previous buttons -->
            <a class="prev" @click="plusSlides(-1)">❮</a>
            <a class="next" @click="plusSlides(1)">❯</a>
        </div>
        <br>

        <!-- The dots/circles -->
        <div style="text-align:center">
            <span class="dot" @click="currentSlide(1)"></span>
            <span class="dot" @click="currentSlide(2)"></span>
            <span class="dot" @click="currentSlide(3)"></span>
        </div>

        <!-- Rest of your existing content -->
        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
        </footer>
    </div>
</template>

<style scoped>
/* Slideshow styles */
* { box-sizing: border-box; }
body { font-family: Verdana, sans-serif; margin: 0; }
.mySlides { display: none; }
img { vertical-align: middle; }

/* Slideshow container */
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}

/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -22px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover, .next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active, .dot:hover {
  background-color: #717171;
}

/* Fading animation */
.fade {
  animation-name: fade;
  animation-duration: 1.5s;
}

@keyframes fade {
  from { opacity: .4; }
  to { opacity: 1; }
}

/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .prev, .next, .text { font-size: 11px; }
}
</style>