<script setup>
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
defineProps({ formation: Object });
</script>

<template>
        <Head title="Afficher toutes les formations" />
        <AuthenticatedLayout>
    <div class="container">
        <h1>{{ formation.titre }}</h1>

        <img v-if="formation.image_formation" :src="`/storage/${formation.image_formation}`" alt="Image formation" width="200">
        
        <h2>Vidéos</h2>
        <ul v-if="formation.videos.length">
            <li v-for="video in formation.videos" :key="video.id">
                <h3>{{ video.titre }}</h3>
                <video width="400" controls>
                    <source :src="video.url" type="video/mp4">
                    Votre navigateur ne supporte pas la lecture de vidéos.
                </video>
            </li>
        </ul>
        <p v-else>Aucune vidéo disponible.</p>

        <Link href="/formations">Retour aux formations</Link>
    </div>
</AuthenticatedLayout>
</template>
