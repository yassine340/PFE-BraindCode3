<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
            <h2 class="text-2xl font-semibold mb-4 text-center text-gray-800">Vérification de l'Email</h2>
            
            <p class="mb-4 text-gray-600">
                Merci de votre inscription !
            </p>
            
            <p class="mb-6 text-gray-600">
                Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur
                le lien que nous venons de vous envoyer ?
                Si vous ne l'avez pas reçu, nous vous en enverrons un autre avec plaisir.
            </p>

            <div
                class="mb-4 p-4 bg-green-50 rounded-lg text-sm font-medium text-green-600"
                v-if="verificationLinkSent"
            >
                Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie
                lors de votre inscription.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-6 flex items-center justify-between">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="transition transform duration-300 ease-in-out hover:scale-105 hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:outline-none py-3 px-6 rounded-lg"
                    >
                        Renvoyer l'e-mail de vérification
                    </PrimaryButton>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-blue-600 underline hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >Déconnecter</Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Fix typo in "déconnecter" text */
:deep(.text-primary) {
    color: #005183;
}

/* Background color */
:deep(body), :deep(html) {
    background-color: white;
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