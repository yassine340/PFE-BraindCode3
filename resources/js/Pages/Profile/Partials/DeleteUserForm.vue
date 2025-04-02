DeleteUserForm.vue
<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const formFocus = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="profile-card delete-account-card">
        <div class="profile-header">
            <h2 class="profile-title">Supprimer votre compte</h2>
            <div class="profile-decoration"></div>
        </div>

        <p class="profile-subtitle">
            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. 
            Avant de supprimer votre compte, veuillez télécharger les données ou informations que vous souhaitez conserver.
        </p>

        <div class="delete-action">
            <button @click="confirmUserDeletion" class="delete-button">
                <span class="button-content">
                    <svg class="button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    Supprimer mon compte
                </span>
            </button>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal" class="custom-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h2 class="modal-title">
                        Êtes-vous sûr de vouloir supprimer votre compte ?
                    </h2>
                </div>

                <p class="modal-description">
                    Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. 
                    Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.
                </p>

                <div class="password-input-container">
                    <div class="form-field modal-field" :class="{ 'field-focused': formFocus === 'password' }">
                        <div class="field-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        
                        <div class="field-content">
                            <InputLabel for="password" value="Mot de passe" class="field-label" />
                            <TextInput
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                class="field-input"
                                @focus="formFocus = 'password'"
                                @blur="formFocus = null"
                                @keyup.enter="deleteUser"
                                autocomplete="current-password"
                            />
                            <InputError class="field-error" :message="form.errors.password" />
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button @click="closeModal" class="cancel-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                        Annuler
                    </button>

                    <button
                        @click="deleteUser"
                        class="confirm-delete-button"
                        :class="{ 'button-processing': form.processing }"
                        :disabled="form.processing"
                    >
                        <span class="button-content">
                            <svg v-if="form.processing" class="button-spinner" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" stroke-width="4" stroke-dasharray="30 30" stroke-linecap="round"></circle>
                            </svg>
                            <svg v-else class="action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            Supprimer le compte
                        </span>
                    </button>
                </div>
            </div>
        </Modal>
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

.delete-account-card {
    background: linear-gradient(135deg, #ffffff 0%, #fff1f2 100%);
}

.profile-card:hover {
    box-shadow: 0 15px 35px rgba(124, 58, 237, 0.15), 0 3px 10px rgba(124, 58, 237, 0.1);
}

.delete-account-card:hover {
    box-shadow: 0 15px 35px rgba(239, 68, 68, 0.15), 0 3px 10px rgba(239, 68, 68, 0.1);
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

.delete-account-card::before {
    background: linear-gradient(90deg, #f87171, #ef4444, #b91c1c);
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

.delete-account-card .profile-title {
    color: #b91c1c;
}

.profile-decoration {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #8b5cf6, #6d28d9);
    border-radius: 2px;
}

.delete-account-card .profile-decoration {
    background: linear-gradient(90deg, #f87171, #ef4444);
}

.profile-subtitle {
    font-size: 0.95rem;
    color: #6b7280;
    margin-bottom: 2rem;
    line-height: 1.5;
}

.delete-action {
    margin-top: 1.5rem;
}

.delete-button {
    position: relative;
    display: inline-block;
    background: linear-gradient(90deg, #f87171, #ef4444);
    color: white;
    font-weight: 600;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
}

.delete-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: all 0.6s;
}

.delete-button:hover {
    box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3);
    transform: translateY(-2px);
}

.delete-button:hover::before {
    left: 100%;
}

.delete-button:active {
    transform: translateY(0);
    box-shadow: 0 2px 5px rgba(239, 68, 68, 0.2);
}

.button-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.button-icon {
    width: 18px;
    height: 18px;
    stroke-width: 2px;
}

/* Modal Styles */
:deep(.custom-modal) {
    overflow: visible;
    border-radius: 16px;
}

.modal-content {
    position: relative;
    background: linear-gradient(135deg, #ffffff 0%, #fff1f2 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    max-width: 500px;
    margin: 0 auto;
    overflow: hidden;
}

.modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: linear-gradient(90deg, #f87171, #ef4444, #b91c1c);
}

.modal-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.modal-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    background-color: #fee2e2;
    color: #b91c1c;
    border-radius: 50%;
    flex-shrink: 0;
}

.modal-icon svg {
    width: 24px;
    height: 24px;
    stroke-width: 2px;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #b91c1c;
    line-height: 1.3;
}

.modal-description {
    font-size: 0.95rem;
    color: #6b7280;
    line-height: 1.5;
    margin-bottom: 1.5rem;
}

.password-input-container {
    margin: 1.5rem 0;
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

.modal-field {
    border-color: #fecaca;
}

.form-field:hover {
    border-color: #c4b5fd;
    box-shadow: 0 4px 10px rgba(124, 58, 237, 0.1);
}

.modal-field:hover {
    border-color: #fca5a5;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.1);
}

.field-focused {
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
}

.modal-field.field-focused {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
}

.field-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 1rem;
    background-color: #f5f3ff;
    color: #6d28d9;
}

.modal-field .field-icon {
    background-color: #fee2e2;
    color: #b91c1c;
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

.modal-field .field-label {
    color: #b91c1c;
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

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

.cancel-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background-color: transparent;
    color: #6b7280;
    font-weight: 600;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.2s;
    cursor: pointer;
}

.cancel-button:hover {
    background-color: #f9fafb;
    border-color: #d1d5db;
    color: #4b5563;
}

.confirm-delete-button {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: linear-gradient(90deg, #f87171, #ef4444);
    color: white;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
    cursor: pointer;
    overflow: hidden;
}

.confirm-delete-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: all 0.6s;
}

.confirm-delete-button:hover {
    box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3);
    transform: translateY(-2px);
}

.confirm-delete-button:hover::before {
    left: 100%;
}

.confirm-delete-button:active {
    transform: translateY(0);
    box-shadow: 0 2px 5px rgba(239, 68, 68, 0.2);
}

.action-icon {
    width: 16px;
    height: 16px;
    stroke-width: 2.5px;
}

.button-processing {
    background: #ef4444;
    cursor: not-allowed;
    opacity: 0.8;
    transform: none !important;
}

.button-spinner {
    animation: rotate 1.5s linear infinite;
    width: 16px;
    height: 16px;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Mode sombre */
@media (prefers-color-scheme: dark) {
    .profile-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
    }
    
    .delete-account-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #450a0a 100%);
    }
    
    .profile-title {
        color: #c4b5fd;
    }
    
    .delete-account-card .profile-title {
        color: #fca5a5;
    }
    
    .profile-subtitle {
        color: #a1a1aa;
    }
    
    .modal-content {
        background: linear-gradient(135deg, #1e1b4b 0%, #450a0a 100%);
    }
    
    .modal-title {
        color: #fca5a5;
    }
    
    .modal-description {
        color: #a1a1aa;
    }
    
    .modal-icon {
        background-color: #7f1d1d;
        color: #fca5a5;
    }
    
    .form-field {
        background-color: #1f2937;
        border-color: #374151;
    }
    
    .modal-field {
        border-color: #7f1d1d;
    }
    
    .modal-field .field-icon {
        background-color: #7f1d1d;
        color: #fca5a5;
    }
    
    .field-input {
        color: #f3f4f6 !important;
    }
    
    .cancel-button {
        color: #d1d5db;
        border-color: #4b5563;
    }
    
    .cancel-button:hover {
        background-color: #374151;
        border-color: #6b7280;
        color: #f3f4f6;
    }
}
</style>