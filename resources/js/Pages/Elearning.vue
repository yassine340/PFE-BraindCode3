<script setup>
import { ref, computed } from 'vue';

const user = ref({ name: '', avatar: 'https://source.unsplash.com/100x100/?user' });
const courses = ref([
    { 
        title: "Vue.js Basics", 
        description: "Learn the fundamentals of Vue.js.", 
        image: "/images/vuejs.png",
        price: "$49.99",
        progress: 45,
        rating: 4.5,
        completed: false
    },
    { 
        title: "Laravel API Development", 
        description: "Build APIs with Laravel.", 
        image:"/images/laravel.jpg",
        price: "$59.99",
        progress: 75,
        rating: 4.8,
        completed: false
    },
    { 
        title: "Photography Basics", 
        description: "Master the art of photography.", 
        image: "/images/aperture.webp",
        price: "Free",
        progress: 100,
        rating: 5.0,
        completed: true
    }
]);

const recommendedCourses = computed(() => courses.value.filter(c => !c.completed));
</script>

<template>
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-10">
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto text-center p-6">
            <h1 class="text-4xl font-bold text-green-600">Start Learning Today!</h1>
            <p class="text-gray-700 dark:text-gray-300 mt-2">
                Explore the best online courses and enhance your skills.
            </p>
            <input
                type="text"
                placeholder="Search courses..."
                class="mt-4 p-2 w-2/3 md:w-1/2 rounded-lg border border-gray-300 dark:border-gray-600"
            />
        </div>

        <!-- Courses Section -->
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Popular Courses</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div v-for="course in courses" :key="course.title" class="bg-white shadow-lg p-4 rounded-lg">
                    <img :src="course.image" alt="Course" class="rounded-t-lg w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ course.title }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ course.description }}</p>
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-green-600 font-bold">{{ course.price }}</span>
                            <span class="text-yellow-500">⭐ {{ course.rating }}</span>
                        </div>
                        <div class="w-full bg-gray-200 h-2 rounded-lg mt-3">
                            <div class="bg-green-500 h-2 rounded-lg" :style="{ width: course.progress + '%' }"></div>
                        </div>
                        <button class="mt-4 w-full bg-green-500 text-white py-2 rounded-lg">
                            {{ course.completed ? 'Completed' : 'Enroll Now' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Courses Section -->
        <div class="max-w-7xl mx-auto px-6 mt-10">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Recommended For You</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div v-for="course in recommendedCourses" :key="course.title" class="bg-white shadow-md p-4 rounded-lg flex items-center">
                    <img :src="course.image" alt="Course" class="w-24 h-24 rounded-lg object-cover">
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold">{{ course.title }}</h3>
                        <p class="text-gray-600 text-sm">{{ course.description }}</p>
                        <div class="text-green-600 font-bold mt-1">{{ course.price }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 mt-10 py-4 bg-gray-200 dark:bg-gray-800">
            © 2025 E-Learning Platform. All rights reserved.
        </div>
    </div>
</template>