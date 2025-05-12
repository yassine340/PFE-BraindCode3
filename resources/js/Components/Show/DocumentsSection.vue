<template>
  <div class="mb-8">
    <h4 class="flex items-center text-lg font-bold text-gray-800 mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      Documents
    </h4>
    
    <div class="grid gap-6">
      <div v-for="document in documents" :key="document.id"
           class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100">
        <div class="p-4 border-b border-gray-100">
          <h5 class="font-semibold text-gray-800">{{ document.titre || 'Document' }}</h5>
        </div>
        <div class="h-[400px] resize-y overflow-auto">
          <embed :src="getDocumentUrl(document)" class="w-full h-full" type="application/pdf" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  documents: {
    type: Array as () => { id: number; titre?: string; url: string; document_url?: string }[],
    required: true
  }
});

// Fonction utilitaire pour formater les URLs des documents
const getDocumentUrl = (document) => {
  if (!document || !document.url) {
    console.error('Document ou URL manquante:', document);
    return '';
  }
  
  // NOUVEAU: Utiliser l'URL S3 temporaire si disponible
  if (document.document_url) {
    console.log('Utilisation URL S3:', document.document_url);
    return document.document_url;
  }
  
  let finalUrl = '';
  
  // Si c'est une URL absolue avec localhost, la corriger
  if (document.url.startsWith('http://localhost/')) {
    // Remplacer http://localhost/ par la base URL actuelle
    const baseUrl = window.location.origin + '/';
    finalUrl = document.url.replace('http://localhost/', baseUrl);
  } 
  // Si c'est une autre URL absolue (externe)
  else if (document.url.startsWith('http')) {
    finalUrl = document.url;
  } 
  // Si c'est un chemin qui contient déjà 'documents/'
  else if (document.url.includes('documents/')) {
    finalUrl = `/storage/${document.url}`;
  } 
  // Sinon c'est juste un nom de fichier
  else {
    finalUrl = `/storage/documents/${document.url}`;
  }
  
  console.log('URL document générée (fallback):', finalUrl);
  
  return finalUrl;
};
</script>