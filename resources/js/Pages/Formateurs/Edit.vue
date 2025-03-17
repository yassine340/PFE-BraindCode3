<script setup>
import { defineProps, ref } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  formateur: Object
});

const form = useForm({
  first_name: props.formateur.first_name,
  last_name: props.formateur.last_name,
  phone: props.formateur.phone,
  email: props.formateur.email,
  speciality: props.formateur.speciality,  
  description: props.formateur.description 
});


// Fonction pour soumettre le formulaire
const submit = () => {
  router.put(`/formateurs/${props.formateur.id}`, form, {
    onSuccess: () => {
      alert("Formateur modifié avec succès !");
      router.visit("/formateurs");
    }
  });
};
</script>

<template>
  <Head title="Modifier Formateur" />
  <AuthenticatedLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Modifier Formateur</h1>

      <form @submit.prevent="submit" class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2">Prénom</label>
          <input type="text" v-model="form.first_name" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2">Nom</label>
          <input type="text" v-model="form.last_name" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2">Téléphone</label>
          <input type="text" v-model="form.phone" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2">Email</label>
          <input type="email" v-model="form.email" class="w-full border rounded p-2">
        </div>
        <div>
          <label class="block text-gray-700 font-bold mb-2">Spécialité</label>
          <input type="text" v-model="form.speciality" class="w-full border rounded p-2">
        </div>
        <div>
          <label class="block text-gray-700 font-bold mb-2">Description</label>
          <textarea v-model="form.description" class="w-full border rounded p-2"></textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">
          Enregistrer
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
