<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
      <div class="py-12">
          <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
              <div class="mt-6">
                  <h1>Hello Formateur</h1>
                  <Link :href="route('formations.create')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                      Créer une nouvelle formation
                  </Link>
              </div>
              <div class="mt-6">
                  <h1>Hello Formateur</h1>
                  <Link :href="route('document-upload')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    DocumentUpload
                  </Link>
              </div>
              <div class="mt-6">
                  <h1>Hello Formateur</h1>
                  <Link :href="route('document-list')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    DocumentList
                  </Link>
              </div>
          </div>
      </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>
