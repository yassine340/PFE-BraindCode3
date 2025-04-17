<template>
    <div class="space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-white rounded-lg border border-indigo-100">
        <!-- Quiz Title -->
        <div class="md:col-span-3">
          <label :for="'quiz_titre_' + moduleIndex + '_' + lessonIndex" class="block text-sm font-medium text-gray-700 mb-1">Titre du quiz</label>
          <input 
            type="text" 
            v-model="quiz.titre" 
            :id="'quiz_titre_' + moduleIndex + '_' + lessonIndex" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
            placeholder="Ex: Quiz de validation"
            required
          />
        </div>
        
        <!-- Quiz Score -->
        <div>
          <label :for="'quiz_noteFinale_' + moduleIndex + '_' + lessonIndex" class="block text-sm font-medium text-gray-700 mb-1">Note Finale</label>
          <input 
            type="number" 
            v-model="quiz.noteFinale" 
            :id="'quiz_noteFinale_' + moduleIndex + '_' + lessonIndex" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
            placeholder="20"
            required
          />
        </div>
        
        <!-- Quiz Duration -->
        <div>
          <label :for="'quiz_dureeMaximale_' + moduleIndex + '_' + lessonIndex" class="block text-sm font-medium text-gray-700 mb-1">Durée (minutes)</label>
          <input 
            type="number" 
            v-model="quiz.dureeMaximale" 
            :id="'quiz_dureeMaximale_' + moduleIndex + '_' + lessonIndex" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
            placeholder="30"
            required
          />
        </div>
        
        <!-- Quiz Action Button -->
        <div>
          <label class="invisible block text-sm font-medium text-gray-700 mb-1">Action</label>
          <button 
            type="button" 
            @click="$emit('remove')" 
            class="w-full p-3 text-red-500 border border-red-200 rounded-lg hover:bg-red-50 transition-colors"
          >
            Supprimer le quiz
          </button>
        </div>
      </div>
      
      <!-- Questions -->
      <div>
        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
          </svg>
          Questions
        </h4>
        
        <div class="space-y-4 mt-3">
          <!-- Question List -->
          <div v-for="(question, qIndex) in quiz.questions" :key="qIndex" class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
            <div class="flex items-center justify-between mb-3">
              <span class="font-medium text-indigo-700 flex items-center">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-200 text-indigo-700 mr-2 text-sm font-bold">
                  {{ qIndex + 1 }}
                </span>
                Question
              </span>
              <button type="button" @click="removeQuestion(qIndex)" class="text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <!-- Question Content -->
            <input 
              type="text" 
              v-model="question.contenu" 
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all mb-4" 
              placeholder="Texte de la question"
              required
            />
            
            <!-- Answers -->
            <div class="ml-4 space-y-2">
              <div v-for="(reponse, rIndex) in question.reponses" :key="rIndex" class="flex items-center bg-white p-2 rounded-lg border border-gray-200">
                <input 
                  type="checkbox" 
                  v-model="reponse.correct" 
                  :id="`reponse-${moduleIndex}-${lessonIndex}-${qIndex}-${rIndex}`" 
                  class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500" 
                />
                <input 
                  type="text" 
                  v-model="reponse.contenu" 
                  placeholder="Texte de la réponse" 
                  class="ml-2 flex-1 p-2 border-0 focus:ring-0" 
                  required
                />
                <button type="button" @click="removeReponse(qIndex, rIndex)" class="text-red-500 hover:text-red-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <!-- Add Answer Button -->
              <button 
                type="button" 
                @click="addReponse(qIndex)" 
                class="w-full p-2 border border-dashed border-gray-300 rounded text-gray-600 text-sm hover:bg-gray-50 transition-colors flex items-center justify-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Ajouter une réponse
              </button>
            </div>
          </div>
          
          <!-- Add Question Button -->
          <button 
            type="button" 
            @click="addQuestion" 
            class="w-full py-3 px-4 border-2 border-dashed border-purple-300 rounded-lg text-purple-600 font-medium hover:bg-purple-50 transition-colors flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Ajouter une question
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits, computed } from 'vue';
  
  const props = defineProps({
    modelValue: {
      type: Object,
      required: true
    },
    moduleIndex: {
      type: Number,
      required: true
    },
    lessonIndex: {
      type: Number,
      required: true
    }
  });
  
  const emit = defineEmits(['update:modelValue', 'remove']);
  
  // Create a reactive copy of the quiz for two-way binding
  const quiz = computed({
    get: () => props.modelValue,
    set: (value) => {
      emit('update:modelValue', value);
    }
  });
  
  // Question management methods
  const addQuestion = () => {
    const updatedQuiz = { ...quiz.value };
    updatedQuiz.questions.push({
      contenu: '',
      reponses: [
        { contenu: '', correct: false },
        { contenu: '', correct: false }
      ]
    });
    emit('update:modelValue', updatedQuiz);
  };
  
  const removeQuestion = (questionIndex) => {
    const updatedQuiz = { ...quiz.value };
    updatedQuiz.questions.splice(questionIndex, 1);
    
    // If we removed the last question, add a new empty one to avoid having no questions
    if (updatedQuiz.questions.length === 0) {
      updatedQuiz.questions.push({
        contenu: '',
        reponses: [
          { contenu: '', correct: false },
          { contenu: '', correct: false }
        ]
      });
    }
    
    emit('update:modelValue', updatedQuiz);
  };
  
  // Answer management methods
  const addReponse = (questionIndex) => {
    const updatedQuiz = { ...quiz.value };
    updatedQuiz.questions[questionIndex].reponses.push({ contenu: '', correct: false });
    emit('update:modelValue', updatedQuiz);
  };
  
  const removeReponse = (questionIndex, reponseIndex) => {
    const updatedQuiz = { ...quiz.value };
    const currentReponses = updatedQuiz.questions[questionIndex].reponses;
    
    // Only remove if there are more than 2 answers (keep at least 2 answers per question)
    if (currentReponses.length > 2) {
      updatedQuiz.questions[questionIndex].reponses.splice(reponseIndex, 1);
      emit('update:modelValue', updatedQuiz);
    }
  };
  </script>