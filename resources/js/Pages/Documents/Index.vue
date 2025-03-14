<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const documents = ref([]);

onMounted(async () => {
  try {
    // Fetch all documents from the backend
    let response = await axios.get("/documents");
    documents.value = response.data;
  } catch (error) {
    console.error("Error fetching documents:", error);
  }
});
</script>

<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">All Documents</h1>
    
    <div v-if="documents.length">
      <table class="min-w-full border border-gray-300">
        <thead>
          <tr class="bg-gray-200">
            <th class="border px-4 py-2">#</th>
            <th class="border px-4 py-2">Title</th>
            <th class="border px-4 py-2">Download</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(document, index) in documents" :key="index">
            <td class="border px-4 py-2">{{ index + 1 }}</td>
            <td class="border px-4 py-2">{{ document.titre }}</td>
            <td class="border px-4 py-2">
              <!-- Link to download the document -->
              <a :href="'/storage/' + document.url" target="_blank" class="text-blue-600 underline">Download</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <p v-else class="text-red-500">No documents found.</p>
  </div>
</template>
