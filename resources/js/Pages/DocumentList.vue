<template>
    <div>
      <h1>Liste des Documents</h1>
      <ul>
        <li v-for="document in documents" :key="document.id">
            <p>ID: {{ document.id }}</p>
          <a :href="document.url" target="_blank">{{ document.titre }}</a>
          <!-- Bouton de téléchargement -->
          <button @click="downloadDocument(document.url)">Télécharger</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      documents: Array,
    },
    methods: {
      // Fonction pour télécharger le document
      downloadDocument(url) {
        const a = document.createElement('a');
        a.href = url; // L'URL du fichier
        a.download = url.split('/').pop(); // Utiliser le nom du fichier pour le téléchargement
        document.body.appendChild(a);
        a.click(); // Déclencher le téléchargement
        document.body.removeChild(a); // Retirer l'élément <a> après le téléchargement
      }
    }
  };
  </script>
  