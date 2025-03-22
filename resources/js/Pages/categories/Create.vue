<script setup>
import { useForm } from "@inertiajs/vue3";
import { Head, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

// Initialisation du formulaire
const form = useForm({
  name: "",
  description: "",
});

// Fonction de soumission du formulaire
const submit = () => {
  form.post(route("categories.store"), {
    onSuccess: () => {
      form.reset();
      router.visit("/categorie"); // Redirige vers la liste des catégories après ajout
    },
  });
};
</script>

<template>
  <Head title="Ajouter une Catégorie" />
  <AuthenticatedLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
        Ajouter une Catégorie
      </h1>

      <form @submit.prevent="submit" class="bg-white p-6 rounded-lg shadow-lg">
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2">Nom</label>
          <input v-model="form.name" type="text" class="input" required />
        </div>

        <div class="flex justify-between">
          <button type="submit" class="btn-success">Ajouter</button>
          <button type="button" class="btn-secondary" @click="router.visit('/categorie')">Annuler</button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
}
.btn-success {
  background-color: #10b981;
  color: white;
  font-weight: bold;
  padding: 10px 15px;
  border-radius: 6px;
  transition: background-color 0.2s;
}
.btn-success:hover {
  background-color: #059669;
}
.btn-secondary {
  background-color: #6b7280;
  color: white;
  font-weight: bold;
  padding: 10px 15px;
  border-radius: 6px;
  transition: background-color 0.2s;
}
.btn-secondary:hover {
  background-color: #4b5563;
}
</style>
