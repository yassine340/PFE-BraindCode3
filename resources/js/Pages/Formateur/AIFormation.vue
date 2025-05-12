<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

// Récupérer le token CSRF
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Configuration globale d'axios
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

const form = reactive({
    topic: '',
    details: '',
    level: 'intermédiaire'
});

const levels = [
    { value: 'débutant', label: 'Débutant' },
    { value: 'intermédiaire', label: 'Intermédiaire' },
    { value: 'avancé', label: 'Avancé' }
];

const loading = ref(false);
const error = ref(null);
const generatedFormation = ref(null);
const streamingText = ref('');
const isStreaming = ref(false);

// Génération standard (sans streaming)
const generateFormationStandard = async () => {
    loading.value = true;
    error.value = null;
    streamingText.value = '';
    generatedFormation.value = null;
    
    try {
        const response = await axios.post('/formateur/generate-formation', form);
        generatedFormation.value = response.data.data;
        console.log('Formation générée:', generatedFormation.value);
    } catch (err) {
        console.error('Erreur:', err);
        error.value = err.response?.data?.message || 'Une erreur est survenue';
    } finally {
        loading.value = false;
    }
};

// Génération avec streaming
const generateFormationStreaming = async () => {
    loading.value = true;
    error.value = null;
    streamingText.value = '';
    isStreaming.value = true;
    generatedFormation.value = null;
    
    try {
        const eventSource = new EventSource('/formateur/generate-formation-stream?' + new URLSearchParams({
            topic: form.topic,
            details: form.details,
            level: form.level,
            _token: token // Inclure le token CSRF
        }));
        
        eventSource.addEventListener('message', (event) => {
            try {
                const data = JSON.parse(event.data);
                
                if (data.type === 'start') {
                    console.log('Streaming commencé');
                } 
                else if (data.type === 'update') {
                    streamingText.value += data.content;
                } 
                else if (data.type === 'complete') {
                    isStreaming.value = false;
                    
                    if (data.is_json) {
                        generatedFormation.value = data.data;
                    } else {
                        streamingText.value = data.data;
                    }
                    
                    eventSource.close();
                }
                else if (data.type === 'error') {
                    error.value = data.message;
                    isStreaming.value = false;
                    eventSource.close();
                }
            } catch (err) {
                console.error('Erreur parsing SSE:', err);
            }
        });
        
        eventSource.addEventListener('error', (e) => {
            console.error('Erreur EventSource:', e);
            error.value = 'Erreur de connexion au serveur';
            isStreaming.value = false;
            eventSource.close();
            loading.value = false;
        });
        
    } catch (err) {
        console.error('Erreur globale streaming:', err);
        error.value = 'Une erreur est survenue';
        isStreaming.value = false;
    }
};

// Utiliser streaming si disponible, sinon méthode standard
const generateFormation = () => {
    if ('EventSource' in window) {
        // Essayer la méthode streaming
        generateFormationStreaming().catch(() => {
            // Fallback à la méthode standard en cas d'échec
            generateFormationStandard();
        });
    } else {
        // Navigateur ne supporte pas EventSource, utiliser méthode standard
        generateFormationStandard();
    }
};

const useGeneratedFormation = async () => {
    // Rediriger vers le formulaire de création de formation 
    // avec les données pré-remplies
    // Cette fonction sera implémentée plus tard
    alert('Fonctionnalité à implémenter: créer une formation avec ces données');
};
</script>

<template>
    <div>
        <Head title="Générer une formation avec l'IA" />
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-semibold mb-6">Générer une formation avec l'IA</h1>
                    
                    <form @submit.prevent="generateFormation" class="space-y-6">
                        <!-- Ajouter un champ caché pour CSRF -->
                        <input type="hidden" name="_token" :value="token">
                        
                        <div>
                            <label for="topic" class="block text-sm font-medium text-gray-700">Sujet de la formation</label>
                            <input 
                                id="topic" 
                                v-model="form.topic" 
                                type="text" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Ex: Développement web avec Laravel" 
                                required
                            />
                        </div>
                        
                        <div>
                            <label for="details" class="block text-sm font-medium text-gray-700">Détails supplémentaires (optionnel)</label>
                            <textarea 
                                id="details" 
                                v-model="form.details" 
                                rows="3" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Objectifs spécifiques, public cible, etc."
                            ></textarea>
                        </div>
                        
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700">Niveau</label>
                            <select 
                                id="level" 
                                v-model="form.level" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option v-for="level in levels" :key="level.value" :value="level.value">
                                    {{ level.label }}
                                </option>
                            </select>
                        </div>
                        
                        <div>
                            <button 
                                type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                :disabled="loading"
                            >
                                <span v-if="loading">Génération en cours...</span>
                                <span v-else>Générer la formation</span>
                            </button>
                        </div>
                    </form>
                    
                    <div v-if="error" class="mt-4 p-4 bg-red-100 text-red-700 rounded-md">
                        {{ error }}
                    </div>
                    
                    <!-- Affichage du texte en streaming -->
                    <div v-if="streamingText && isStreaming" class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Génération en cours...</h2>
                        <div class="bg-gray-50 p-6 rounded-md">
                            <div class="whitespace-pre-wrap animate-pulse">{{ streamingText }}</div>
                        </div>
                    </div>
                    
                    <!-- Affichage du résultat final structuré -->
                    <div v-if="generatedFormation" class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Formation générée</h2>
                        
                        <div class="bg-gray-50 p-6 rounded-md">
                            <h3 class="text-lg font-bold">{{ generatedFormation.titre }}</h3>
                            <p class="mt-2">{{ generatedFormation.description }}</p>
                            
                            <div class="mt-6 space-y-6">
                                <div v-for="(module, index) in generatedFormation.modules" :key="index" class="border-l-4 border-indigo-500 pl-4">
                                    <h4 class="font-bold">Module {{ index + 1 }}: {{ module.titre }}</h4>
                                    <p>{{ module.description }}</p>
                                    
                                    <div class="mt-4 ml-4 space-y-2">
                                        <div v-for="(lecon, leconIndex) in module.lecons" :key="leconIndex">
                                            <h5 class="font-semibold">Leçon {{ leconIndex + 1 }}: {{ lecon.titre }}</h5>
                                            <p class="text-sm">{{ lecon.contenu }}</p>
                                        </div>
                                    </div>
                                    
                                    <div v-if="module.quiz" class="mt-4 ml-4 p-3 bg-indigo-50 rounded-md">
                                        <h5 class="font-semibold">Quiz: {{ module.quiz.titre }}</h5>
                                        <div v-for="(question, qIndex) in module.quiz.questions" :key="qIndex" class="mt-2">
                                            <p>{{ question.contenu }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button 
                                @click="useGeneratedFormation" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                Utiliser cette formation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>