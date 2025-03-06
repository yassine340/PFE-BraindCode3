<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios'; // Importez axios pour les requêtes HTTP
import { Inertia } from '@inertiajs/inertia';

</script>

<template>
    <Head title="Liste des formateurs en attente" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Liste des formateurs en attente -->
                <div class="p-6 bg-gray-100 min-h-screen">
                    <h1 class="text-2xl font-bold text-gray-700 mb-6">📋 Liste des formateurs en attente</h1>

                    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 border text-left">ID</th>
                                    <th class="px-4 py-2 border text-left">Prénom</th>
                                    <th class="px-4 py-2 border text-left">Nom</th>
                                    <th class="px-4 py-2 border text-left">Email</th>
                                    <th class="px-4 py-2 border text-left">Téléphone</th>
                                    <th class="px-4 py-2 border text-left">Spécialité</th>
                                    <th class="px-4 py-2 border text-left">Description</th>
                                    <th class="px-4 py-2 border text-left">Actions</th> <!-- Nouvelle colonne pour les actions -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="formateur in formateurs" :key="formateur.id" class="hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ formateur.id }}</td>
                                    <td class="px-4 py-2 border">{{ formateur.first_name }}</td>
                                    <td class="px-4 py-2 border">{{ formateur.last_name }}</td>
                                    <td class="px-4 py-2 border">{{ formateur.email }}</td>
                                    <td class="px-4 py-2 border">{{ formateur.phone || 'N/A' }}</td>
                                    <td class="px-4 py-2 border">{{ formateur.speciality || 'Non précisée' }}</td>
                                    <td class="px-4 py-2 border">{{ formateur.description || 'Aucune description' }}</td>
                                    <td class="px-4 py-2 border">
                                        <!-- Bouton pour valider -->
                                        <button 
                                            @click="validerFormateur(formateur.id)" 
                                            class="px-4 py-2 text-white bg-green-500 hover:bg-green-700 rounded-lg">
                                            Valider
                                        </button>
                                        <button 
                                            @click="rejetFormateur(formateur.id)" 
                                            class="px-4 py-2 text-white bg-red-500 hover:bg-red-700 rounded-lg">
                                            Rejeter
                                        </button>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
  props: {
    formateurs: Array, // Liste des formateurs en attente
  },
  methods: {
    async validerFormateur(id) {
  try {
    // Envoyer une requête POST pour valider un formateur
    const response = await axios.post(`/formateurs/${id}/valider`);
    
    // Afficher le message de succès
    alert(response.data.message); // Afficher le message de succès
    
    // Mettre à jour la liste des formateurs si nécessaire
    this.$inertia.reload(); // Recharger la page après la validation
  } catch (error) {
    console.error('Erreur lors de la validation du formateur:', error);
    alert('Erreur lors de la validation du formateur');
  }
}
,
async rejetFormateur(id) {
  try {
    // Envoyer une requête POST pour rejeter un formateur
    const response = await axios.post(`/formateurs/${id}/rejeter`);

    // Vérifier si la réponse contient un message
    if (response.data && response.data.message) {
      alert(response.data.message); // Afficher le message de succès
    } else {
      alert("Erreur lors du rejet du formateur.");
    }

    // Mettre à jour la liste des formateurs ou recharger la page
    this.$inertia.reload(); // Optionnel si vous voulez recharger la page après l'action
  } catch (error) {
    console.error('Erreur lors du rejet du formateur:', error);
    alert('Erreur lors du rejet du formateur');
  }
}



  }
};
</script>
