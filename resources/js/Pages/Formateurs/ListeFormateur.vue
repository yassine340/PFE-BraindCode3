<script setup>
import { defineProps, ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  formateurs: Array
});

// Liste réactive pour mise à jour après suppression
const formateursList = ref(props.formateurs);

// Fonction pour afficher les détails d'un formateur
const detailsFormateur = (id) => {
  router.visit(`/formateurs/${id}`);
};

// Fonction pour modifier un formateur
const modifierFormateur = (id) => {
  router.visit(`/formateurs/${id}/edit`);
};

// Fonction pour supprimer un formateur
const deleteFormateur = async (id) => {
  if (confirm("Voulez-vous vraiment supprimer ce formateur ?")) {
    try {
      await axios.delete(`/formateurs/${id}`);
      formateursList.value = formateursList.value.filter(f => f.id !== id);
      alert("Formateur supprimé avec succès !");
    } catch (error) {
      console.error("Erreur lors de la suppression :", error);
      alert("Une erreur s'est produite lors de la suppression.");
    }
  }
};
</script>


<template>
  <Head title="Liste des formateurs" />
  <AuthenticatedLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
        Liste des Formateurs
      </h1>

      <div class="overflow-x-auto bg-white shadow-lg rounded-lg p-4">
        <table class="w-full border border-gray-300 rounded-lg">
          <thead class="bg-blue-500 text-white">
            <tr>
              <th class="px-6 py-3 text-left uppercase">ID</th>
              <th class="px-6 py-3 text-left uppercase">Prénom</th>
              <th class="px-6 py-3 text-left uppercase">Nom</th>
              <th class="px-6 py-3 text-left uppercase">Email</th>
              <th class="px-6 py-3 text-center uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="formateur in formateursList" 
              :key="formateur.id" 
              class="hover:bg-gray-100 transition-all"
            >
              <td class="px-6 py-3 border">{{ formateur.id }}</td>
              <td class="px-6 py-3 border">{{ formateur.first_name }}</td>
              <td class="px-6 py-3 border">{{ formateur.last_name }}</td>
              <td class="px-6 py-3 border">{{ formateur.email }}</td>
              <td class="px-6 py-3 border text-center">
                <div class="flex justify-center gap-2">
                  <button class="btn-primary" @click="detailsFormateur(formateur.id)">
                    Détails
                  </button>
                  <button class="btn-success" @click="modifierFormateur(formateur.id)">
                    Modifier
                  </button>
                  <button class="btn-danger" @click="deleteFormateur(formateur.id)">
                    Supprimer
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Styles des boutons */
.btn-primary {
  background-color: #3b82f6;
  color: white;
  font-weight: bold;
  padding: 8px 12px;
  border-radius: 6px;
  transition: background-color 0.2s ease-in-out;
}

.btn-primary:hover {
  background-color: #2563eb;
}

.btn-success {
  background-color: #10b981;
  color: white;
  font-weight: bold;
  padding: 8px 12px;
  border-radius: 6px;
  transition: background-color 0.2s ease-in-out;
}

.btn-success:hover {
  background-color: #059669;
}

.btn-danger {
  background-color: #ef4444;
  color: white;
  font-weight: bold;
  padding: 8px 12px;
  border-radius: 6px;
  transition: background-color 0.2s ease-in-out;
}

.btn-danger:hover {
  background-color: #dc2626;
}
</style>
