<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Elearning from '@/Pages/Elearning.vue';
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- E-learning Page -->
                <div class="mt-6">
                    <h1>Hello administrateur</h1>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
