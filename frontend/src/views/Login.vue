<template>
  <div class="min-h-screen flex items-center justify-center bg-base-200">
    <div
      class="card w-full max-w-md bg-base-300 text-base-content shadow-lg p-6"
    >
      <h1 class="text-4xl font-bold text-center mb-4">Faça login agora!</h1>
      <p class="text-center mb-6">
        Acesse sua conta e gerencie seus dados de forma rápida e segura.
      </p>
      <h2 class="text-2xl font-bold text-center mb-6">Entrar</h2>
      <FormLogin :form-data="form" @submit="enviarLogin" />
      <div class="text-center mt-4">
        <p class="text-sm">
          Não tem conta?
          <router-link to="/register" class="link link-primary"
            >Cadastre-se</router-link
          >
        </p>
      </div>
      <dialog ref="successModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg text-success">Login Bem-Sucedido!</h3>
          <p class="py-4">Você foi logado com sucesso!</p>
          <div class="modal-action">
            <button class="btn btn-primary" @click="closeSuccessModal">
              OK
            </button>
          </div>
        </div>
      </dialog>
      <dialog ref="errorModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg text-error">Erro ao Logar</h3>
          <p class="py-4">{{ errorMessage }}</p>
          <div class="modal-action">
            <button class="btn btn-error" @click="closeErrorModal">
              Fechar
            </button>
          </div>
        </div>
      </dialog>
    </div>
  </div>
</template>

<script>
import api from '../services/api';
import FormLogin from '../components/FormLogin.vue';

export default {
  components: { FormLogin },
  data() {
    return {
      form: { email: '', senha: '' },
      errorMessage: '',
    };
  },
  methods: {
    enviarLogin(formData) {
      api
        .post('/login', formData)
        .then((response) => {
          if (response.data.token) {
            localStorage.setItem('auth_token', response.data.token);
            this.form.email = '';
            this.form.senha = '';
            this.$refs.successModal.showModal();
          }
        })
        .catch((error) => {
          this.errorMessage =
            error.response?.data?.message || 'Erro ao logar. Tente novamente.';
          this.$refs.errorModal.showModal();
        });
    },
    closeSuccessModal() {
      this.$refs.successModal.close();
      this.$router.push('/vendas');
    },
    closeErrorModal() {
      this.$refs.errorModal.close();
    },
  },
};
</script>
