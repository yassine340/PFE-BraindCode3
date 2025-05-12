<template>
    <div class="border border-gray-200 rounded-xl p-6 mt-6 bg-gray-50">
      <div class="flex items-center mb-4">
        <div class="bg-blue-600 h-8 w-8 rounded-full flex items-center justify-center mr-3">
          <span class="text-white font-bold">{{ currentLeconIndex + 1 }}</span>
        </div>
        <h3 class="text-xl font-bold text-gray-800">{{ currentLecon.titre }}</h3>
      </div>
      
      <div class="prose max-w-none mb-8">
        <p>{{ currentLecon.contenu }}</p>
      </div>
      
      <!-- Video component -->
      <VideosSection v-if="currentLecon?.videos?.length" :videos="currentLecon.videos" />
      
      <!-- Documents component -->
      <DocumentsSection v-if="currentLecon?.documents?.length" :documents="currentLecon.documents" />
      
      <!-- Quiz component -->
      <QuizSection 
        v-if="currentLecon?.quiz" 
        :quiz="currentLecon.quiz" 
        :quizValidationStatus="quizValidationStatus"
        :userAnswers="userAnswers"
        @submit-answers="$emit('submit-answers')"
      />
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps, defineEmits } from 'vue';
  import VideosSection from './VideosSection.vue';
  import DocumentsSection from './DocumentsSection.vue';
  import QuizSection from './QuizSection.vue';
  
  defineProps({
    currentLecon: {
      type: Object,
      required: true
    },
    currentLeconIndex: {
      type: Number,
      required: true
    },
    quizValidationStatus: {
      type: Object,
      required: true
    },
    userAnswers: {
      type: Object,
      required: true
    }
  });
  
  defineEmits(['submit-answers']);
  </script>