<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
            <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Réinitialisation du mot de passe</h2>
            
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" class="text-primary" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 rounded-lg"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Mot de passe" class="text-primary" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 rounded-lg"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirmation de mot de passe"
                        class="text-primary"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 rounded-lg"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="transition transform duration-300 ease-in-out hover:scale-105 hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:outline-none py-3 px-6 rounded-lg"
                    >
                        Réinitialiser le mot de passe
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

/* Background color */
:deep(body), :deep(html) {
    background-color: white;
}

/* Input Fields - Focus Effects */
input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #2760f1; /* Custom border color on focus */
    box-shadow: 0 0 0 3px rgba(39, 96, 241, 0.2); /* Blue box-shadow on focus */
}

/* Button Hover Effects */
.primary-button {
    transition: transform 0.3s ease-in-out, background-color 0.3s ease-in-out;
}

.primary-button:hover {
    background-color: #4338ca; /* Darker blue on hover */
    transform: scale(1.05); /* Slight scale effect on hover */
}

.primary-button:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(39, 96, 241, 0.5); /* Blue focus ring */
}
</style>