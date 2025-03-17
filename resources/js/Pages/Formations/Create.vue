<template>
  <Head title="Créer une formation" />
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mt-6 p-8 bg-white rounded-xl shadow-lg">
          <h2 class="text-2xl font-semibold mb-4">Créer une nouvelle formation</h2>
          
          <!-- Form Start -->
          <form @submit.prevent="submitForm">

            <!-- Formation Title -->
            <div class="mb-4">
              <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
              <input type="text" v-model="titre" id="titre" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required />
            </div>

            <!-- Formation Price -->
            <div class="mb-4">
              <label for="prix" class="block text-sm font-medium text-gray-700">Prix</label>
              <input type="number" v-model="prix" id="prix" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required />
            </div>

            <!-- Certification Checkbox -->
            <div class="mb-4 flex items-center">
              <label for="estcertifiante" class="mr-2 text-sm font-medium text-gray-700">Formation certifiante</label>
              <input type="checkbox" v-model="estcertifiante" id="estcertifiante" />
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
              <label for="image_formation" class="block text-sm font-medium text-gray-700">Image de la formation</label>
              <input type="file" @change="handleImageUpload" id="image_formation" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" />
            </div>

            <!-- Modules Section -->
            <div class="mb-4">
              <h3 class="text-xl font-semibold">Modules de la formation</h3>
              
              <!-- Dynamic Module List -->
              <div v-for="(module, index) in modules" :key="index" class="mb-4">
                <div class="mb-4">
                  <label :for="'module_titre_' + index" class="block text-sm font-medium text-gray-700">Titre du Module {{ index + 1 }}</label>
                  <input type="text" v-model="module.titre" :id="'module_titre_' + index" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required />
                </div>
                
                <!-- Module Details -->
                <label :for="'module_description_' + index" class="block text-sm font-medium text-gray-700 mt-2">Description du Module</label>
                <textarea v-model="module.description" :id="'module_description_' + index" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>

                <label :for="'module_ordre_' + index" class="block text-sm font-medium text-gray-700 mt-2">Ordre du Module</label>
                <input type="number" v-model="module.ordre" :id="'module_ordre_' + index" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required />

                <label :for="'module_duree_' + index" class="block text-sm font-medium text-gray-700 mt-2">Durée Estimée (en heures)</label>
                <input type="number" v-model="module.duree_estimee" :id="'module_duree_' + index" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required />

                <!-- Leçon Section for Each Module -->
                <div class="mt-4">
                  <h4 class="text-lg font-semibold">Leçons du Module</h4>
                  <div v-for="(lecon, leconIndex) in module.lecons" :key="leconIndex" class="mb-4">
                    <label :for="'lecon_titre_' + index + '_' + leconIndex" class="block text-sm font-medium text-gray-700">Titre de la Leçon</label>
                    <input type="text" v-model="lecon.titre" :id="'lecon_titre_' + index + '_' + leconIndex" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required />

                    <label :for="'lecon_contenu_' + index + '_' + leconIndex" class="block text-sm font-medium text-gray-700 mt-2">Contenu de la Leçon</label>
                    <textarea v-model="lecon.contenu" :id="'lecon_contenu_' + index + '_' + leconIndex" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>

                    <!-- Video Upload -->
                    <div class="mt-4">
                      <label class="block text-sm font-medium text-gray-700">Ajouter des vidéos</label>
                      <input type="file" multiple @change="handleMultipleFiles($event, index, leconIndex, 'videos')" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" />
                      <ul v-if="lecon.videos.length">
                        <li v-for="(video, vIndex) in lecon.videos" :key="vIndex" class="flex flex-col text-gray-600 mb-2">
                          <input type="text" v-model="video.titre" placeholder="Titre de la vidéo" class="block w-full p-1 border border-gray-300 rounded-md mb-1" />
                          <span>{{ video.file.name }}</span>
                          <button @click="removeFile(index, leconIndex, vIndex, 'videos')" class="text-red-500 ml-2">❌</button>
                        </li>
                      </ul>
                    </div>

                    <!-- Document Upload -->
                    <div class="mt-4">
                      <label class="block text-sm font-medium text-gray-700">Ajouter des documents</label>
                      <input type="file" multiple @change="handleMultipleFiles($event, index, leconIndex, 'documents')" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" />
                      <ul v-if="lecon.documents.length">
                        <li v-for="(doc, dIndex) in lecon.documents" :key="dIndex" class="flex flex-col text-gray-600 mb-2">
                          <input type="text" v-model="doc.titre" placeholder="Titre du document" class="block w-full p-1 border border-gray-300 rounded-md mb-1" />
                          <span>{{ doc.file.name }}</span>
                          <button @click="removeFile(index, leconIndex, dIndex, 'documents')" class="text-red-500 ml-2">❌</button>
                        </li>
                      </ul>
                    </div>

                    <!-- Delete Lesson Button -->
                    <button type="button" @click="removeLecon(index, leconIndex)" class="mt-2 text-red-500">Supprimer la leçon</button>
                  </div>
                </div>

                <!-- Add New Lesson Button -->
                <button type="button" @click="addLecon(index)" class="mb-4 py-2 px-4 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                  + Ajouter une leçon
                </button>

                <!-- Delete Module Button -->
                <button type="button" @click="removeModule(index)" class="mt-2 text-red-500">Supprimer le module</button>
              </div>

              <!-- Add New Module Button -->
              <button type="button" @click="addModule" class="mb-4 py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600">
                + Ajouter un module
              </button>
            </div>

            <!-- Submit Button -->
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Form data references
const titre = ref('');
const prix = ref('');
const estcertifiante = ref(false);
const image_formation = ref(null);
const modules = ref([]);

// Methods for module and lesson management
const addModule = () => {
  modules.value.push({ titre: '', description: '', ordre: '', duree_estimee: '', lecons: [] });
};

const removeModule = (index) => {
  modules.value.splice(index, 1);
};

const addLecon = (moduleIndex) => {
  modules.value[moduleIndex].lecons.push({ titre: '', contenu: '', videos: [], documents: [] });
};

const removeLecon = (moduleIndex, leconIndex) => {
  modules.value[moduleIndex].lecons.splice(leconIndex, 1);
};

// File handling methods
const handleImageUpload = (e) => { image_formation.value = e.target.files[0]; };

const handleMultipleFiles = (e, moduleIndex, leconIndex, fileType) => {
  const files = Array.from(e.target.files);
  files.forEach(file => {
    const newFile = { titre: '', file };
    if (fileType === 'videos') {
      modules.value[moduleIndex].lecons[leconIndex].videos.push(newFile);
    } else if (fileType === 'documents') {
      modules.value[moduleIndex].lecons[leconIndex].documents.push(newFile);
    }
  });
};

const removeFile = (moduleIndex, leconIndex, fileIndex, fileType) => {
  if (fileType === 'videos') {
    modules.value[moduleIndex].lecons[leconIndex].videos.splice(fileIndex, 1);
  } else if (fileType === 'documents') {
    modules.value[moduleIndex].lecons[leconIndex].documents.splice(fileIndex, 1);
  }
};

// Form submission method
const submitForm = () => {
  const formData = new FormData();
  formData.append('titre', titre.value);
  formData.append('prix', prix.value);
  formData.append('estcertifiante', estcertifiante.value ? 1 : 0);
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
    onFinish: () => {
      alert('Formation créée avec succès !');
    },
  });
};
</script>
