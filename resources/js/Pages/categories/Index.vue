<script setup>
import { defineProps, ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  categories: Array,
});

// Liste réactive pour mise à jour après suppression
const categoriesList = ref(props.categories);

// Rediriger vers la page d'ajout
const ajouterCategory = () => {
  router.visit("/categories/create");
};

// Fonction pour modifier une catégorie
const modifierCategory = (id) => {
  router.visit(`/categories/${id}/edit`);
};

// Fonction pour supprimer une catégorie
function deleteCategory(id) {
  if (confirm("Voulez-vous vraiment supprimer cette catégorie ?")) {
    axios
      .delete(route("categories.destroy", { category: id })) // Utilisation de Ziggy
      .then((response) => {
        categoriesList.value = categoriesList.value.filter(
          (category) => category.id !== id
        );
      })
      .catch((error) => {
        console.error("Erreur lors de la suppression :", error);
      });
  }
}
</script>

<template>
  <Head title="Liste des Catégories" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-black dark:text-gray-200">
        🌟 Liste des Catégories
      </h2>
    </template>
    <div class="container mx-auto p-6">
      <!-- Bouton Ajouter une Catégorie -->
      <div class="text-center mb-6">
        <button class="btn-add" @click="ajouterCategory">
          + Ajouter une Catégorie
        </button>
      </div>

      <div class="overflow-x-auto bg-white shadow-lg rounded-lg p-4">
        <table class="w-full border border-gray-300 rounded-lg">
          <thead class="bg-blue-500 text-white">
            <tr>
              <th class="px-6 py-3 text-left uppercase">Nom</th>
              <th class="px-6 py-3 text-center uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="category in categoriesList" 
              :key="category.id" 
              class="hover:bg-gray-100 transition-all"
            >
              <td class="px-6 py-3 border">{{ category.name }}</td>
              <td class="px-6 py-3 border text-center">
                <div class="flex justify-center gap-2">
                  <button class="btn-success" @click="modifierCategory(category.id)">
                    Modifier
                  </button>
                  <button class="btn-danger" @click="deleteCategory(category.id)">
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
/* Styles du bouton "Ajouter une Catégorie" */
.btn-add {
  background-color: #22c55e;
  color: white;
  font-weight: bold;
  padding: 10px 15px;
  border-radius: 6px;
  transition: background-color 0.2s ease-in-out;
}

.btn-add:hover {
  background-color: #16a34a;
}

/* Styles des boutons d'action */
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
