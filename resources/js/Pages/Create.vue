<template>
    <Head title="Créer une nouvelle formation" />
    <AuthenticatedLayout>
      <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="mt-6 p-8 bg-white rounded-xl shadow-lg">
            <h2 class="text-2xl font-semibold mb-4">Créer une nouvelle formation</h2>
            <form @submit.prevent="submitForm">
              
              <!-- Champ Titre -->
              <div class="mb-4">
                <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" v-model="titre" id="titre" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required />
              </div>
              
              <!-- Champ Prix -->
              <div class="mb-4">
                <label for="prix" class="block text-sm font-medium text-gray-700">Prix</label>
                <input type="number" v-model="prix" id="prix" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required />
              </div>
      
              <!-- Formation Certifiante -->
              <div class="mb-4 flex items-center">
                <label for="estcertifiante" class="mr-2 text-sm font-medium text-gray-700">Formation certifiante</label>
                <input type="checkbox" v-model="estcertifiante" id="estcertifiante" />
              </div>
      
              <!-- Image de Formation -->
              <div class="mb-4">
                <label for="image_formation" class="block text-sm font-medium text-gray-700">Image de la formation</label>
                <input type="file" @change="handleImageUpload" id="image_formation" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" />
              </div>
  
              <!-- Champ Vidéos -->
              <div v-for="(video, index) in videos" :key="index" class="mb-4 border p-4 rounded-md bg-gray-100">
                <label :for="'video_' + index" class="block text-sm font-medium text-gray-700">Vidéo {{ index + 1 }}</label>
                <input type="file" @change="handleVideoUpload($event, index)" :id="'video_' + index" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" />
                
                <label :for="'video_titre_' + index" class="block text-sm font-medium text-gray-700 mt-2">Titre de la Vidéo</label>
                <input type="text" v-model="video.titre" :id="'video_titre_' + index" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" />
                
                <button type="button" @click="removeVideo(index)" class="mt-2 text-red-500">Supprimer</button>
              </div>
  
              <!-- Bouton Ajouter une vidéo -->
              <button type="button" @click="addVideo" class="mb-4 py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600">
                + Ajouter une vidéo
              </button>
      
              <!-- Bouton Soumettre -->
              <button type="submit" class="mt-6 w-full py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600">Soumettre</button>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
</template>
  
<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Variables pour les champs du formulaire
const titre = ref('');
const prix = ref('');
const estcertifiante = ref(false);
const image_formation = ref(null);
const videos = ref([]); // Tableau contenant les vidéos et leurs titres

// Ajouter un nouveau champ vidéo
const addVideo = () => {
  videos.value.push({ file: null, titre: '' });
};

// Supprimer une vidéo
const removeVideo = (index) => {
  videos.value.splice(index, 1);
};

// Gérer l'upload de l'image
const handleImageUpload = (e) => {
  image_formation.value = e.target.files[0];
};

// Gérer l'upload des vidéos
const handleVideoUpload = (e, index) => {
  videos.value[index].file = e.target.files[0];
};

// Fonction pour soumettre le formulaire
const submitForm = () => {
  const formData = new FormData();
  formData.append('titre', titre.value);
  formData.append('prix', prix.value);
  formData.append('estcertifiante', estcertifiante.value ? 1 : 0);

  if (image_formation.value) {
    formData.append('image_formation', image_formation.value);
  }

  // Ajouter plusieurs vidéos
  videos.value.forEach((video, index) => {
    if (video.file) {
      formData.append(`videos[${index}][file]`, video.file);
    }
    if (video.titre) {
      formData.append(`videos[${index}][titre]`, video.titre);
    }
  });

  // Envoi des données via Inertia
  Inertia.post('/formations', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
    onFinish: () => {
      console.log('Formation créée avec succès !');
    },
  });
};
</script>
