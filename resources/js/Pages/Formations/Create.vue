<template>
  <Head title="Créer une formation" />
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-10">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <!-- En-tête de page -->
        <div class="text-center mb-12">
          <h1 class="text-4xl md:text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
            Créer une nouvelle formation
          </h1>
          <p class="mt-2 text-gray-600">Remplissez les informations ci-dessous pour créer votre formation</p>
        </div>

        <!-- Formulaire principal avec étapes visuelles -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <!-- Barre de progression -->
          <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2"></div>

          <form @submit.prevent="submitForm" class="p-8">
            <!-- Section 1: Informations générales -->
            <div class="mb-10">
              <h2 class="text-2xl font-semibold text-gray-800 flex items-center mb-6">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 mr-3 font-bold">1</span>
                Informations générales
              </h2>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Titre de la formation -->
                <div class="col-span-2">
                  <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre de la formation</label>
                  <input 
                    type="text" 
                    v-model="titre" 
                    id="titre" 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                    placeholder="Ex: Développement Web Fullstack" 
                    required 
                  />
                </div>

                <!-- Prix -->
                <div>
                  <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">Prix (€)</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span class="text-gray-500">€</span>
                    </div>
                    <input 
                      type="number" 
                      v-model="prix" 
                      id="prix" 
                      class="w-full pl-8 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                      placeholder="0.00" 
                      required 
                    />
                  </div>
                </div>

                <!-- Certification -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Type de formation</label>
                  <div class="flex items-center space-x-4 p-3 border border-gray-300 rounded-lg bg-white">
                    <div @click="estcertifiante = true" :class="{'bg-blue-100 text-blue-700 border-blue-300': estcertifiante, 'bg-gray-100 hover:bg-gray-200': !estcertifiante}" class="flex-1 cursor-pointer p-2 rounded transition-all text-center border">
                      <span class="font-medium">Certifiante</span>
                    </div>
                    <div @click="estcertifiante = false" :class="{'bg-blue-100 text-blue-700 border-blue-300': !estcertifiante, 'bg-gray-100 hover:bg-gray-200': estcertifiante}" class="flex-1 cursor-pointer p-2 rounded transition-all text-center border">
                      <span class="font-medium">Non certifiante</span>
                    </div>
                  </div>
                </div>
                <!-- Category Selection -->
                <div class="mb-4">
                  <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                  <select v-model="selectedCategory" id="category_id" class="form-control w-full p-2 border border-gray-300 rounded-md">
                    <option disabled value="">Sélectionnez une catégorie</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
                <!-- publiee -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Formation publiée</label>
                  <div class="flex items-center space-x-4 p-3 border border-gray-300 rounded-lg bg-white">
                    <!-- Option Publiée -->
                    <div
                      @click="isPubliee = true"
                      :class="{
                        'bg-blue-100 text-blue-700 border-blue-300': isPubliee,
                        'bg-gray-100 hover:bg-gray-200': !isPubliee
                      }"
                      class="flex-1 cursor-pointer p-2 rounded transition-all text-center border"
                    >
                      <span class="font-medium">Publiée</span>
                    </div>

                    <!-- Option Non Publiée -->
                    <div
                      @click="isPubliee = false"
                      :class="{
                        'bg-blue-100 text-blue-700 border-blue-300': !isPubliee,
                        'bg-gray-100 hover:bg-gray-200': isPubliee
                      }"
                      class="flex-1 cursor-pointer p-2 rounded transition-all text-center border"
                    >
                      <span class="font-medium">Non publiée</span>
                    </div>
                  </div>
                </div>
                <!--duree-->
                <div>
                  <label for="duree" class="block text-sm font-medium text-gray-700 mb-1">Durée estimée (en heures)</label>
                  <input 
                    type="number" 
                    id="duree" 
                    v-model="duree" 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                    placeholder="Ex: 10" 
                    required 
                  />
                </div>
                <!-- Description de la formation -->
                <div class="col-span-2">
                  <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description de la formation</label>
                  <textarea 
                    id="description" 
                    v-model="description" 
                    rows="4" 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                    placeholder="Décrivez votre formation ici..." 
                    required
                  ></textarea>
                </div>
                <!-- Image de formation -->
                <div class="col-span-2">
                  <label for="image_formation" class="block text-sm font-medium text-gray-700 mb-1">Image de couverture</label>
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-all">
                    <input type="file" @change="handleImageUpload" id="image_formation" class="hidden" accept="image/*" />
                    <div v-if="image_formation" class="mb-3">
                      <img :src="imagePreviewUrl" alt="Aperçu" class="mx-auto h-40 object-cover rounded" />
                    </div>
                    <label for="image_formation" class="cursor-pointer">
                      <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <p class="mt-1 text-sm text-gray-600">
                        Cliquez pour sélectionner une image<br>
                        <span class="text-xs text-gray-500">(JPG, PNG, max 5MB)</span>
                      </p>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Section 2: Modules et Leçons -->
            <div>
              <h2 class="text-2xl font-semibold text-gray-800 flex items-center mb-6">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 mr-3 font-bold">2</span>
                Modules et contenu
              </h2>

              <!-- Utilisation du composant de modules -->
              <module-component v-model="modules" />
            </div>
            
            <!-- Boutons d'action -->
            <div class="mt-10 flex justify-end space-x-4">
              <button type="button" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                Annuler
              </button>
              <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-colors font-medium">
                Créer la formation
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, computed, onBeforeUnmount } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import ModuleComponent from '@/Components/formation/ModuleComponent.vue';

// Form data references
const titre = ref('');
const prix = ref('');
const estcertifiante = ref(false);
const description = ref('');
const image_formation = ref(null);
const selectedCategory = ref('');
const categories = ref([]);
const modules = ref([]);
const isPubliee = ref(false);
const duree = ref(0);

// Fetch categories on component mount
onMounted(async () => {
  try {
    const response = await axios.get('/categories'); // Assurez-vous que cet endpoint est correct
    categories.value = response.data;
  } catch (error) {
    console.error('Erreur lors du chargement des catégories', error);
  }
});

// URL de prévisualisation pour l'image
const imagePreviewUrl = computed(() => {
  if (image_formation.value && typeof window !== 'undefined') {
    return URL.createObjectURL(image_formation.value);
  }
  return '';
});

// Nettoyer les URLs d'objets pour éviter les fuites de mémoire
onBeforeUnmount(() => {
  if (imagePreviewUrl.value) {
    URL.revokeObjectURL(imagePreviewUrl.value);
  }
});

// File handling methods
const handleImageUpload = (e) => { 
  const file = e.target.files[0];
  if (file) {
    image_formation.value = file;
  }
};

// Form submission method
const submitForm = () => {
  if (!titre.value || !prix.value || !selectedCategory.value) {
    alert("Please fill all required fields.");
    return;
  }
  
  const formData = new FormData();
  formData.append('titre', titre.value);
  formData.append('prix', prix.value);
  formData.append('estcertifiante', estcertifiante.value ? 1 : 0);
  formData.append('duree', duree.value);
  formData.append('est_publiee', isPubliee.value ? 1 : 0);
  formData.append('category_id', selectedCategory.value);
  formData.append('description', description.value);
  
  if (image_formation.value) {
    formData.append('image_formation', image_formation.value);
  }

  // Add modules and lessons data to formData
  modules.value.forEach((module, index) => {
    formData.append(`modules[${index}][titre]`, module.titre);
    formData.append(`modules[${index}][description]`, module.description);
    formData.append(`modules[${index}][ordre]`, module.ordre);
    formData.append(`modules[${index}][duree_estimee]`, module.duree_estimee);

    module.lecons.forEach((lecon, leconIndex) => {
      formData.append(`modules[${index}][lecons][${leconIndex}][titre]`, lecon.titre);
      formData.append(`modules[${index}][lecons][${leconIndex}][contenu]`, lecon.contenu);

      // Append quiz data
      if (lecon.quiz) {
        formData.append(`modules[${index}][lecons][${leconIndex}][quiz][titre]`, lecon.quiz.titre);
        formData.append(`modules[${index}][lecons][${leconIndex}][quiz][noteFinale]`, lecon.quiz.noteFinale);
        formData.append(`modules[${index}][lecons][${leconIndex}][quiz][dureeMaximale]`, lecon.quiz.dureeMaximale);

        // Append questions and answers
        lecon.quiz.questions.forEach((question, qIndex) => {
          formData.append(`modules[${index}][lecons][${leconIndex}][quiz][questions][${qIndex}][contenu]`, question.contenu);

          question.reponses.forEach((reponse, rIndex) => {
            formData.append(`modules[${index}][lecons][${leconIndex}][quiz][questions][${qIndex}][reponses][${rIndex}][contenu]`, reponse.contenu);
            formData.append(`modules[${index}][lecons][${leconIndex}][quiz][questions][${qIndex}][reponses][${rIndex}][est_correcte]`, reponse.correct ? 1 : 0);
          });
        });
      }

      // Append videos and documents
      lecon.videos.forEach((video, vidIndex) => {
        formData.append(`modules[${index}][lecons][${leconIndex}][videos][${vidIndex}][titre]`, video.titre);
        formData.append(`modules[${index}][lecons][${leconIndex}][videos][${vidIndex}][file]`, video.file);
      });

      lecon.documents.forEach((doc, docIndex) => {
        formData.append(`modules[${index}][lecons][${leconIndex}][documents][${docIndex}][titre]`, doc.titre);
        formData.append(`modules[${index}][lecons][${leconIndex}][documents][${docIndex}][file]`, doc.file);
      });
    });
  });

  Inertia.post('/formations', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
    onSuccess: () => {
      alert('Formation créée avec succès !');
    },
    onError: (errors) => {
      console.error('Erreurs lors de la soumission:', errors);
      alert('Une erreur est survenue lors de la création de la formation. Veuillez vérifier tous les champs.');
    }
  });
};
</script>