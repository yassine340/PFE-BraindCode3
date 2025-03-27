<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const selectedRole = ref('user');

const form = useForm({
    role: 'user',
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    phone: '',
    startup_name: '',
    code_fiscal: '',
    startup_email: '',
    startup_phone: '',
    speciality: '',
    description: ''
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const selectRole = (role) => {
    selectedRole.value = role;
    form.role = role;
};

const roleOptions = [
    { 
        value: 'user', 
        label: 'Utilisateur individuel', 
        description: 'Compte personnel pour lapprentissage et le réseautage',
        icon: `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        `
    },
    { 
        value: 'formateur', 
        label: 'Formateur/Instructeur', 
        description: 'Formation et éducation professionnelles',
        icon: `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.1 50.1 0 0112 2.493a50.1 50.1 0 019.741 4.814m-15.482 0A48.4 48.4 0 0112 13.732c.79 0 1.575-.02 2.354-.06.748.425 1.455.89 2.12 1.395a48.677 48.677 0 00-2.12 1.395c-.779-.04-1.564-.06-2.354-.06-2.356 0-4.605.526-6.62 1.475z" />
            </svg>
        `
    },
    { 
        value: 'startup', 
        label: 'Startup/Entreprise', 
        description: 'Compte professionnel ou entrepreneurial',
        icon: `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-lienjoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387c-1.847-.187-3.708-.281-5.6-.281s-3.753.094-5.6.281c-1.069.16-1.837 1.094-1.837 2.175v4.286c0 .687.384 1.312.75 1.661m16.5 0v5.25c0 .966-.784 1.725-1.75 1.725h-16.5A1.75 1.75 0 013 19.5v-5.25m16.5 0h-16.5" />
            </svg>
        `
    }
];
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="max-w-xl mx-auto bg-white/10 backdrop-blur-lg rounded-xl shadow-2xl p-8">
            <!-- Role Selection -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-center text-white mb-6">Choisissez votre type de compte</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div 
                        v-for="role in roleOptions" 
                        :key="role.value" 
                        @click="selectRole(role.value)"
                        class="cursor-pointer group transition-all duration-300 transform hover:scale-105 text-center"
                    >
                        <div 
                            class="p-4 rounded-xl transition-all duration-300 flex flex-col items-center justify-center space-y-3 border-2"
                            :class="{
                                'bg-blue-500/20 border-blue-500': form.role === role.value,
                                'bg-white/10 border-transparent hover:bg-white/20 hover:border-white/30': form.role !== role.value
                            }"
                        >
                            <div 
                                class="w-16 h-16 flex items-center justify-center rounded-full transition-all duration-300"
                                :class="{
                                    'bg-blue-500/20': form.role === role.value,
                                    'bg-white/10 group-hover:bg-white/20': form.role !== role.value
                                }"
                            >
                                <div 
                                    v-html="role.icon"
                                    class="w-10 h-10 transition-all duration-300"
                                    :class="{
                                        'text-blue-500': form.role === role.value,
                                        'text-white/70 group-hover:text-white': form.role !== role.value
                                    }"
                                ></div>
                            </div>
                            <div>
                                <h3 
                                    class="font-semibold text-sm transition-colors duration-300"
                                    :class="{
                                        'text-blue-500': form.role === role.value,
                                        'text-white/70 group-hover:text-white': form.role !== role.value
                                    }"
                                >
                                    {{ role.label }}
                                </h3>
                                <p 
                                    class="text-xs text-center mt-1 transition-colors duration-300"
                                    :class="{
                                        'text-blue-300': form.role === role.value,
                                        'text-white/50 group-hover:text-white/70': form.role !== role.value
                                    }"
                                >
                                    {{ role.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Common Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="first_name" value="Prénom" />
                        <TextInput 
                            id="first_name" 
                            type="text" 
                            class="mt-1 block w-full" 
                            v-model="form.first_name" 
                            required 
                            placeholder="Entrez votre prénom"
                        />
                        <InputError class="mt-2" :message="form.errors.first_name" />
                    </div>
                    <div>
                        <InputLabel for="last_name" value="Nom de famille" />
                        <TextInput 
                            id="last_name" 
                            type="text" 
                            class="mt-1 block w-full" 
                            v-model="form.last_name" 
                            required 
                            placeholder="Entrez votre nom de famille"
                        />
                        <InputError class="mt-2" :message="form.errors.last_name" />
                    </div>
                </div>

                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput 
                        id="email" 
                        type="email" 
                        class="mt-1 block w-full" 
                        v-model="form.email" 
                        required 
                        placeholder="Enter your email address"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="phone" value="Téléphone" />
                    <TextInput 
                        id="phone" 
                        type="tel" 
                        class="mt-1 block w-full" 
                        v-model="form.phone" 
                        required 
                        placeholder="+1 (123) 456-7890"
                    />
                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <!-- Role Specific Fields -->
                <transition name="fade">
                    <div v-if="form.role === 'startup'" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="startup_name" value="l'entreprise nom" />
                                <TextInput 
                                    id="startup_name" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    v-model="form.startup_name" 
                                    placeholder="Le nom de votre l'entreprise"
                                />
                                <InputError class="mt-2" :message="form.errors.startup_name" />
                            </div>
                            <div>
                                <InputLabel for="code_fiscal" value="Code fiscal" />
                                <TextInput 
                                    id="code_fiscal" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    v-model="form.code_fiscal" 
                                    placeholder="Code fiscal de l'entreprise"
                                />
                                <InputError class="mt-2" :message="form.errors.code_fiscal" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="startup_email" value="l'entreprise Email" />
                                <TextInput 
                                    id="startup_email" 
                                    type="email" 
                                    class="mt-1 block w-full" 
                                    v-model="form.startup_email" 
                                    placeholder="Contact email"
                                />
                                <InputError class="mt-2" :message="form.errors.startup_email" />
                            </div>
                            <div>
                                <InputLabel for="startup_phone" value="l'entreprise Téléphone" />
                                <TextInput 
                                    id="startup_phone" 
                                    type="tel" 
                                    class="mt-1 block w-full" 
                                    v-model="form.startup_phone" 
                                    placeholder="Téléphone de l'entreprise"
                                />
                                <InputError class="mt-2" :message="form.errors.startup_phone" />
                            </div>
                        </div>
                    </div>
                </transition>

                <transition name="fade">
                    <div v-if="form.role === 'formateur'" class="space-y-4">
                        <div>
                            <InputLabel for="speciality" value="Spécialité" />
                            <TextInput 
                                id="speciality" 
                                type="text" 
                                class="mt-1 block w-full" 
                                v-model="form.speciality" 
                                placeholder="Votre domaine d'expertise"
                            />
                            <InputError class="mt-2" :message="form.errors.speciality" />
                        </div>
                        <div>
                            <InputLabel for="description" value="Description" />
                            <textarea 
                                id="description" 
                                class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-300" 
                                rows="4"
                                v-model="form.description" 
                                placeholder="Parlez-nous de vous"
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>
                    </div>
                </transition>

                <!-- Password Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="password" value="mot de passe" />
                        <TextInput 
                            id="password" 
                            type="password" 
                            class="mt-1 block w-full" 
                            v-model="form.password" 
                            required 
                            placeholder="Entrez votre mot de passe"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    <div>
                        <InputLabel for="password_confirmation" value="Confirmez le mot de passe" />
                        <TextInput 
                            id="password_confirmation" 
                            type="password" 
                            class="mt-1 block w-full" 
                            v-model="form.password_confirmation" 
                            required 
                            placeholder="Confirmez votre mot de passe"
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <Link 
                        :href="route('login')" 
                        class="text-sm text-blue-400 hover:text-blue-300 transition-colors duration-300"
                    >
                        Déjà inscrit?
                    </Link>

                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 ease-in-out flex items-center"
                        :disabled="form.processing"
                    >
                        <svg 
                            v-if="form.processing" 
                            class="animate-spin h-5 w-5 mr-3" 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ form.processing ? "Registering..." : "Register" }}
                    </button>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Fade transition for role-specific sections */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Subtle background and blur effect */
.backdrop-blur-lg {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Input and textarea styles */
.input-field {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: white;
}

.input-field:focus {
    border-color: #2760f1;
    box-shadow: 0 0 0 3px rgba(39, 96, 241, 0.2);
}
</style>