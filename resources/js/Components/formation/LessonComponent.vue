<template>
    <div class="mb-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
      <div class="flex justify-between items-center mb-3">
        <div class="flex items-center">
          <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 mr-2 text-sm font-bold">
            {{ lessonIndex + 1 }}
          </span>
          <input 
            type="text" 
            v-model="lesson.titre" 
            :placeholder="`Leçon ${lessonIndex + 1}`"
            class="text-md font-medium bg-transparent border-b-2 border-transparent focus:border-indigo-500 focus:outline-none transition-all" 
            required 
          />
        </div>
        <button 
          type="button" 
          @click="$emit('remove')" 
          class="text-red-500 hover:text-red-700 transition-colors"
          title="Supprimer cette leçon"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
  
      <!-- Tabs pour les différents types de contenu -->
      <div class="mt-4">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-4">
            <a href="#" @click.prevent="lesson.activeTab = 'contenu'" :class="[lesson.activeTab === 'contenu' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm']">
              Contenu
            </a>
            <a href="#" @click.prevent="lesson.activeTab = 'medias'" :class="[lesson.activeTab === 'medias' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm']">
              Vidéos & Docs
            </a>
            <a href="#" @click.prevent="lesson.activeTab = 'quiz'" :class="[lesson.activeTab === 'quiz' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm']">
              Quiz
            </a>
          </nav>
        </div>
  
        <!-- Tab: Contenu -->
        <div v-show="lesson.activeTab === 'contenu'" class="mt-4">
          <label :for="'lecon_contenu_' + moduleIndex + '_' + lessonIndex" class="block text-sm font-medium text-gray-700 mb-1">Contenu de la leçon</label>
          <textarea 
            v-model="lesson.contenu" 
            :id="'lecon_contenu_' + moduleIndex + '_' + lessonIndex" 
            rows="5"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
            placeholder="Contenu détaillé de la leçon..."
          ></textarea>
        </div>
  
        <!-- Tab: Médias -->
        <div v-show="lesson.activeTab === 'medias'" class="mt-4">
          <!-- Vidéos -->
          <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
              </svg>
              Vidéos
            </h4>
            <div class="p-3 border border-gray-200 rounded-lg bg-white">
              <input 
                type="file" 
                multiple 
                @change="handleFiles($event, 'videos')" 
                class="block w-full text-sm text-gray-500 
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-md file:border-0
                  file:text-sm file:font-semibold
                  file:bg-blue-50 file:text-blue-700
                  hover:file:bg-blue-100"
                accept="video/*"
              />
  
              <ul v-if="lesson.videos.length" class="mt-3 space-y-2">
                <li v-for="(video, vIndex) in lesson.videos" :key="vIndex" class="flex items-center p-2 bg-gray-50 rounded">
                  <span class="flex-1 truncate">{{ video.file.name }}</span>
                  <input 
                    type="text" 
                    v-model="video.titre" 
                    placeholder="Titre de la vidéo" 
                    class="mx-2 p-1 border border-gray-300 rounded text-sm" 
                  />
                  <button type="button" @click="removeFile(vIndex, 'videos')" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </li>
              </ul>
            </div>
          </div>
  
          <!-- Documents -->
          <div>
            <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
              </svg>
              Documents
            </h4>
            <div class="p-3 border border-gray-200 rounded-lg bg-white">
              <input 
                type="file" 
                multiple 
                @change="handleFiles($event, 'documents')" 
                class="block w-full text-sm text-gray-500 
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-md file:border-0
                  file:text-sm file:font-semibold
                  file:bg-green-50 file:text-green-700
                  hover:file:bg-green-100"
                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx"
              />
  
              <ul v-if="lesson.documents.length" class="mt-3 space-y-2">
                <li v-for="(doc, dIndex) in lesson.documents" :key="dIndex" class="flex items-center p-2 bg-gray-50 rounded">
                  <span class="flex-1 truncate">{{ doc.file.name }}</span>
                  <input 
                    type="text" 
                    v-model="doc.titre" 
                    placeholder="Titre du document" 
                    class="mx-2 p-1 border border-gray-300 rounded text-sm" 
                  />
                  <button type="button" @click="removeFile(dIndex, 'documents')" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
  
        <!-- Tab: Quiz -->
        <div v-show="lesson.activeTab === 'quiz'" class="mt-4">
          <quiz-component 
            v-if="lesson.quiz" 
            v-model="lesson.quiz" 
            :module-index="moduleIndex" 
            :lesson-index="lessonIndex"
            @remove="lesson.quiz = null"
          />
          <div v-else>
            <button
              type="button"
              @click="addQuiz"
              class="w-full py-3 px-4 border-2 border-dashed border-indigo-300 rounded-lg text-indigo-600 font-medium hover:bg-indigo-50 transition-colors flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Ajouter un quiz
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits, computed, watch } from 'vue';
  import QuizComponent from '@/Components/formation/QuizComponent.vue';
  
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
  
  // Create a reactive copy of the lesson for two-way binding
  const lesson = computed({
    get: () => props.modelValue,
    set: (value) => {
      emit('update:modelValue', value);
    }
  });
  
  // File handling methods
  const handleFiles = (e, fileType) => {
    const files = Array.from(e.target.files);
    const updatedLesson = { ...lesson.value };
    
    files.forEach(file => {
      const newFile = { titre: file.name.split('.')[0], file };
      if (fileType === 'videos') {
        updatedLesson.videos = [...updatedLesson.videos, newFile];
      } else if (fileType === 'documents') {
        updatedLesson.documents = [...updatedLesson.documents, newFile];
      }
    });
    
    emit('update:modelValue', updatedLesson);
  };
  
  const removeFile = (fileIndex, fileType) => {
    const updatedLesson = { ...lesson.value };
    
    if (fileType === 'videos') {
      updatedLesson.videos = updatedLesson.videos.filter((_, index) => index !== fileIndex);
    } else if (fileType === 'documents') {
      updatedLesson.documents = updatedLesson.documents.filter((_, index) => index !== fileIndex);
    }
    
    emit('update:modelValue', updatedLesson);
  };
  
  const addQuiz = () => {
    const updatedLesson = { ...lesson.value };
    updatedLesson.quiz = {
      titre: '',
      noteFinale: 10, // Valeur par défaut
      dureeMaximale: 30, // Valeur par défaut
      questions: [{
        contenu: '',
        reponses: [
          { contenu: '', correct: false },
          { contenu: '', correct: false }
        ]
      }]
    };
    
    emit('update:modelValue', updatedLesson);
  };
  </script>