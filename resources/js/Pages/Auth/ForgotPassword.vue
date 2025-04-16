<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
            <div class="mb-4 text-sm text-gray-600">
                Mot de passe oublié ? Aucun problème.
                Indiquez-nous simplement votre adresse e-mail
                et nous vous enverrons un lien de réinitialisation
                de mot de passe qui vous permettra d'en choisir un nouveau.
            </div>

            <div
                v-if="status"
                class="mb-4 text-sm font-medium text-green-600"
            >
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" class="text-primary" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full transition duration-300 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="transition duration-300 ease-in-out transform hover:scale-105 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Lien de réinitialisation du mot de passe par e-mail
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
:deep(.text-primary) {
    color: #005183;
}

/* Set background color to white */
:deep(body), :deep(html) {
    background-color: white;
}

/* Input field focus effect */
input[type="email"] {
    transition: all 0.3s ease-in-out;
    background-color: white;
}

input[type="email"]:focus {
    outline: none;
    border-color: #2760f1;
    box-shadow: 0 0 0 3px rgba(39, 96, 241, 0.2);
}

/* Button hover and focus effects */
.primary-button {
    background-color: #4f46e5;
    color: white;
    transition: all 0.3s ease-in-out;
}

.primary-button:hover {
    background-color: #4338ca;
    transform: scale(1.05);
}

.primary-button:focus {
    outline: none;
    box-shadow: 0 0 0 2px #2760f1;
}
</style>