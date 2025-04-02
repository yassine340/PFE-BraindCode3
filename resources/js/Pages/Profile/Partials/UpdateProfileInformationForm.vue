.vue
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const formFocus = ref(null);

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
});
</script>

<template>
    <section class="profile-card">
        <div class="profile-header">
            <h2 class="profile-title">Votre Profil</h2>
            <div class="profile-decoration"></div>
        </div>

        <form @submit.prevent="form.patch(route('profile.update'))" class="profile-form">
            <div class="form-fields-container">
                <!-- Prénom -->
                <div class="form-field" :class="{ 'field-focused': formFocus === 'first_name' }">
                    <div class="field-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    
                    <div class="field-content">
                        <InputLabel for="first_name" value="Prénom" class="field-label" />
                        <TextInput
                            id="first_name"
                            type="text"
                            class="field-input"
                            v-model="form.first_name"
                            required
                            @focus="formFocus = 'first_name'"
                            @blur="formFocus = null"
                            autocomplete="first_name"
                        />
                        <InputError class="field-error" :message="form.errors.first_name" />
                    </div>
                </div>

                <!-- Nom -->
                <div class="form-field" :class="{ 'field-focused': formFocus === 'last_name' }">
                    <div class="field-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    
                    <div class="field-content">
                        <InputLabel for="last_name" value="Nom" class="field-label" />
                        <TextInput
                            id="last_name"
                            type="text"
                            class="field-input"
                            v-model="form.last_name"
                            required
                            @focus="formFocus = 'last_name'"
                            @blur="formFocus = null"
                            autocomplete="last_name"
                        />
                        <InputError class="field-error" :message="form.errors.last_name" />
                    </div>
                </div>

                <!-- Email -->
                <div class="form-field form-field-full" :class="{ 'field-focused': formFocus === 'email' }">
                    <div class="field-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    
                    <div class="field-content">
                        <InputLabel for="email" value="Adresse email" class="field-label" />
                        <TextInput
                            id="email"
                            type="email"
                            class="field-input"
                            v-model="form.email"
                            required
                            @focus="formFocus = 'email'"
                            @blur="formFocus = null"
                            autocomplete="username"
                        />
                        <InputError class="field-error" :message="form.errors.email" />
                    </div>
                </div>
            </div>

            <!-- Email verification -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="verification-alert">
                <div class="alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="currentColor"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor"></line>
                    </svg>
                </div>
                <div class="alert-content">
                    <p>
                        Votre adresse email n'est pas vérifiée.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="verification-link"
                        >
                            Renvoyer l'email de vérification
                        </Link>
                    </p>

                    <div v-show="status === 'verification-link-sent'" class="verification-sent">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                            <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Un nouveau lien de vérification a été envoyé à votre adresse email.
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <div class="form-actions">
                <button 
                    type="submit"
                    :disabled="form.processing" 
                    class="submit-button"
                    :class="{'button-processing': form.processing}"
                >
                    <span class="button-content">
                        <svg v-if="form.processing" class="button-spinner" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" stroke-width="4" stroke-dasharray="30 30" stroke-linecap="round"></circle>
                        </svg>
                        <svg v-else class="button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Sauvegarder les modifications
                    </span>
                </button>

                <Transition
                    enter-active-class="success-enter"
                    leave-active-class="success-leave"
                >
                    <div v-if="form.recentlySuccessful" class="success-message">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Profil mis à jour avec succès!
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
.profile-card {
    background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(124, 58, 237, 0.1), 0 1px 3px rgba(124, 58, 237, 0.05);
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    transform: translateZ(0);
    transition: all 0.3s;
}

.profile-card:hover {
    box-shadow: 0 15px 35px rgba(124, 58, 237, 0.15), 0 3px 10px rgba(124, 58, 237, 0.1);
}

.profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: linear-gradient(90deg, #8b5cf6, #6d28d9, #4c1d95);
}

.profile-header {
    margin-bottom: 2rem;
    position: relative;
}

.profile-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #4c1d95;
    margin-bottom: 0.5rem;
}

.profile-decoration {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #8b5cf6, #6d28d9);
    border-radius: 2px;
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-fields-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.form-field-full {
    grid-column: 1 / -1;
}

.form-field {
    display: flex;
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.form-field:hover {
    border-color: #c4b5fd;
    box-shadow: 0 4px 10px rgba(124, 58, 237, 0.1);
}

.field-focused {
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
}

.field-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 1rem;
    background-color: #f5f3ff;
    color: #6d28d9;
}

.field-icon svg {
    width: 20px;
    height: 20px;
    stroke-width: 2px;
}

.field-content {
    flex: 1;
    padding: 0.5rem 1rem;
}

.field-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6d28d9;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.field-input {
    width: 100%;
    border: none !important;
    padding: 0.5rem 0 !important;
    font-size: 1rem;
    background: transparent !important;
    color: #1f2937;
    outline: none !important;
    box-shadow: none !important;
}

.field-input:focus {
    box-shadow: none !important;
    border: none !important;
}

.field-error {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.verification-alert {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 12px;
    background-color: #fef3c7;
    border: 1px solid #fbbf24;
    color: #92400e;
    animation: slideIn 0.4s ease-out;
}

.alert-icon {
    flex-shrink: 0;
    color: #f59e0b;
}

.alert-icon svg {
    width: 24px;
    height: 24px;
    stroke-width: 2px;
}

.alert-content {
    flex: 1;
    font-size: 0.875rem;
    line-height: 1.5;
}

.verification-link {
    display: inline-block;
    margin-top: 0.5rem;
    color: #b45309;
    font-weight: 600;
    text-decoration: none;
    border-bottom: 2px dotted #fbbf24;
    transition: all 0.2s;
}

.verification-link:hover {
    color: #92400e;
    border-bottom-style: solid;
}

.verification-sent {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.75rem;
    padding: 0.5rem 0.75rem;
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 6px;
    font-weight: 600;
    color: #16a34a;
    animation: fadeIn 0.4s ease-out;
}

.verification-sent svg {
    width: 16px;
    height: 16px;
    stroke-width: 2.5px;
    color: #16a34a;
}

.form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1rem;
    position: relative;
}

.submit-button {
    position: relative;
    display: inline-block;
    background: linear-gradient(90deg, #8b5cf6, #6d28d9);
    color: white;
    font-weight: 600;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(124, 58, 237, 0.2);
}

.submit-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: all 0.6s;
}

.submit-button:hover {
    box-shadow: 0 6px 15px rgba(124, 58, 237, 0.3);
    transform: translateY(-2px);
}

.submit-button:hover::before {
    left: 100%;
}

.submit-button:active {
    transform: translateY(0);
    box-shadow: 0 2px 5px rgba(124, 58, 237, 0.2);
}

.button-processing {
    background: #8b5cf6;
    cursor: not-allowed;
    opacity: 0.8;
    transform: none !important;
}

.button-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.button-icon, .button-spinner {
    width: 18px;
    height: 18px;
    stroke-width: 2px;
}

.button-spinner {
    animation: rotate 1.5s linear infinite;
}

.success-message {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    border-radius: 12px;
    background-color: #f0fdf4;
    border: 1px solid #86efac;
    color: #16a34a;
    font-weight: 600;
    font-size: 0.875rem;
}

.success-message svg {
    width: 20px;
    height: 20px;
    stroke-width: 2px;
}

/* Animations */
@keyframes slideIn {
    from { 
        opacity: 0;
        transform: translateY(-10px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.success-enter {
    animation: successEnter 0.5s ease-out forwards;
}

.success-leave {
    animation: successLeave 0.3s ease-in forwards;
}

@keyframes successEnter {
    from { 
        opacity: 0;
        transform: scale(0.9);
    }
    50% {
        transform: scale(1.05);
    }
    to { 
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes successLeave {
    from { 
        opacity: 1;
        transform: scale(1);
    }
    to { 
        opacity: 0;
        transform: scale(0.9);
    }
}

/* Mode sombre */
@media (prefers-color-scheme: dark) {
    .profile-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
    }
    
    .profile-title {
        color: #c4b5fd;
    }
    
    .form-field {
        background-color: #1f2937;
        border-color: #374151;
    }
    
    .form-field:hover {
        border-color: #6d28d9;
    }
    
    .field-icon {
        background-color: #312e81;
    }
    
    .field-label {
        color: #c4b5fd;
    }
    
    .field-input {
        color: #f3f4f6 !important;
    }
    
    .verification-alert {
        background-color: #422006;
        border-color: #b45309;
        color: #fcd34d;
    }
    
    .verification-link {
        color: #fbbf24;
        border-bottom-color: #b45309;
    }
    
    .verification-link:hover {
        color: #f59e0b;
    }
    
    .verification-sent {
        background-color: rgba(0, 0, 0, 0.2);
    }
    
    .success-message {
        background-color: #064e3b;
        border-color: #10b981;
        color: #a7f3d0;
    }
}
</style>