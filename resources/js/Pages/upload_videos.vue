<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const file = ref(null);
const uploadMessage = ref('');
const videoUrl = ref('');

const handleFileUpload = (event) => {
    file.value = event.target.files[0];
};

const uploadVideo = async () => {
    if (!file.value) {
        uploadMessage.value = "Veuillez sélectionner un fichier.";
        return;
    }

    let formData = new FormData();
    formData.append("video", file.value);

    try {
        const response = await axios.post("/upload-video", formData, {
            headers: { "Content-Type": "multipart/form-data" }
        });

        uploadMessage.value = response.data.message;
        videoUrl.value = response.data.url; // URL de la vidéo stockée
    } catch (error) {
        uploadMessage.value = "Erreur lors de l'upload.";
    }
};
</script>

<template>
    <Head title="Upload Vidéo" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Upload Video Section -->
                <div class="mt-6">
                    <h2 class="text-lg font-semibold">Uploader une Vidéo</h2>
                    <input type="file" @change="handleFileUpload" accept="video/*" class="mt-2">
                    <button @click="uploadVideo" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Envoyer</button>
                    
                    <p v-if="uploadMessage" class="mt-2 text-red-500">{{ uploadMessage }}</p>
                    
                    <video v-if="videoUrl" controls width="400" class="mt-4">
                        <source :src="videoUrl" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de cette vidéo.
                    </video>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
