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

        <div class="">
            <transition name="fade">
                <div class="">
                    <!-- Affichage du message flash (s'il existe) -->
                    <transition name="slide">
                        <div v-if="message" class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-600">
                            {{ message }}
                        </div>
                    </transition>

                    <!-- Affichage du statut si présent -->
                    <transition name="slide">
                        <div v-if="status" class="mb-4 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-600">
                            {{ status }}
                        </div>
                    </transition>

                    <!-- Formulaire de connexion -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full rounded-md border p-3 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50 transition-all"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Mot de passe" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full rounded-md border p-3 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50 transition-all"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-white">Se souvenir de moi</span>

                        </div>

                        <div class="flex items-center justify-between">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-indigo-600 hover:underline"
                            >
                                Mot de passe oublié?
                            </Link>

                            <button
                                type="submit"
                                class="ml-4 flex items-center justify-center gap-2 transform bg-indigo-600 px-6 py-2 text-white transition duration-300 hover:bg-indigo-700 hover:scale-105"
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
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

                        <div class="text-center">
                            <Link
                                :href="route('register')"
                                class="text-sm text-white hover:underline"
                            >
                                Créer un compte
                            </Link>
                        </div>
                    </form>
                </div>
            </transition>
        </div>
    </GuestLayout>
</template>

<style>
/* Vue transitions */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease-in-out;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

.slide-enter-active, .slide-leave-active {
    transition: transform 0.5s ease-in-out;
}
.slide-enter, .slide-leave-to {
    transform: translateY(-10px);
    opacity: 0;
}
</style>
