<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const selectedRole = ref('user');
const formProgress = ref(0);

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
    description: '',
    cv_file: null
});

// Calculate form progress
watch(form, (newValue) => {
    let fieldsToCheck = ['first_name', 'last_name', 'email', 'phone', 'password', 'password_confirmation'];
    
    if (form.role === 'startup') {
        fieldsToCheck = [...fieldsToCheck, 'startup_name', 'code_fiscal', 'startup_email', 'startup_phone'];
    }
    
    if (form.role === 'formateur') {
        fieldsToCheck = [...fieldsToCheck, 'speciality', 'description'];
    }
    
    const filledFields = fieldsToCheck.filter(field => newValue[field] && newValue[field].toString().trim() !== '');
    formProgress.value = Math.round((filledFields.length / fieldsToCheck.length) * 100);
}, { deep: true });

const submit = () => {
    form.post(route('register'), {
        forceFormData: true,
        onFinish: () => form.reset('password', 'password_confirmation', 'cv_file'),
    });
};

const selectRole = (role) => {
    selectedRole.value = role;
    form.role = role;
};

const handleCvFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.cv_file = file;
    }
};

const roleOptions = [
    { 
        value: 'user', 
        label: 'Utilisateur individuel', 
        description: 'Compte personnel pour l\'apprentissage et le réseautage',
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387c-1.847-.187-3.708-.281-5.6-.281s-3.753.094-5.6.281c-1.069.16-1.837 1.094-1.837 2.175v4.286c0 .687.384 1.312.75 1.661m16.5 0v5.25c0 .966-.784 1.725-1.75 1.725h-16.5A1.75 1.75 0 013 19.5v-5.25m16.5 0h-16.5" />
            </svg>
        `
    }
];
</script>

<template>
    <GuestLayout>
        <Head title="Créer un compte" />

        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">Créez votre compte</h1>
            <p class="text-center text-gray-600 mb-8">Rejoignez notre communauté pour accéder à tous les services</p>
            
            <!-- Progress indicator -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Progression</span>
                    <span class="text-sm font-medium text-blue-600">{{ formProgress }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div 
                        class="bg-gradient-to-r from-blue-500 to-blue-400 h-2 rounded-full transition-all duration-500 ease-out"
                        :style="`width: ${formProgress}%`"
                    ></div>
                </div>
            </div>

            <!-- Role Selection -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Choisissez votre type de compte</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div 
                        v-for="role in roleOptions" 
                        :key="role.value" 
                        @click="selectRole(role.value)"
                        class="cursor-pointer group transition-all duration-300 transform hover:scale-102"
                    >
                        <div 
                            class="p-6 rounded-xl transition-all duration-300 flex flex-col items-center justify-center space-y-4 border-2 h-full"
                            :class="{
                                'bg-blue-50 border-blue-500 shadow-md': form.role === role.value,
                                'bg-gray-50 border-gray-200 hover:bg-gray-100 hover:border-gray-300': form.role !== role.value
                            }"
                        >
                            <div 
                                class="w-20 h-20 flex items-center justify-center rounded-full transition-all duration-300"
                                :class="{
                                    'bg-blue-100': form.role === role.value,
                                    'bg-gray-100 group-hover:bg-gray-200': form.role !== role.value
                                }"
                            >
                                <div 
                                    v-html="role.icon"
                                    class="w-12 h-12 transition-all duration-300"
                                    :class="{
                                        'text-blue-600': form.role === role.value,
                                        'text-gray-600 group-hover:text-gray-800': form.role !== role.value
                                    }"
                                ></div>
                            </div>
                            <div class="text-center">
                                <h3 
                                    class="font-semibold text-base transition-colors duration-300"
                                    :class="{
                                        'text-blue-600': form.role === role.value,
                                        'text-gray-700 group-hover:text-gray-900': form.role !== role.value
                                    }"
                                >
                                    {{ role.label }}
                                </h3>
                                <p 
                                    class="text-sm mt-2 transition-colors duration-300"
                                    :class="{
                                        'text-blue-500': form.role === role.value,
                                        'text-gray-500 group-hover:text-gray-700': form.role !== role.value
                                    }"
                                >
                                    {{ role.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <!-- Common Fields -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2 text-sm">1</span>
                        Informations personnelles
                    </h3>
                    
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel for="first_name" value="Prénom" style="color: #005183;" />
                                <TextInput 
                                    id="first_name" 
                                    type="text" 
                                    class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                    v-model="form.first_name" 
                                    required 
                                    placeholder="Entrez votre prénom"
                                />
                                <InputError class="mt-2" :message="form.errors.first_name" />
                            </div>
                            <div>
                                <InputLabel for="last_name" value="Nom de famille" style="color: #005183;" />
                                <TextInput 
                                    id="last_name" 
                                    type="text" 
                                    class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                    v-model="form.last_name" 
                                    required 
                                    placeholder="Entrez votre nom de famille"
                                />
                                <InputError class="mt-2" :message="form.errors.last_name" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="email" value="Email" style="color: #005183;" />
                                <TextInput 
                                    id="email" 
                                    type="email" 
                                    class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                    v-model="form.email" 
                                    required 
                                    placeholder="votre@email.com"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                            <div>
                                <InputLabel for="phone" value="Téléphone" style="color: #005183;" />
                                <TextInput 
                                    id="phone" 
                                    type="tel" 
                                    class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                    v-model="form.phone" 
                                    required 
                                    placeholder="+33 6 12 34 56 78"
                                />
                                <InputError class="mt-2" :message="form.errors.phone" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Specific Fields -->
                <transition name="fade-slide" mode="out-in">
                    <div v-if="form.role === 'startup'" class="role-specific-section">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2 text-sm">2</span>
                            Informations de l'entreprise
                        </h3>
                        
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <InputLabel for="startup_name" value="Nom de l'entreprise" style="color: #005183;" />
                                    <TextInput 
                                        id="startup_name" 
                                        type="text" 
                                        class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                        v-model="form.startup_name" 
                                        placeholder="Le nom de votre entreprise"
                                    />
                                    <InputError class="mt-2" :message="form.errors.startup_name" />
                                </div>
                                <div>
                                    <InputLabel for="code_fiscal" value="Code fiscal" style="color: #005183;" />
                                    <TextInput 
                                        id="code_fiscal" 
                                        type="text" 
                                        class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                        v-model="form.code_fiscal" 
                                        placeholder="Code fiscal de l'entreprise"
                                    />
                                    <InputError class="mt-2" :message="form.errors.code_fiscal" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="startup_email" value="Email de l'entreprise" style="color: #005183;" />
                                    <TextInput 
                                        id="startup_email" 
                                        type="email" 
                                        class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                        v-model="form.startup_email" 
                                        placeholder="contact@entreprise.com"
                                    />
                                    <InputError class="mt-2" :message="form.errors.startup_email" />
                                </div>
                                <div>
                                    <InputLabel for="startup_phone" value="Téléphone de l'entreprise" style="color: #005183;" />
                                    <TextInput 
                                        id="startup_phone" 
                                        type="tel" 
                                        class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                        v-model="form.startup_phone" 
                                        placeholder="+33 1 23 45 67 89"
                                    />
                                    <InputError class="mt-2" :message="form.errors.startup_phone" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else-if="form.role === 'formateur'" class="role-specific-section">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2 text-sm">2</span>
                            Profil professionnel
                        </h3>
                        
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <div class="mb-4">
                                <InputLabel for="speciality" value="Spécialité" style="color: #005183;" />
                                <TextInput 
                                    id="speciality" 
                                    type="text" 
                                    class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                    v-model="form.speciality" 
                                    placeholder="Ex: Développement web, Intelligence artificielle, Design UX/UI"
                                />
                                <InputError class="mt-2" :message="form.errors.speciality" />
                            </div>
                            <div class="mb-4">
                                <InputLabel for="description" value="Description" style="color: #005183;" />
                                <textarea 
                                    id="description" 
                                    class="mt-1 block w-full rounded-lg bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30 transition duration-300" 
                                    rows="4"
                                    v-model="form.description" 
                                    placeholder="Parlez-nous de votre expérience, votre parcours et vos compétences"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                            
                            <div>
                                <InputLabel for="cv_file" value="CV (Format PDF, DOC, DOCX)" style="color: #005183;" />
                                <div class="mt-2 flex items-center">
                                    <label 
                                        for="cv_file" 
                                        class="cursor-pointer flex items-center px-4 py-3 bg-blue-50 text-blue-600 rounded-lg border border-blue-200 hover:bg-blue-100 transition-all duration-300 w-full"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span v-if="!form.cv_file" class="truncate">Sélectionner votre CV</span>
                                        <span v-else class="truncate">{{ form.cv_file.name }}</span>
                                    </label>
                                    <input
                                        id="cv_file"
                                        name="cv_file"
                                        type="file"
                                        class="hidden"
                                        @input="handleCvFileChange"
                                        accept=".pdf,.doc,.docx"
                                    />
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX (5MB max)</p>
                                <InputError class="mt-2" :message="form.errors.cv_file" />
                                
                                <div v-if="form.cv_file" class="mt-3 text-sm text-blue-600 flex items-center p-2 bg-blue-50 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="truncate">Fichier sélectionné: {{ form.cv_file.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-else class="empty-section"></div>
                </transition>

                <!-- Password Fields -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2 text-sm">{{ form.role === 'user' ? '2' : '3' }}</span>
                        Sécurité
                    </h3>
                    
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="password" value="Mot de passe" style="color: #005183;" />
                                <TextInput 
                                    id="password" 
                                    type="password" 
                                    class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                    v-model="form.password" 
                                    required 
                                    placeholder="Entrez votre mot de passe"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                                <p class="text-xs text-gray-500 mt-1">8 caractères minimum avec lettres et chiffres</p>
                            </div>
                            <div>
                                <InputLabel for="password_confirmation" value="Confirmez le mot de passe" style="color: #005183;" />
                                <TextInput 
                                    id="password_confirmation" 
                                    type="password" 
                                    class="mt-1 block w-full bg-white border-gray-300 text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/30" 
                                    v-model="form.password_confirmation" 
                                    required 
                                    placeholder="Confirmez votre mot de passe"
                                />
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between mt-10 gap-4">
                    <Link 
                        :href="route('login')" 
                        class="text-blue-600 hover:text-blue-800 transition-colors duration-300 flex items-center order-2 sm:order-1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        Déjà inscrit? Connectez-vous
                    </Link>

                    <button
                        type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white transition-all duration-300 ease-in-out flex items-center justify-center shadow-md w-full sm:w-auto order-1 sm:order-2"
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
                        {{ form.processing ? "Inscription en cours..." : "Créer mon compte" }}
                        <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Fade and slide transitions for role-specific sections */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.4s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

/* Custom label color - can be applied globally if needed */
:deep(.form-label) {
    color: #005183 !important;
}

/* Custom focus styles */
input:focus, 
textarea:focus, 
select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .role-specific-section, .empty-section {
        min-height: 100px;
    }
}
</style>