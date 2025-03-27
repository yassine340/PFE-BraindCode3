<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const selectedRole = ref('user'); // Default role

const form = useForm({
    role: 'user', // Default role
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    phone: '',
    // Fields specific to User
    startup_name: '',
    code_fiscal: '',
    startup_email: '',
    startup_phone: '',
    // Fields specific to Formateur
    speciality: '',
    description: ''
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

// Function to change the role
const selectRole = (role) => {
    selectedRole.value = role;
    form.role = role;
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <!-- Radio buttons to select the role -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center mx-4">
                <input 
                    type="radio" 
                    id="user" 
                    name="role" 
                    value="user" 
                    v-model="form.role" 
                    class="mr-2"
                    @click="selectRole('user')"
                />
                <label for="user" class="text-white">User</label>
            </div>
            <div class="flex items-center mx-4">
                <input 
                    type="radio" 
                    id="formateur" 
                    name="role" 
                    value="formateur" 
                    v-model="form.role" 
                    class="mr-2"
                    @click="selectRole('formateur')"
                />
                <label for="formateur" class="text-white">Formateur</label>
            </div>
            <div class="flex items-center mx-4">
                <input 
                    type="radio" 
                    id="startup" 
                    name="role" 
                    value="startup" 
                    v-model="form.role" 
                    class="mr-2"
                    @click="selectRole('startup')"
                />
                <label for="startup" class="text-white">Startup</label>
            </div>
        </div>

        <form @submit.prevent="submit">
            <!-- Common fields -->
            <div>
                <InputLabel for="first_name" value="Nom" />
                <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required />
                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="last_name" value="Prenom" />
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

            <!-- Fields specific to Startup -->
            <div v-if="form.role === 'startup'" class="fade-enter-active fade-leave-active">
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

            <!-- Fields specific to Formateur -->
            <div v-if="form.role === 'formateur'" class="slide-enter-active slide-leave-active">
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

            <!-- Password fields -->
            <div class="mt-4">
                <InputLabel for="password" value="Mot de passe" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4 flex items-center justify-between">
                <Link :href="route('login')" class="text-sm text-white hover:underline">
                    Déjà inscrit ?
                </Link>

                <button
                    type="submit"
                    class="primary-button"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <svg v-if="form.processing" class="h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v4m0 8v4m8-8h-4m-8 0H4m15.656-7.071l-2.828 2.829M6.343 6.343l2.829 2.829M15.657 15.657l2.828 2.829M6.343 17.657l2.829-2.829" />
                    </svg>
                    {{ form.processing ? "Inscription en cours..." : "S'inscrire" }}
                </button>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
/* Animations for transitions between different roles */
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

/* Style for input fields */
.input-field {
    margin-top: 0.25rem;
    display: block;
    width: 100%;
    border-radius: 0.375rem;
    border-width: 1px;
    padding: 0.75rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
.input-field:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

/* Style for the submit button */
.primary-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transform: translateZ(0);
    background-color: #4f46e5;
    padding: 0.5rem 1.5rem;
    color: white;
    transition: all 300ms;
}
.primary-button:hover {
    background-color: #4338ca;
    transform: scale(1.05);
}

/* Style for the radio button itself */
input[type="radio"] {
    accent-color: #2760f1;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    transition: all 0.2s ease-in-out;
}

/* Hover effect for radio buttons */
input[type="radio"]:hover {
    transform: scale(1.1);
}

/* Style for the labels */
label {
    font-size: 1rem;
    font-weight: 500;
    color: white; /* Change label color to white */
    cursor: pointer;
    transition: color 0.3s ease-in-out;
}

/* Change label color on hover */
label:hover {
    color: #2760f1;
}

/* Active state for the selected radio button */
input[type="radio"]:checked + label {
    color: #2760f1;
}
</style>
