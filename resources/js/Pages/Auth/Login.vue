<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Définir les props reçues depuis Inertia (message flash, statut)
defineProps({
    canResetPassword: Boolean,
    status: String,
    message: {
        type: String,
        default: '',
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onStart: () => (form.processing = true),
        onFinish: () => {
            form.reset('password');
            form.processing = false;
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden md:max-w-lg ">
            <div class="md:flex">
                <div class="p-8 w-full">
                    <div class="mb-6 text-center">
                        <h2 class="text-2xl font-bold text-gray-800">Login</h2>
                        <p class="text-gray-600 mt-1">Bon retour ! Connectez-vous à votre compte</p>
                    </div>

                    <!-- Affichage du message flash (s'il existe) -->
                    <transition name="slide">
                        <div v-if="message" class="mb-6 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-600 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ message }}
                        </div>
                    </transition>

                    <!-- Affichage du statut si présent -->
                    <transition name="slide">
                        <div v-if="status" class="mb-6 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-600 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ status }}
                        </div>
                    </transition>

                    <!-- Formulaire de connexion -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <InputLabel for="email" value="Email" class="font-medium" style="color: #005183;" />

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="pl-10 mt-1 block w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50 transition-all"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="votre@email.com"
                                />
                            </div>
                            <InputError class="mt-1 text-red-500" :message="form.errors.email" />
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <InputLabel for="password" value="Mot de passe" class="font-medium" style="color: #005183;" />
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium hover:underline"
                                    style="color: #005183;"
                                >
                                    Mot de passe oublié?
                                </Link>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 116 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="pl-10 mt-1 block w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50 transition-all"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                />
                            </div>
                            <InputError class="mt-1 text-red-500" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                            <span class="ml-2 text-sm" style="color: #005183;">Se souvenir de moi</span>
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                                :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="h-5 w-5 animate-spin text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v4m0 8v4m8-8h-4m-8 0H4m15.656-7.071l-2.828 2.829M6.343 6.343l2.829 2.829M15.657 15.657l2.828 2.829M6.343 17.657l2.829-2.829"
                                    />
                                </svg>
                                {{ form.processing ? "Connexion en cours..." : "Se connecter" }}
                            </button>
                        </div>

                        <div class="text-center mt-6 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600">
                                Vous n'avez pas de compte?
                                <Link
                                    :href="route('register')"
                                    class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline"
                                    style="color: #005183;"
                                >
                                    Créer un compte
                                </Link>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style>
/* Vue transitions */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease-in-out;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.slide-enter-active, .slide-leave-active {
    transition: all 0.3s ease-out;
}
.slide-enter-from, .slide-leave-to {
    transform: translateY(-10px);
    opacity: 0;
}
</style>