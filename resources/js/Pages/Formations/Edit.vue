<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-black dark:text-gray-200">
        ✏️ Modifier la formation
      </h2>
    </template>
    <div class="container mx-auto p-6">
      <form @submit.prevent="updateFormation" class="bg-gray-100 p-6 rounded-lg shadow-lg">
        <!-- Informations de base -->
        <div class="mb-8 border-b pb-6">
          <h3 class="text-lg font-bold mb-4">Informations de base</h3>
          
          <!-- Titre -->
          <label class="block font-semibold">Titre</label>
          <input v-model="form.titre" type="text" class="w-full p-2 border rounded mb-4">
    
          <!-- Prix -->
          <label class="block font-semibold">Prix (€)</label>
          <input v-model="form.prix" type="number" class="w-full p-2 border rounded mb-4">
    
          <!-- Est Certifiante -->
          <label class="block font-semibold">Certification</label>
          <select v-model="form.estcertifiante" class="w-full p-2 border rounded mb-4">
            <option :value="true">Oui</option>
            <option :value="false">Non</option>
          </select>
          
          <!-- Catégorie -->
          <label class="block font-semibold">Catégorie</label>
          <select v-model="form.category_id" class="w-full p-2 border rounded mb-4">
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          
          <!-- Description -->
          <label class="block font-semibold">Description</label>
          <textarea v-model="form.description" class="w-full p-2 border rounded mb-4" rows="4"></textarea>
          
          <!-- Durée -->
          <label class="block font-semibold">Durée</label>
          <input v-model="form.duree" type="text" class="w-full p-2 border rounded mb-4">
          
          <!-- Est Publiée -->
          <label class="block font-semibold">Statut de publication</label>
          <select v-model="form.est_publiee" class="w-full p-2 border rounded mb-4">
            <option :value="true">Publiée</option>
            <option :value="false">Brouillon</option>
          </select>
          
          <!-- Image -->
          <label class="block font-semibold">Image</label>
          <div class="flex items-center mb-4">
            <img v-if="formation.image_formation" :src="'/storage/' + formation.image_formation" class="h-20 mr-4 rounded">
            <input type="file" @change="handleImageUpload" class="p-2 border rounded">
          </div>
        </div>
        
        <!-- Modules -->
        <div class="mb-8">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Modules</h3>
            <button type="button" @click="addModule" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
              + Ajouter un module
            </button>
          </div>
          
          <div v-for="(module, moduleIndex) in form.modules" :key="moduleIndex" class="mb-6 p-4 border rounded bg-white">
            <div class="flex justify-between items-center mb-4">
              <h4 class="font-bold">Module {{ moduleIndex + 1 }}</h4>
              <button type="button" @click="removeModule(moduleIndex)" class="text-red-600 hover:text-red-800">
                Supprimer
              </button>
            </div>
            
            <!-- Module title -->
            <label class="block font-semibold">Titre</label>
            <input v-model="module.titre" type="text" class="w-full p-2 border rounded mb-4">
            
            <!-- Module description -->
            <label class="block font-semibold">Description</label>
            <textarea v-model="module.description" class="w-full p-2 border rounded mb-4" rows="2"></textarea>
            
            <!-- Module order -->
            <label class="block font-semibold">Ordre</label>
            <input v-model.number="module.ordre" type="number" class="w-full p-2 border rounded mb-4">
            
            <!-- Module estimated duration -->
            <label class="block font-semibold">Durée estimée (minutes)</label>
            <input v-model.number="module.duree_estimee" type="number" class="w-full p-2 border rounded mb-4">
            
            <!-- Leçons -->
            <div class="ml-4 border-l-2 pl-4 mt-6">
              <div class="flex justify-between items-center mb-4">
                <h5 class="font-bold">Leçons</h5>
                <button type="button" @click="addLecon(moduleIndex)" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                  + Ajouter une leçon
                </button>
              </div>
              
              <div v-for="(lecon, leconIndex) in module.lecons" :key="leconIndex" class="mb-6 p-4 border rounded bg-gray-50">
                <div class="flex justify-between items-center mb-4">
                  <h6 class="font-bold">Leçon {{ leconIndex + 1 }}</h6>
                  <button type="button" @click="removeLecon(moduleIndex, leconIndex)" class="text-red-600 hover:text-red-800">
                    Supprimer
                  </button>
                </div>
                
                <!-- Leçon title -->
                <label class="block font-semibold">Titre</label>
                <input v-model="lecon.titre" type="text" class="w-full p-2 border rounded mb-4">
                
                <!-- Leçon content -->
                <label class="block font-semibold">Contenu</label>
                <textarea v-model="lecon.contenu" class="w-full p-2 border rounded mb-4" rows="4"></textarea>
                
                <!-- Vidéos -->
                <div class="mb-4">
                  <div class="flex justify-between items-center mb-2">
                    <h6 class="font-semibold">Videos</h6>
                    <button type="button" @click="addVideo(moduleIndex, leconIndex)" class="px-2 py-1 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
                      + Ajouter une vidéo
                    </button>
                  </div>
                  
                  <div v-for="(video, videoIndex) in lecon.videos" :key="videoIndex" class="mb-2 p-3 border rounded bg-white">
                    <div class="flex justify-between items-center mb-2">
                      <span class="text-sm font-semibold">Vidéo {{ videoIndex + 1 }}</span>
                      <button type="button" @click="removeVideo(moduleIndex, leconIndex, videoIndex)" class="text-red-600 hover:text-red-800 text-sm">
                        Supprimer
                      </button>
                    </div>
                    
                    <label class="block text-sm">Titre</label>
                    <input v-model="video.titre" type="text" class="w-full p-2 border rounded mb-2">
                    
                    <label class="block text-sm">Fichier</label>
                    <input type="file" @change="(e) => handleVideoUpload(e, moduleIndex, leconIndex, videoIndex)" class="w-full p-2 border rounded mb-2">
                    
                    <div v-if="video.url" class="text-sm text-gray-600">
                      Fichier actuel: {{ getFileName(video.url) }}
                    </div>
                  </div>
                </div>
                
                <!-- Documents -->
                <div class="mb-4">
                  <div class="flex justify-between items-center mb-2">
                    <h6 class="font-semibold">Documents</h6>
                    <button type="button" @click="addDocument(moduleIndex, leconIndex)" class="px-2 py-1 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
                      + Ajouter un document
                    </button>
                  </div>
                  
                  <div v-for="(document, documentIndex) in lecon.documents" :key="documentIndex" class="mb-2 p-3 border rounded bg-white">
                    <div class="flex justify-between items-center mb-2">
                      <span class="text-sm font-semibold">Document {{ documentIndex + 1 }}</span>
                      <button type="button" @click="removeDocument(moduleIndex, leconIndex, documentIndex)" class="text-red-600 hover:text-red-800 text-sm">
                        Supprimer
                      </button>
                    </div>
                    
                    <label class="block text-sm">Titre</label>
                    <input v-model="document.titre" type="text" class="w-full p-2 border rounded mb-2">
                    
                    <label class="block text-sm">Fichier</label>
                    <input type="file" @change="(e) => handleDocumentUpload(e, moduleIndex, leconIndex, documentIndex)" class="w-full p-2 border rounded mb-2">
                    
                    <div v-if="document.url" class="text-sm text-gray-600">
                      Fichier actuel: {{ getFileName(document.url) }}
                    </div>
                  </div>
                </div>
                
                <!-- Quiz -->
                <div class="mb-4">
                  <div class="flex justify-between items-center mb-2">
                    <h6 class="font-semibold">Quiz</h6>
                    <button v-if="!lecon.quiz" type="button" @click="addQuiz(moduleIndex, leconIndex)" class="px-2 py-1 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
                      + Ajouter un quiz
                    </button>
                    <button v-else type="button" @click="removeQuiz(moduleIndex, leconIndex)" class="text-red-600 hover:text-red-800 text-sm">
                      Supprimer le quiz
                    </button>
                  </div>
                  
                  <div v-if="lecon.quiz" class="p-3 border rounded bg-white">
                    <label class="block text-sm">Titre</label>
                    <input v-model="lecon.quiz.titre" type="text" class="w-full p-2 border rounded mb-2">
                    
                    <label class="block text-sm">Note finale</label>
                    <input v-model.number="lecon.quiz.noteFinale" type="number" class="w-full p-2 border rounded mb-2">
                    
                    <label class="block text-sm">Durée maximale (minutes)</label>
                    <input v-model.number="lecon.quiz.dureeMaximale" type="number" class="w-full p-2 border rounded mb-4">
                    
                    <!-- Questions -->
                    <div class="ml-2 border-l pl-2">
                      <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold">Questions</span>
                        <button type="button" @click="addQuestion(moduleIndex, leconIndex)" class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                          + Ajouter une question
                        </button>
                      </div>
                      
                      <div v-for="(question, questionIndex) in lecon.quiz.questions" :key="questionIndex" class="mb-3 p-2 border rounded bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                          <span class="text-xs font-semibold">Question {{ questionIndex + 1 }}</span>
                          <button type="button" @click="removeQuestion(moduleIndex, leconIndex, questionIndex)" class="text-red-600 hover:text-red-800 text-xs">
                            Supprimer
                          </button>
                        </div>
                        
                        <label class="block text-xs">Contenu</label>
                        <textarea v-model="question.contenu" class="w-full p-2 border rounded mb-2" rows="2"></textarea>
                        
                        <!-- Réponses -->
                        <div class="ml-2 border-l pl-2">
                          <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-semibold">Réponses</span>
                            <button type="button" @click="addReponse(moduleIndex, leconIndex, questionIndex)" class="px-1 py-0.5 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                              + Ajouter une réponse
                            </button>
                          </div>
                          
                          <div v-for="(reponse, reponseIndex) in question.reponses" :key="reponseIndex" class="mb-2 p-2 border rounded bg-white">
                            <div class="flex justify-between items-center mb-1">
                              <span class="text-xs">Réponse {{ reponseIndex + 1 }}</span>
                              <button type="button" @click="removeReponse(moduleIndex, leconIndex, questionIndex, reponseIndex)" class="text-red-600 hover:text-red-800 text-xs">
                                Supprimer
                              </button>
                            </div>
                            
                            <label class="block text-xs">Contenu</label>
                            <input v-model="reponse.contenu" type="text" class="w-full p-1 border rounded mb-1 text-sm">
                            
                            <div class="flex items-center">
                              <input v-model="reponse.est_correcte" type="checkbox" class="mr-2">
                              <label class="text-xs">Réponse correcte</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Boutons -->
        <div class="flex justify-between mt-8">
          <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
            💾 Sauvegarder
          </button>
          <Link :href="`/formations/${formation.id}`"
            class="px-6 py-2 bg-gray-400 text-white font-semibold rounded-lg hover:bg-gray-500 transition duration-200">
            Annuler
          </Link>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from "axios";

interface Category {
  id: number;
  name: string;
}

interface Reponse {
  id?: number;
  contenu: string;
  est_correcte: boolean;
  file?: File | null;
}

interface Question {
  id?: number;
  contenu: string;
  reponses: Reponse[];
}

interface Quiz {
  id?: number;
  titre: string;
  noteFinale: number;
  dureeMaximale: number;
  questions: Question[];
}

interface Document {
  id?: number;
  titre: string;
  url?: string;
  file?: File | null;
}

interface Video {
  id?: number;
  titre: string;
  url?: string;
  file?: File | null;
}

interface Lecon {
  id?: number;
  titre: string;
  contenu: string;
  videos: Video[];
  documents: Document[];
  quiz: Quiz | null;
}

interface Module {
  id?: number;
  titre: string;
  description: string | null;
  ordre: number;
  duree_estimee: number;
  lecons: Lecon[];
}

interface Formation {
  id: number;
  titre: string;
  prix: number;
  estcertifiante: boolean;
  image_formation: string | null;
  category_id: number;
  description: string | null;
  duree: string | null;
  est_publiee: boolean | null;
  modules?: Module[];
}

const categories = ref<Category[]>([]);

// Props depuis Laravel
const props = defineProps<{ formation: Formation }>();
const formation = props.formation;

// Charger les catégories au montage du composant
onMounted(async () => {
  try {
    const response = await axios.get('/categories');
    categories.value = response.data;
    
    // Si c'est une nouvelle formation ou si les modules sont manquants, charger la structure initiale
    if (!formation.modules || formation.modules.length === 0) {
      loadFormationData();
    }
  } catch (error) {
    console.error("Erreur lors du chargement des catégories", error);
  }
});

// Fonction pour charger les données de la formation
const loadFormationData = async () => {
  try {
    const response = await axios.get(`/formations/${formation.id}/edit/data`);
    const formationData = response.data;
    
    // Initialiser les modules depuis l'API
    if (formationData.modules) {
      form.modules = formationData.modules.map((module: Module) => {
        return {
          ...module,
          lecons: module.lecons ? module.lecons.map((lecon: Lecon) => {
            return {
              ...lecon,
              videos: lecon.videos || [],
              documents: lecon.documents || [],
              quiz: lecon.quiz ? {
                ...lecon.quiz,
                questions: lecon.quiz.questions ? lecon.quiz.questions.map((question: Question) => {
                  return {
                    ...question,
                    reponses: question.reponses || []
                  };
                }) : []
              } : null
            };
          }) : []
        };
      });
    }
  } catch (error) {
    console.error("Erreur lors du chargement des données de la formation", error);
  }
};

// Création du formulaire avec Inertia.js
const form = useForm({
  titre: formation.titre,
  prix: formation.prix,
  estcertifiante: formation.estcertifiante,
  category_id: formation.category_id,
  description: formation.description || '',
  duree: formation.duree || '',
  est_publiee: formation.est_publiee ?? false,
  image_formation: null as File | null,
  modules: []
});

// Initialiser les modules s'ils existent
if (formation.modules && formation.modules.length > 0) {
  form.modules = formation.modules.map(module => ({
    id: module.id,
    titre: module.titre,
    description: module.description || '',
    ordre: module.ordre,
    duree_estimee: module.duree_estimee,
    lecons: (module.lecons || []).map(lecon => ({
      id: lecon.id,
      titre: lecon.titre,
      contenu: lecon.contenu,
      videos: (lecon.videos || []).map(video => ({
        id: video.id,
        titre: video.titre,
        url: video.url,
        file: null as File | null
      })),
      documents: (lecon.documents || []).map(document => ({
        id: document.id,
        titre: document.titre,
        url: document.url,
        file: null as File | null
      })),
      quiz: lecon.quiz ? {
        id: lecon.quiz.id,
        titre: lecon.quiz.titre,
        noteFinale: lecon.quiz.noteFinale,
        dureeMaximale: lecon.quiz.dureeMaximale,
        questions: (lecon.quiz.questions || []).map(question => ({
          id: question.id,
          contenu: question.contenu,
          reponses: (question.reponses || []).map(reponse => ({
            id: reponse.id,
            contenu: reponse.contenu,
            est_correcte: reponse.est_correcte
          }))
        }))
      } : null
    }))
  }));
}

// Gérer le téléchargement de l'image principale
const handleImageUpload = (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (file) form.image_formation = file;
};

// Gérer le téléchargement d'une vidéo
const handleVideoUpload = (event: Event, moduleIndex: number, leconIndex: number, videoIndex: number) => {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (file) form.modules[moduleIndex].lecons[leconIndex].videos[videoIndex].file = file;
};

// Gérer le téléchargement d'un document
const handleDocumentUpload = (event: Event, moduleIndex: number, leconIndex: number, documentIndex: number) => {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (file) form.modules[moduleIndex].lecons[leconIndex].documents[documentIndex].file = file;
};

// Extraire le nom du fichier à partir de l'URL
const getFileName = (url: string): string => {
  const parts = url.split('/');
  return parts[parts.length - 1];
};

// Ajouter un module
const addModule = () => {
  form.modules.push({
    id: undefined, // Set to undefined for new items
    titre: '',
    description: '',
    ordre: form.modules.length + 1,
    duree_estimee: 60,
    lecons: []
  });
};

// Supprimer un module
const removeModule = (moduleIndex: number) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce module?')) {
    form.modules.splice(moduleIndex, 1);
  }
};

// Ajouter une leçon
const addLecon = (moduleIndex: number) => {
  form.modules[moduleIndex].lecons.push({
    id: undefined, // Set to undefined for new items
    titre: '',
    contenu: '',
    videos: [],
    documents: [],
    quiz: null
  });
};

// Supprimer une leçon
const removeLecon = (moduleIndex: number, leconIndex: number) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette leçon?')) {
    form.modules[moduleIndex].lecons.splice(leconIndex, 1);
  }
};

// Ajouter une vidéo
const addVideo = (moduleIndex: number, leconIndex: number) => {
  form.modules[moduleIndex].lecons[leconIndex].videos.push({
    id: undefined, // Set to undefined for new items
    titre: '',
    file: null,
    url: ''
  });
};

// Supprimer une vidéo
const removeVideo = (moduleIndex: number, leconIndex: number, videoIndex: number) => {
  form.modules[moduleIndex].lecons[leconIndex].videos.splice(videoIndex, 1);
};

// Ajouter un document
const addDocument = (moduleIndex: number, leconIndex: number) => {
  form.modules[moduleIndex].lecons[leconIndex].documents.push({
    id: undefined, // Set to undefined for new items
    titre: '',
    file: null,
    url: ''
  });
};

// Supprimer un document
const removeDocument = (moduleIndex: number, leconIndex: number, documentIndex: number) => {
  form.modules[moduleIndex].lecons[leconIndex].documents.splice(documentIndex, 1);
};

// Ajouter un quiz
const addQuiz = (moduleIndex: number, leconIndex: number) => {
  form.modules[moduleIndex].lecons[leconIndex].quiz = {
    id: undefined, // Set to undefined for new items
    titre: 'Quiz',
    noteFinale: 100,
    dureeMaximale: 30,
    questions: []
  };
};

// Supprimer un quiz
const removeQuiz = (moduleIndex: number, leconIndex: number) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce quiz?')) {
    form.modules[moduleIndex].lecons[leconIndex].quiz = null;
  }
};

// Ajouter une question
const addQuestion = (moduleIndex: number, leconIndex: number) => {
  if (form.modules[moduleIndex].lecons[leconIndex].quiz) {
    form.modules[moduleIndex].lecons[leconIndex].quiz!.questions.push({
      id: undefined, // Set to undefined for new items
      contenu: '',
      reponses: []
    });
  }
};

// Supprimer une question
const removeQuestion = (moduleIndex: number, leconIndex: number, questionIndex: number) => {
  if (form.modules[moduleIndex].lecons[leconIndex].quiz) {
    form.modules[moduleIndex].lecons[leconIndex].quiz!.questions.splice(questionIndex, 1);
  }
};

// Ajouter une réponse
const addReponse = (moduleIndex: number, leconIndex: number, questionIndex: number) => {
  if (form.modules[moduleIndex].lecons[leconIndex].quiz) {
    form.modules[moduleIndex].lecons[leconIndex].quiz!.questions[questionIndex].reponses.push({
      id: undefined, // Set to undefined for new items
      contenu: '',
      est_correcte: false
    });
  }
};

// Supprimer une réponse
const removeReponse = (moduleIndex: number, leconIndex: number, questionIndex: number, reponseIndex: number) => {
  if (form.modules[moduleIndex].lecons[leconIndex].quiz) {
    form.modules[moduleIndex].lecons[leconIndex].quiz!.questions[questionIndex].reponses.splice(reponseIndex, 1);
  }
};

// Fonction pour mettre à jour la formation
const updateFormation = () => {
  // Convertir le formulaire en FormData pour gérer les fichiers
  const formData = new FormData();
  
  // Ajouter les champs de base
  formData.append('_method', 'PUT');
  formData.append('titre', form.titre);
  formData.append('prix', form.prix.toString());
  formData.append('estcertifiante', form.estcertifiante ? '1' : '0');
  formData.append('category_id', form.category_id.toString());
  formData.append('description', form.description || '');
  formData.append('duree', form.duree || '');
  formData.append('est_publiee', form.est_publiee ? '1' : '0');
  
  if (form.image_formation) {
    formData.append('image_formation', form.image_formation);
  }
  
  // Ajouter les modules avec leur structure complète
  form.modules.forEach((module, moduleIndex) => {
    // Ajouter les champs du module
    if (module.id) formData.append(`modules[${moduleIndex}][id]`, module.id.toString());
    formData.append(`modules[${moduleIndex}][titre]`, module.titre);
    formData.append(`modules[${moduleIndex}][description]`, module.description || '');
    formData.append(`modules[${moduleIndex}][ordre]`, module.ordre.toString());
    formData.append(`modules[${moduleIndex}][duree_estimee]`, module.duree_estimee.toString());
    
    // Ajouter les leçons
    module.lecons.forEach((lecon, leconIndex) => {
      if (lecon.id) formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][id]`, lecon.id.toString());
      formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][titre]`, lecon.titre);
      formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][contenu]`, lecon.contenu);
      
      // Ajouter les vidéos
      lecon.videos.forEach((video, videoIndex) => {
        if (video.id) formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][videos][${videoIndex}][id]`, video.id.toString());
        formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][videos][${videoIndex}][titre]`, video.titre);
        if (video.file) {
          formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][videos][${videoIndex}][file]`, video.file);
        }
      });
      
      // Ajouter les documents
      lecon.documents.forEach((document, documentIndex) => {
        if (document.id) formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][documents][${documentIndex}][id]`, document.id.toString());
        formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][documents][${documentIndex}][titre]`, document.titre);
        if (document.file) {
          formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][documents][${documentIndex}][file]`, document.file);
        }
      });
      // Ajouter le quiz
      if (lecon.quiz) {
        if (lecon.quiz.id) formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][id]`, lecon.quiz.id.toString());
        formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][titre]`, lecon.quiz.titre);
        formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][noteFinale]`, lecon.quiz.noteFinale.toString());
        formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][dureeMaximale]`, lecon.quiz.dureeMaximale.toString());
        
        // Ajouter les questions
        lecon.quiz.questions.forEach((question, questionIndex) => {
          if (question.id) formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][questions][${questionIndex}][id]`, question.id.toString());
          formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][questions][${questionIndex}][contenu]`, question.contenu);
          
          // Ajouter les réponses
          question.reponses.forEach((reponse, reponseIndex) => {
            if (reponse.id) formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][questions][${questionIndex}][reponses][${reponseIndex}][id]`, reponse.id.toString());
            formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][questions][${questionIndex}][reponses][${reponseIndex}][contenu]`, reponse.contenu);
            formData.append(`modules[${moduleIndex}][lecons][${leconIndex}][quiz][questions][${questionIndex}][reponses][${reponseIndex}][est_correcte]`, reponse.est_correcte ? '1' : '0');
          });
        });
      }
    });
  });
   // Envoyer les données au serveur via l'API
   axios.post(`/formations/${props.formation.id}`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(response => {
    // Redirection vers la page de détail de la formation mise à jour
    router.visit(`/formations/${props.formation.id}`, { 
      only: ['formation'],
      preserveState: true,
      onSuccess: () => {
        // Afficher un message de succès
        alert('Formation mise à jour avec succès !');
      }
    });
  })
  .catch(error => {
    console.error("Erreur lors de la mise à jour de la formation", error);
    // Afficher les erreurs de validation si présentes
    if (error.response && error.response.data && error.response.data.errors) {
      const errorMessages = Object.values(error.response.data.errors).flat().join('\n');
      alert(`Erreurs de validation:\n${errorMessages}`);
    } else {
      alert('Une erreur est survenue lors de la mise à jour de la formation.');
    }
  });
};
</script>
<style scoped>
/* Ajoutez ici vos styles personnalisés */
</style>