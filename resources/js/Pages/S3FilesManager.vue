<template>
    <div class="s3-files-manager">
      <!-- En-tête -->
      <header class="page-header">
        <h1>Gestionnaire de Fichiers S3</h1>
        <div class="stats-container" v-if="stats">
          <div class="stat-item">
            <span class="stat-value">{{ stats.total_files }}</span>
            <span class="stat-label">Fichiers</span>
          </div>
          <div class="stat-item">
            <span class="stat-value">{{ stats.total_videos }}</span>
            <span class="stat-label">Vidéos</span>
          </div>
          <div class="stat-item">
            <span class="stat-value">{{ stats.total_documents }}</span>
            <span class="stat-label">Documents</span>
          </div>
        </div>
        <div class="actions">
          <button @click="refreshFiles" class="btn refresh">
            <i class="fas fa-sync-alt"></i> Rafraîchir
          </button>
        </div>
      </header>
  
      <!-- Loader -->
      <div v-if="loading" class="loader-container">
        <div class="loader"></div>
        <p>Chargement des fichiers...</p>
      </div>
  
      <!-- Message d'erreur -->
      <div v-if="error" class="error-message">
        <i class="fas fa-exclamation-triangle"></i>
        <p>{{ error }}</p>
        <button @click="refreshFiles" class="btn">Réessayer</button>
      </div>
  
      <!-- Contenu principal -->
      <div v-if="!loading && !error" class="content-container">
        <!-- Section vidéos -->
        <section class="file-section">
          <h2>
            <i class="fas fa-video"></i> Vidéos 
            <span class="count">({{ videos.length }})</span>
          </h2>
          <div class="file-grid" v-if="videos.length > 0">
            <div v-for="file in videos" :key="file.path" class="file-card">
              <div class="file-preview video-preview">
                <i class="fas fa-file-video"></i>
              </div>
              <div class="file-info">
                <h3 class="file-name" :title="file.filename">{{ truncateFilename(file.filename, 20) }}</h3>
                <div class="file-meta">
                  <span class="file-size">{{ file.size }}</span>
                  <span class="file-date" :title="file.last_modified">{{ file.last_modified_human }}</span>
                </div>
                <div class="file-actions">
                  <a :href="file.url" target="_blank" class="btn btn-view">
                    <i class="fas fa-eye"></i> Voir
                  </a>
                  <a :href="file.url" class="btn btn-download" download>
                    <i class="fas fa-download"></i> Télécharger
                  </a>
                </div>
              </div>
            </div>
          </div>
          <p v-else class="no-files">Aucune vidéo disponible</p>
        </section>
  
        <!-- Section documents -->
        <section class="file-section">
          <h2>
            <i class="fas fa-file-alt"></i> Documents 
            <span class="count">({{ documents.length }})</span>
          </h2>
          <div class="file-grid" v-if="documents.length > 0">
            <div v-for="file in documents" :key="file.path" class="file-card">
              <div class="file-preview document-preview">
                <i class="fas fa-file-pdf" v-if="file.filename.endsWith('.pdf')"></i>
                <i class="fas fa-file-word" v-else-if="file.filename.endsWith('.doc') || file.filename.endsWith('.docx')"></i>
                <i class="fas fa-file-excel" v-else-if="file.filename.endsWith('.xls') || file.filename.endsWith('.xlsx')"></i>
                <i class="fas fa-file-alt" v-else></i>
              </div>
              <div class="file-info">
                <h3 class="file-name" :title="file.filename">{{ truncateFilename(file.filename, 20) }}</h3>
                <div class="file-meta">
                  <span class="file-size">{{ file.size }}</span>
                  <span class="file-date" :title="file.last_modified">{{ file.last_modified_human }}</span>
                </div>
                <div class="file-actions">
                  <a :href="file.url" target="_blank" class="btn btn-view">
                    <i class="fas fa-eye"></i> Voir
                  </a>
                  <a :href="file.url" class="btn btn-download" download>
                    <i class="fas fa-download"></i> Télécharger
                  </a>
                </div>
              </div>
            </div>
          </div>
          <p v-else class="no-files">Aucun document disponible</p>
        </section>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        videos: [],
        documents: [],
        stats: null,
        loading: true,
        error: null
      }
    },
    mounted() {
      this.fetchFiles();
    },
    methods: {
      async fetchFiles() {
        this.loading = true;
        this.error = null;
        
        try {
          const response = await fetch('/api/s3-files');
          
          if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
          }
          
          const data = await response.json();
          this.videos = data.videos || [];
          this.documents = data.documents || [];
          this.stats = data.stats;
          
        } catch (error) {
          console.error('Erreur lors de la récupération des fichiers:', error);
          this.error = `Impossible de charger les fichiers: ${error.message}`;
        } finally {
          this.loading = false;
        }
      },
      refreshFiles() {
        this.fetchFiles();
      },
      truncateFilename(filename, maxLength) {
        if (filename.length <= maxLength) return filename;
        
        const extension = filename.split('.').pop();
        const name = filename.substring(0, filename.length - extension.length - 1);
        
        if (name.length <= maxLength - 3) return filename;
        
        return name.substring(0, maxLength - 3) + '...' + (extension ? `.${extension}` : '');
      }
    }
  }
  </script>
  
  <style scoped>
  .s3-files-manager {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
  }
  
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .page-header h1 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.8rem;
  }
  
  .stats-container {
    display: flex;
    gap: 1.5rem;
  }
  
  .stat-item {
    text-align: center;
    background-color: #f8f9fa;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
  }
  
  .stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 600;
    color: #3490dc;
  }
  
  .stat-label {
    font-size: 0.875rem;
    color: #606c76;
  }
  
  .actions {
    display: flex;
    gap: 0.5rem;
  }
  
  .btn {
    background-color: #3490dc;
    border: none;
    border-radius: 4px;
    color: white;
    padding: 0.5rem 1rem;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.2s;
  }
  
  .btn:hover {
    background-color: #2779bd;
  }
  
  .btn.refresh {
    background-color: #38c172;
  }
  
  .btn.refresh:hover {
    background-color: #2d995b;
  }
  
  .loader-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
  }
  
  .loader {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3490dc;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  .error-message {
    background-color: #fff5f5;
    border: 1px solid #feb2b2;
    color: #c53030;
    padding: 1rem;
    border-radius: 4px;
    margin: 2rem 0;
    text-align: center;
  }
  
  .error-message i {
    font-size: 2rem;
    margin-bottom: 1rem;
  }
  
  .content-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }
  
  .file-section h2 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
  }
  
  .count {
    color: #718096;
    font-size: 0.9rem;
    font-weight: normal;
  }
  
  .file-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
  }
  
  .file-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
  }
  
  .file-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }
  
  .file-preview {
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
  }
  
  .video-preview {
    background-color: #ebf8ff;
    color: #3182ce;
  }
  
  .document-preview {
    background-color: #faf5ff;
    color: #805ad5;
  }
  
  .file-info {
    padding: 1rem;
  }
  
  .file-name {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .file-meta {
    display: flex;
    justify-content: space-between;
    color: #718096;
    font-size: 0.85rem;
    margin-bottom: 1rem;
  }
  
  .file-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
  }
  
  .btn-view {
    background-color: #4299e1;
    font-size: 0.8rem;
    padding: 0.4rem 0.75rem;
  }
  
  .btn-download {
    background-color: #48bb78;
    font-size: 0.8rem;
    padding: 0.4rem 0.75rem;
  }
  
  .no-files {
    color: #718096;
    text-align: center;
    padding: 2rem;
    background-color: #f7fafc;
    border-radius: 8px;
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .page-header {
      flex-direction: column;
      align-items: flex-start;
    }
    
    .stats-container {
      width: 100%;
      justify-content: space-between;
    }
    
    .actions {
      width: 100%;
      justify-content: flex-end;
    }
  }
  </style>