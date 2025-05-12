<template>
    <div class="bg-white rounded-xl shadow-lg border border-purple-100 overflow-hidden mt-8">
      <!-- Quiz Header -->
      <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-4">
        <h4 class="text-lg font-bold text-white flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Quiz : {{ quiz.titre }}
        </h4>
      </div>
      
      <!-- Quiz validation status -->
      <div class="p-4 border-b border-gray-100" 
          :class="quizValidationStatus[quiz.id] === true 
                  ? 'bg-green-50'
                  : quizValidationStatus[quiz.id] === false
                    ? 'bg-red-50'
                    : 'bg-blue-50'">
        <div class="flex items-center">
          <svg v-if="quizValidationStatus[quiz.id] === true" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg v-else-if="quizValidationStatus[quiz.id] === false" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="font-medium" :class="{
            'text-green-700': quizValidationStatus[quiz.id] === true,
            'text-red-700': quizValidationStatus[quiz.id] === false,
            'text-blue-700': quizValidationStatus[quiz.id] === undefined
          }">
            {{ getQuizStatusMessage(quiz.id) }}
          </span>
        </div>
      </div>
      
      <!-- Quiz Questions -->
      <div class="p-6">
        <div v-if="quiz.questions?.length" class="space-y-8">
          <div v-for="(question, questionIndex) in quiz.questions" :key="question.id"
              class="bg-gray-50 rounded-lg p-5 border border-gray-200">
            <p class="text-gray-800 font-medium mb-4 flex">
              <span class="bg-purple-600 text-white h-6 w-6 rounded-full flex items-center justify-center mr-3 shrink-0">
                {{ questionIndex + 1 }}
              </span>
              {{ question.contenu }}
            </p>
            
            <div class="ml-9 space-y-3">
              <div v-for="reponse in question.reponses" :key="reponse.id"
                  class="flex items-start">
                <div class="flex h-5 items-center">
                  <input type="radio"
                        :id="`reponse-${reponse.id}`"
                        :name="`question-${question.id}`"
                        :value="reponse.id"
                        v-model="userAnswers[question.id]"
                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                </div>
                <label :for="`reponse-${reponse.id}`" class="ml-3 block text-sm font-medium leading-6 text-gray-700">
                  {{ reponse.contenu }}
                </label>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Quiz Submit Button -->
        <div class="mt-6">
          <button v-if="quizValidationStatus[quiz.id] !== true"
                  @click="submitAnswers"
                  class="w-full py-3 px-5 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-lg hover:from-purple-600 hover:to-pink-600 shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Soumettre les réponses
          </button>
          
          <div v-else class="w-full py-3 px-5 bg-green-100 text-green-700 font-semibold rounded-lg shadow-md flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Quiz validé ! Vous pouvez passer à la suite
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps, defineEmits } from 'vue';
  
  // Interface definitions
  interface Reponse {
    id: number;
    contenu: string;
    est_correcte: boolean;
  }
  
  interface Question {
    id: number;
    contenu: string;
    reponses?: Reponse[];
  }
  
  interface Quiz {
    id: number;
    titre: string;
    noteFinale: number;
    dureeMaximale: number;
    questions?: Question[];
  }
  
  const props = defineProps<{
    quiz: Quiz;
    userAnswers: Record<number, number | null>;
    quizValidationStatus: Record<number, boolean>;
  }>();
  
  const emit = defineEmits(['submit-answers']);
  
  // Message de statut pour le quiz
  const getQuizStatusMessage = (quizId: number) => {
    if (props.quizValidationStatus[quizId] === true) {
      return "Quiz validé ! Vous pouvez passer à la suite.";
    } else if (props.quizValidationStatus[quizId] === false) {
      return "Quiz non validé. Essayez à nouveau ou revoyez le contenu du cours.";
    } else {
      return "Complétez ce quiz pour passer à la leçon suivante.";
    }
  };
  
  // Fonction pour soumettre les réponses
  const submitAnswers = () => {
    emit('submit-answers');
  };
  </script>