<template>
  <AuthenticatedLayout>
    <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-black dark:text-gray-200"
            >
            📘 {{ formation?.titre || 'Chargement...' }}
            </h2>
        </template>
    <div class="container mx-auto p-6">
      <!-- Informations de la formation -->
      <div class="bg-gray-100 p-6 rounded-lg shadow-lg mb-6">
        <p><strong>💰 Prix :</strong> {{ formation?.prix }} €</p>
        <p><strong>🎓 Certification :</strong> {{ formation?.estcertifiante ? 'Oui' : 'Non' }}</p>
        <p><strong>🏷️ Catégorie :</strong> {{ formation?.category?.name || 'Non spécifiée' }}</p>
      </div>

      <!-- Image -->
      <div v-if="formation?.image_formation" class="text-center mb-6">
        <img :src="`/storage/${formation.image_formation}`" alt="Image de la formation" 
             class="w-64 h-64 object-cover mx-auto rounded-lg shadow-lg">
      </div>

      <!-- Modules -->
      <h2 class="text-2xl font-semibold text-gray-700 mt-8">📚 Modules</h2>
      <ul v-if="formation?.modules?.length">
        <li v-for="module in formation.modules" :key="module.id" class="mt-4 p-4 bg-gray-100 rounded-lg">
          <h3 class="text-lg font-bold text-blue-600">📖 {{ module.titre }}</h3>
          <p v-if="module.description" class="text-gray-600">{{ module.description }}</p>
          <p><strong>🔢 Ordre :</strong> {{ module.ordre }}</p>
          <p><strong>⏳ Durée estimée :</strong> {{ module.duree_estimee }} heures</p>

          <!-- Leçons -->
          <h4 class="text-md font-semibold text-gray-700 mt-4">📜 Leçons</h4>
          <ul v-if="module.lecons?.length" class="mt-2 pl-4 list-disc">
            <li v-for="lecon in module.lecons" :key="lecon.id">
              <strong class="text-lg">📌 {{ lecon.titre }}</strong> 
              <p class="text-gray-700">{{ lecon.contenu }}</p>

              <!-- Vidéos -->
              <div v-if="lecon.videos?.length" class="mt-4">
                <h4 class="text-md font-semibold text-blue-500">🎥 Vidéos :</h4>
                <ul class="mt-2 pl-4">
                  <li v-for="video in lecon.videos" :key="video.id">
                    <a :href="`/storage/videos/${video.file}`" target="_blank" class="text-blue-600 hover:underline">
                      {{ video.titre || 'Vidéo' }}
                    </a>
                  </li>
                </ul>
              </div>

              <!-- Documents -->
              <div v-if="lecon.documents?.length" class="mt-4">
                <h4 class="text-md font-semibold text-green-500">📄 Documents :</h4>
                <ul class="mt-2 pl-4">
                  <li v-for="document in lecon.documents" :key="document.id">
                    <a :href="`/storage/documents/${document.file}`" target="_blank" class="text-green-600 hover:underline">
                      {{ document.titre || 'Document' }}
                    </a>
                  </li>
                </ul>
              </div>

            </li>
          </ul>
        </li>
      </ul>

      <p v-else class="text-gray-500 mt-4">Aucun module disponible.</p>
    </div>

    <!-- Boutons Modifier et Supprimer -->
    <div v-if="formation?.id" class="text-center mt-6">
      <Link :href="`/formations/${formation.id}/edit`" 
            class="px-6 py-2 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition duration-200 mr-2">
        ✏️ Modifier
      </Link>

      <button @click="deleteFormation"
        class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-200">
        🗑️ Supprimer
      </button>
    </div>
  </AuthenticatedLayout>
</template>


<script setup lang="ts">
import { defineProps } from "vue";
import { router } from "@inertiajs/vue3";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';


// Définition des types
interface Video {
  id: number;
  titre: string;
  file: string;
}

interface Document {
  id: number;
  titre: string;
  file: string;
}

interface Lecon {
  id: number;
  titre: string;
  contenu: string;
  videos?: Video[];
  documents?: Document[];
}

interface Module {
  id: number;
  titre: string;
  description?: string;
  ordre: number;
  duree_estimee: number;
  lecons?: Lecon[];
}

interface Formation {
  id: number;
  titre: string;
  prix: number;
  estcertifiante: boolean;
  image_formation: string | null;
  modules?: Module[];
  category?: Category;
}
interface Category {
  id: number;
  name: string;
}

// Propriétés reçues depuis Laravel (Inertia.js)
const props = defineProps<{ formation?: Formation }>();

// Fonction pour supprimer la formation
const deleteFormation = () => {
  if (confirm("Êtes-vous sûr de vouloir supprimer cette formation ? Cette action est irréversible.")) {
    router.delete(`/formations/${props.formation?.id}`, {
      onSuccess: () => alert("Formation supprimée avec succès !")
    });
  }
};
</script>
