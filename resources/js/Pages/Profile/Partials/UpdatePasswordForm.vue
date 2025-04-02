<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const formFocus = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="profile-card">
        <div class="profile-header">
            <h2 class="profile-title">Modifier votre mot de passe</h2>
            <div class="profile-decoration"></div>
        </div>

        <p class="profile-subtitle">
            Assurez-vous que votre compte utilise un mot de passe long et unique pour une sécurité optimale.
        </p>

        <form @submit.prevent="updatePassword" class="profile-form">
            <div class="form-fields-container">
                <!-- Mot de passe actuel -->
                <div class="form-field form-field-full" :class="{ 'field-focused': formFocus === 'current_password' }">
                    <div class="field-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    
                    <div class="field-content">
                        <InputLabel for="current_password" value="Mot de passe actuel" class="field-label" />
                        <TextInput
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            class="field-input"
                            @focus="formFocus = 'current_password'"
                            @blur="formFocus = null"
                            autocomplete="current-password"
                        />
                        <InputError class="field-error" :message="form.errors.current_password" />
                    </div>
                </div>

                <!-- Nouveau mot de passe -->
                <div class="form-field form-field-full" :class="{ 'field-focused': formFocus === 'password' }">
                    <div class="field-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 13a1 1 0 1 1 1-1 1 1 0 0 1-1 1zm1-4.5a1 1 0 0 1-2 0v-2a1 1 0 0 1 2 0z"></path>
                        </svg>
                    </div>
                    
                    <div class="field-content">
                        <InputLabel for="password" value="Nouveau mot de passe" class="field-label" />
                        <TextInput
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="field-input"
                            @focus="formFocus = 'password'"
                            @blur="formFocus = null"
                            autocomplete="new-password"
                        />
                        <InputError class="field-error" :message="form.errors.password" />
                    </div>
                </div>

                <!-- Confirmation du mot de passe -->
                <div class="form-field form-field-full" :class="{ 'field-focused': formFocus === 'password_confirmation' }">
                    <div class="field-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    
                    <div class="field-content">
                        <InputLabel for="password_confirmation" value="Confirmer le mot de passe" class="field-label" />
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="field-input"
                            @focus="formFocus = 'password_confirmation'"
                            @blur="formFocus = null"
                            autocomplete="new-password"
                        />
                        <InputError class="field-error" :message="form.errors.password_confirmation" />
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
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Mettre à jour le mot de passe
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
                        Mot de passe mis à jour avec succès!
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
    margin-bottom: 1rem;
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

.profile-subtitle {
    font-size: 0.95rem;
    color: #6b7280;
    margin-bottom: 2rem;
    line-height: 1.5;
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
    
    .profile-subtitle {
        color: #a1a1aa;
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
    
    .success-message {
        background-color: #064e3b;
        border-color: #10b981;
        color: #a7f3d0;
    }
}
</style>