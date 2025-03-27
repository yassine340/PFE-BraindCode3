<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  category: Object,
});

// Formulaire avec les données de la catégorie
const form = useForm({
  name: props.category.name,
});

// Fonction de mise à jour
const updateCategory = () => {
    form.put(route("categories.update", { category: props.category.id }), {
    onSuccess: () => {
        alert("Catégorie modifiée avec succès !");
        router.visit("/categorie");
    },
  });
};
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-black dark:text-gray-200">
        ✏️ Modifier la Catégorie {{ category.name }}
      </h2>
    </template>
    <div class="container mx-auto p-6">

      <form @submit.prevent="updateCategory" class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">
            Nom de la catégorie :
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2"
            required
          />
        </div>

        <div class="flex justify-end gap-2">
          <button
            type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
          >
            Enregistrer
          </button>
          <button
            type="button"
            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600"
            @click="router.visit('/categorie')"
          >
            Annuler
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
