<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const activeTab = ref('profile');

const tabs = [
    { id: 'profile', label: 'Informations du profil', icon: 'user', description: 'Mettez à jour vos informations personnelles' },
    { id: 'security', label: 'Sécurité du compte', icon: 'shield', description: 'Modifiez votre mot de passe' },
    { id: 'danger', label: 'Zone de danger', icon: 'trash', description: 'Supprimer votre compte' }
];

const getTabIcon = (iconName) => {
    const icons = {
        user: `<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>`,
        shield: `<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>`,
        trash: `<polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>`
    };
    return icons[iconName];
};
</script>

<template>
    <Head title="Mon Profil" />

    <AuthenticatedLayout>
        <div class="page-background"></div>
        
        <template #header>
            <div class="enhanced-header">
                <div class="header-content">
                    <div class="header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" 
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                            stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div class="header-text">
                        <h1 class="profile-main-title">Paramètres du compte</h1>
                        <p class="header-subtitle">Gérez votre profil et vos préférences de sécurité</p>
                    </div>
                </div>
                <div class="header-decoration">
                    <div class="decoration-dot"></div>
                    <div class="decoration-dot"></div>
                    <div class="decoration-dot"></div>
                </div>
            </div>
        </template>

        <div class="profile-dashboard">
            <!-- Sidebar Navigation -->
            <div class="profile-sidebar">
                <div class="sidebar-content">
                    <h2 class="sidebar-title">Mon Compte</h2>
                    
                    <div class="sidebar-menu">
                        <button
                            v-for="tab in tabs"
                            :key="tab.id"
                            @click="activeTab = tab.id"
                            class="sidebar-item"
                            :class="{ 
                                'sidebar-item-active': activeTab === tab.id,
                                'sidebar-item-danger': tab.id === 'danger' 
                            }"
                        >
                            <div class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" 
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                    stroke-linejoin="round" v-html="getTabIcon(tab.icon)">
                                </svg>
                            </div>
                            <div class="sidebar-text">
                                <span class="sidebar-label">{{ tab.label }}</span>
                                <span class="sidebar-description">{{ tab.description }}</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="profile-main">
                <transition name="slide-fade" mode="out-in">
                    <div v-if="activeTab === 'profile'" key="profile" class="content-panel">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>

                    <div v-else-if="activeTab === 'security'" key="security" class="content-panel">
                        <UpdatePasswordForm />
                    </div>

                    <div v-else-if="activeTab === 'danger'" key="danger" class="content-panel">
                        <DeleteUserForm />
                    </div>
                </transition>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Page Background */
.page-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.03) 0%, transparent 30%),
        radial-gradient(circle at 80% 70%, rgba(124, 58, 237, 0.05) 0%, transparent 40%),
        linear-gradient(to bottom, #f9fafb, #f5f3ff 80%, #ede9fe);
    z-index: -1;
    opacity: 0.7;
    pointer-events: none;
}

/* Enhanced Header Styles */
.enhanced-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 0;
    position: relative;
}

.enhanced-header::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, 
        rgba(139, 92, 246, 0.3),
        rgba(109, 40, 217, 0.5) 50%,
        rgba(139, 92, 246, 0.3)
    );
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: #ede9fe;
    color: #6d28d9;
    box-shadow: 
        0 4px 10px rgba(124, 58, 237, 0.15),
        0 0 0 6px rgba(124, 58, 237, 0.05);
}

.header-icon svg {
    width: 24px;
    height: 24px;
}

.header-text {
    display: flex;
    flex-direction: column;
}

.profile-main-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #4c1d95;
    margin: 0 0 0.25rem 0;
    letter-spacing: -0.025em;
}

.header-subtitle {
    font-size: 0.95rem;
    color: #6b7280;
    margin: 0;
}

.header-decoration {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.decoration-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #8b5cf6;
}

.decoration-dot:nth-child(1) {
    opacity: 0.8;
}

.decoration-dot:nth-child(2) {
    opacity: 0.5;
}

.decoration-dot:nth-child(3) {
    opacity: 0.3;
}

.profile-dashboard {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    min-height: 600px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Sidebar Styles */
.profile-sidebar {
    position: relative;
}

.sidebar-content {
    position: sticky;
    top: 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(139, 92, 246, 0.1);
    overflow: hidden;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(139, 92, 246, 0.1);
}

.sidebar-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #4c1d95;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 2px solid #f5f3ff;
}

.sidebar-menu {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 12px;
    text-align: left;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: white;
}

.sidebar-item:hover {
    background-color: #f5f3ff;
}

.sidebar-item-active {
    background-color: #f5f3ff;
    position: relative;
}

.sidebar-item-active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #8b5cf6, #6d28d9);
    border-radius: 0 4px 4px 0;
}

.sidebar-item-danger:hover {
    background-color: #fee2e2;
}

.sidebar-item-danger.sidebar-item-active {
    background-color: #fee2e2;
}

.sidebar-item-danger.sidebar-item-active::before {
    background: linear-gradient(180deg, #f87171, #ef4444);
}

.sidebar-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background-color: #ede9fe;
    color: #6d28d9;
    flex-shrink: 0;
}

.sidebar-item-danger .sidebar-icon {
    background-color: #fee2e2;
    color: #b91c1c;
}

.sidebar-item-active .sidebar-icon {
    background-color: #ddd6fe;
}

.sidebar-item-danger.sidebar-item-active .sidebar-icon {
    background-color: #fecaca;
}

.sidebar-icon svg {
    width: 20px;
    height: 20px;
}

.sidebar-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.sidebar-label {
    font-weight: 600;
    color: #1f2937;
}

.sidebar-item-danger .sidebar-label {
    color: #b91c1c;
}

.sidebar-description {
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

/* Content Area */
.profile-main {
    padding: 0.5rem;
}

.content-panel {
    max-width: 900px;
    position: relative;
}

/* Animation */
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: opacity 0.3s, transform 0.3s;
}

.slide-fade-enter-from {
    opacity: 0;
    transform: translateX(20px);
}

.slide-fade-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}

/* Responsive Adjustments */
@media (max-width: 1024px) {
    .profile-dashboard {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .profile-sidebar {
        position: static;
    }
    
    .sidebar-content {
        position: static;
    }
    
    .sidebar-menu {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .sidebar-item {
        flex: 1;
        min-width: 200px;
    }
    
    .enhanced-header {
        padding: 1.25rem 0;
    }
    
    .header-decoration {
        display: none;
    }
}

@media (max-width: 640px) {
    .sidebar-menu {
        flex-direction: column;
    }
    
    .sidebar-item {
        min-width: unset;
    }
    
    .header-content {
        gap: 1rem;
    }
    
    .header-icon {
        width: 40px;
        height: 40px;
    }
    
    .profile-main-title {
        font-size: 1.5rem;
    }
}

/* Dark Mode */
@media (prefers-color-scheme: dark) {
    .page-background {
        background: 
            radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 30%),
            radial-gradient(circle at 80% 70%, rgba(124, 58, 237, 0.15) 0%, transparent 40%),
            linear-gradient(to bottom, #0f172a, #1e1b4b 80%, #312e81);
    }

    .profile-main-title {
        color: #c4b5fd;
    }
    
    .header-subtitle {
        color: #9ca3af;
    }
    
    .header-icon {
        background-color: #4c1d95;
        color: #c4b5fd;
    }
    
    .sidebar-content {
        background: #1e1b4b;
        border-color: rgba(139, 92, 246, 0.2);
    }
    
    .sidebar-title {
        color: #c4b5fd;
        border-bottom-color: #312e81;
    }
    
    .sidebar-item {
        background-color: #1e1b4b;
    }
    
    .sidebar-item:hover {
        background-color: #312e81;
    }
    
    .sidebar-item-active {
        background-color: #312e81;
    }
    
    .sidebar-item-danger:hover {
        background-color: #450a0a;
    }
    
    .sidebar-item-danger.sidebar-item-active {
        background-color: #450a0a;
    }
    
    .sidebar-icon {
        background-color: #4c1d95;
        color: #c4b5fd;
    }
    
    .sidebar-item-danger .sidebar-icon {
        background-color: #7f1d1d;
        color: #fca5a5;
    }
    
    .sidebar-item-active .sidebar-icon {
        background-color: #6d28d9;
    }
    
    .sidebar-item-danger.sidebar-item-active .sidebar-icon {
        background-color: #b91c1c;
    }
    
    .sidebar-label {
        color: #f3f4f6;
    }
    
    .sidebar-description {
        color: #9ca3af;
    }
}
</style>