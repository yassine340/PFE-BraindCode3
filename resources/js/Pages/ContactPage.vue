<template>
  <Head title="contact - BrainCode Startup Studio" />
  <app-header :canLogin="true" :canRegister="true" />
  <div class="contact-page">
    <div class="contact-container">
      <div class="form-section">
        <h1 class="contact-title">Contactez-nous</h1>
        <p class="contact-subtitle">Nous sommes là pour répondre à vos questions !</p>

        <!-- ✅ Success Alert -->
        <div v-if="showAlert" class="alert-message">
          {{ $page.props.flash.success }}
        </div>

        <form @submit.prevent="submitForm" class="contact-form">
          <div class="form-group">
            <label for="name">Nom</label>
            <input 
              type="text" 
              id="name" 
              v-model="form.name" 
              class="form-input" 
              required
            />
            <div v-if="form.errors.name" class="error-message">{{ form.errors.name }}</div>
          </div>

          <div class="form-group">
            <label for="prenom">Prénom</label>
            <input 
              type="text" 
              id="prenom" 
              v-model="form.firstName" 
              class="form-input" 
              required
            />
            <div v-if="form.errors.firstName" class="error-message">{{ form.errors.firstName }}</div>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input 
              type="email" 
              id="email" 
              v-model="form.email" 
              class="form-input" 
              required
            />
            <div v-if="form.errors.email" class="error-message">{{ form.errors.email }}</div>
          </div>

          <div class="form-group">
            <label for="phone">Numéro de téléphone</label>
            <input 
              type="tel" 
              id="phone" 
              v-model="form.phone" 
              class="form-input"
            />
            <div v-if="form.errors.phone" class="error-message">{{ form.errors.phone }}</div>
          </div>

          <div class="form-group">
            <label for="message">Décrivez votre besoin en quelques lignes...</label>
            <textarea 
              id="message" 
              v-model="form.message" 
              class="form-textarea" 
              rows="4" 
              required
            ></textarea>
            <div v-if="form.errors.message" class="error-message">{{ form.errors.message }}</div>
          </div>

          <button type="submit" class="submit-button" :disabled="form.processing">
            {{ form.processing ? 'Envoi en cours...' : 'Envoyer' }}
          </button>
        </form>
      </div>

      <div class="illustration-section">
        <div class="phone-container">
          <img src="/image/faq/5.png" alt="Support agent" class="support-image" />
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script>
import { Head, useForm, usePage } from "@inertiajs/inertia-vue3";
import { defineAsyncComponent, ref, onMounted } from 'vue';
import Footer from "@/Components/Footer.vue";

const AppHeader = defineAsyncComponent(() => 
  import("@/Components/Welcome/AppHeader.vue")
);

export default {
  name: 'ContactPage',
  components: {
    'app-header': AppHeader,
    Footer,
    Head
  },
  setup() {
    const form = useForm({
      name: '',
      firstName: '',
      email: '',
      phone: '',
      message: ''
    });

    const page = usePage();
    const showAlert = ref(false);

    const submitForm = () => {
      form.post(route('contact.store'), {
        onSuccess: () => {
          form.reset();
        },
      });
    };

    return { form, submitForm, showAlert };
  },
}
</script>

<style scoped>
.contact-page {
  background-color: #f5f7fb;
  min-height: 100vh;
  padding: 40px 0;
}

.contact-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  padding: 0 20px;
  gap: 40px;
}

.form-section {
  flex: 1;
  max-width: 600px;
}

.contact-title {
  color: #0066cc;
  font-size: 28px;
  font-weight: 600;
  margin-bottom: 8px;
}

.contact-subtitle {
  color: #666;
  font-size: 16px;
  margin-bottom: 25px;
}

.contact-form {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-bottom: 25px;
}

.form-group {
  margin-bottom: 16px;
}

label {
  display: block;
  margin-bottom: 6px;
  font-size: 14px;
  color: #555;
}

.form-input, .form-textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
}

.form-textarea {
  resize: vertical;
}

.submit-button {
  background-color: #0088ff;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.submit-button:hover {
  background-color: #0077e6;
}

.illustration-section {
  display: flex;
  align-items: center;
  justify-content: center;
}

.phone-container {
  position: relative;
  max-width: 400px;
}

.support-image {
  width: 120%;
  height: auto;
  object-fit: contain;
  transform: scale(1.2);
  transform-origin: center center;
}

/* ✅ Alert Message */
.alert-message {
  background-color: #dff0d8;
  color: #3c763d;
  padding: 12px 20px;
  border-radius: 6px;
  margin-bottom: 16px;
  border: 1px solid #d6e9c6;
  font-weight: 500;
}

@media (max-width: 992px) {
  .contact-container {
    flex-direction: column;
  }

  .form-section {
    max-width: 100%;
    order: 2;
  }

  .illustration-section {
    order: 1;
    margin-bottom: 30px;
  }
}

@media (max-width: 768px) {
  .contact-page {
    padding: 20px 0;
  }

  .contact-container {
    padding: 0 15px;
  }
}

@media (max-width: 480px) {
  .contact-title {
    font-size: 24px;
  }

  .contact-subtitle {
    font-size: 14px;
  }

  .contact-form {
    padding: 15px;
  }

  .submit-button {
    width: 100%;
  }
}
</style>
