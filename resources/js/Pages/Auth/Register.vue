<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const selectedRole = ref('user'); // Rôle par défaut

const form = useForm({
    role: 'user', // Rôle par défaut
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    phone: '',
    // Champs spécifiques à User
    startup_name: '',
    code_fiscal: '',
    startup_email: '',
    startup_phone: '',
    // Champs spécifiques à Formateur
    speciality: '',
    description: ''
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

// Fonction pour changer le rôle
const selectRole = (role) => {
    selectedRole.value = role;
    form.role = role;
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <!-- Boutons radio pour la sélection du rôle -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center mx-4">
                <input 
                    type="radio" 
                    id="user" 
                    name="role" 
                    value="user" 
                    v-model="form.role" 
                    class="mr-2"
                />
                <label for="user" class="text-sm">User</label>
            </div>
            <div class="flex items-center mx-4">
                <input 
                    type="radio" 
                    id="formateur" 
                    name="role" 
                    value="formateur" 
                    v-model="form.role" 
                    class="mr-2"
                />
                <label for="formateur" class="text-sm">Formateur</label>
            </div>
            <div class="flex items-center mx-4">
                <input 
                    type="radio" 
                    id="startup" 
                    name="role" 
                    value="startup" 
                    v-model="form.role" 
                    class="mr-2"
                />
                <label for="startup" class="text-sm">Startup</label>
            </div>
        </div>

        <form @submit.prevent="submit">
            <!-- Champs communs -->
            <div>
                <InputLabel for="first_name" value="First Name" />
                <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required />
                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="last_name" value="Last Name" />
                <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required />
                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone" value="Phone" />
                <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <!-- Champs spécifiques à Startup -->
            <div v-if="form.role === 'startup'">
                <div class="mt-4">
                    <InputLabel for="startup_name" value="Startup Name" />
                    <TextInput id="startup_name" type="text" class="mt-1 block w-full" v-model="form.startup_name" />
                    <InputError class="mt-2" :message="form.errors.startup_name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="code_fiscal" value="Code Fiscal" />
                    <TextInput id="code_fiscal" type="text" class="mt-1 block w-full" v-model="form.code_fiscal" />
                    <InputError class="mt-2" :message="form.errors.code_fiscal" />
                </div>

                <div class="mt-4">
                    <InputLabel for="startup_email" value="Startup Email" />
                    <TextInput id="startup_email" type="email" class="mt-1 block w-full" v-model="form.startup_email" />
                    <InputError class="mt-2" :message="form.errors.startup_email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="startup_phone" value="Startup Phone" />
                    <TextInput id="startup_phone" type="text" class="mt-1 block w-full" v-model="form.startup_phone" />
                    <InputError class="mt-2" :message="form.errors.startup_phone" />
                </div>
            </div>

            <!-- Champs spécifiques à Formateur -->
            <div v-if="form.role === 'formateur'">
                <div class="mt-4">
                    <InputLabel for="speciality" value="Speciality" />
                    <TextInput id="speciality" type="text" class="mt-1 block w-full" v-model="form.speciality" />
                    <InputError class="mt-2" :message="form.errors.speciality" />
                </div>

                <div class="mt-4">
                    <InputLabel for="description" value="Description" />
                    <TextInput id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
            </div>

            <!-- Champs mot de passe -->
            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <!-- Bouton d'inscription -->
            <div class="mt-4 flex items-center justify-end">
                <Link :href="route('login')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900">
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
