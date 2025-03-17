<script setup>
import { defineProps } from "vue";
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  formateur: Object
});
</script>

<template>
  <Head title="Détails du formateur" />
  <AuthenticatedLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Détails du Formateur</h1>

      <div class="bg-white shadow-md rounded-lg p-6">
        <p><strong>ID :</strong> {{ formateur.id }}</p>
        <p><strong>Prénom :</strong> {{ formateur.first_name }}</p>
        <p><strong>Nom :</strong> {{ formateur.last_name }}</p>
        <p><strong>Email :</strong> {{ formateur.email }}</p>
        <p><strong>Téléphone :</strong> {{ formateur.phone }}</p>
        <p><strong>speciality :</strong> {{ formateur.speciality }}</p>
        <p><strong>description :</strong> {{ formateur.description }}</p>
        <p><strong>Créé le :</strong> {{ formateur.created_at }}</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
