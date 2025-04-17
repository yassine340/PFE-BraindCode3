<template>
    <div>
      <!-- Liste des modules -->
      <div v-for="(module, index) in modelValue" :key="index" class="mb-8 border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm transition-all hover:shadow-md">
        <!-- En-tête du module -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 border-b border-gray-200">
          <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center">
              <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white mr-3 font-bold">
                {{ index + 1 }}
              </span>
              <input 
                type="text" 
                v-model="module.titre" 
                :placeholder="`Module ${index + 1}`"
                class="text-lg font-semibold bg-transparent border-b-2 border-transparent focus:border-blue-500 focus:outline-none transition-all" 
                required 
              />
            </div>
            <button 
              type="button" 
              @click="removeModule(index)" 
              class="text-red-500 hover:text-red-700 transition-colors"
              title="Supprimer ce module"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
  
        <!-- Contenu du module -->
        <div class="p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Description -->
            <div>
              <label :for="'module_description_' + index" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea 
                v-model="module.description" 
                :id="'module_description_' + index" 
                rows="3"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                placeholder="Description du module..."
              ></textarea>
            </div>
  
            <div class="grid grid-cols-2 gap-4">
              <!-- Ordre -->
              <div>
                <label :for="'module_ordre_' + index" class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                <input 
                  type="number" 
                  v-model="module.ordre" 
                  :id="'module_ordre_' + index" 
                  class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                  placeholder="1"
                  required 
                />
              </div>
  
              <!-- Durée -->
              <div>
                <label :for="'module_duree_' + index" class="block text-sm font-medium text-gray-700 mb-1">Durée (minutes)</label>
                <input 
                  type="number" 
                  v-model="module.duree_estimee" 
                  :id="'module_duree_' + index" 
                  class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                  placeholder="60"
                  required 
                />
              </div>
            </div>
          </div>
  
          <!-- Leçons -->
          <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
              </svg>
              Leçons
            </h3>
  
            <!-- Liste des leçons -->
            <lesson-component 
              v-for="(lecon, leconIndex) in module.lecons" 
              :key="leconIndex"
              v-model="module.lecons[leconIndex]"
              :lesson-index="leconIndex"
              :module-index="index"
              @remove="removeLecon(index, leconIndex)"
            />
  
            <!-- Ajouter une leçon -->
            <button 
              type="button" 
              @click="addLecon(index)" 
              class="w-full py-3 px-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 font-medium hover:bg-gray-50 transition-colors flex items-center justify-center mt-4"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Ajouter une leçon
            </button>
          </div>
        </div>
      </div>
  
      <!-- Ajouter un module -->
      <button 
        type="button" 
        @click="addModule" 
        class="w-full py-4 px-6 border-2 border-dashed border-blue-300 rounded-xl text-blue-600 font-medium hover:bg-blue-50 transition-colors flex items-center justify-center"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Ajouter un module
      </button>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import LessonComponent from '@/Components/formation/LessonComponent.vue';
  
  const props = defineProps({
    modelValue: {
      type: Array,
      required: true
    }
  });
  
  const emit = defineEmits(['update:modelValue']);
  
  // Methods for module and lesson management
  const addModule = () => {
    const updatedModules = [...props.modelValue];
    updatedModules.push({
      titre: '',
      description: '',
      ordre: props.modelValue.length + 1, // Définir l'ordre automatiquement
      duree_estimee: '',
      lecons: [],
    });
    emit('update:modelValue', updatedModules);
  };
  
  const removeModule = (index) => {
    const updatedModules = [...props.modelValue];
    updatedModules.splice(index, 1);
    emit('update:modelValue', updatedModules);
  };
  
  const addLecon = (moduleIndex) => {
    const updatedModules = [...props.modelValue];
    updatedModules[moduleIndex].lecons.push({
      titre: '',
      contenu: '',
      videos: [],
      documents: [],
      quiz: null,
      activeTab: 'contenu', // Tab actif par défaut
    });
    emit('update:modelValue', updatedModules);
  };
  
  const removeLecon = (moduleIndex, leconIndex) => {
    const updatedModules = [...props.modelValue];
    updatedModules[moduleIndex].lecons.splice(leconIndex, 1);
    emit('update:modelValue', updatedModules);
  };
  </script>