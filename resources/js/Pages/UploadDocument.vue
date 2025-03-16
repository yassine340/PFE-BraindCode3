<template>
  <div>
    <h1>Upload Document</h1>
    <form @submit.prevent="uploadDocument">
      <input type="file" ref="file" />
      <button type="submit">Upload</button>
    </form>
    <p v-if="message">{{ message }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: '',
    };
  },
  methods: {
    uploadDocument() {
      const formData = new FormData();
      const file = this.$refs.file.files[0];
      
      // Vérifiez que le fichier existe
      if (!file) {
        this.message = 'Veuillez sélectionner un fichier';
        return;
      }
      
      formData.append('document', file);
      formData.append('titre', 'Fakhri chargui'); // Ajoutez tous les autres paramètres attendus

      this.$inertia.post('/document/store', formData, {
        onFinish: () => {
          this.message = 'Document uploaded successfully!';
        },
      }).catch(() => {
        this.message = 'An error occurred while uploading the document.';
      });
    },
  },
};
</script>


<style scoped>
/* Ajoutez vos styles ici */
form {
  max-width: 500px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

input[type="text"],
input[type="file"] {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:disabled {
  background-color: #ccc;
}

.message {
  margin-top: 20px;
  font-size: 16px;
  color: green;
}
</style>
