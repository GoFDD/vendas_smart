<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-900">
    <div
      class="card w-full max-w-md bg-base-200 text-base-content shadow-lg p-6"
    >
      <h1 class="text-4xl font-bold text-center mb-4">Registre-se agora!</h1>
      <p class="text-center mb-6">
        Junte-se a nós e tenha acesso a recursos exclusivos. Crie sua conta em
        apenas alguns passos simples.
      </p>
      <h2 class="text-2xl font-bold text-center mb-6">Criar Conta</h2>
      <FormRegister :form-data="form" @submit="enviarCadastro" />
      <div class="text-center mt-4">
        <p class="text-sm">
          Já tem uma conta?
          <router-link to="/login" class="link link-primary"
            >Entrar</router-link
          >
        </p>
      </div>
      <dialog ref="successModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg text-success">Cadastro Bem-Sucedido!</h3>
          <p class="py-4">
            Sua conta foi criada com sucesso. Você será redirecionado para a
            página de login.
          </p>
          <div class="modal-action">
            <button
              class="btn btn-primary"
              @click="closeSuccessModalAndRedirect"
            >
              OK
            </button>
          </div>
        </div>
      </dialog>
      <dialog ref="errorModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg text-error">Erro ao Cadastrar</h3>
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
import FormRegister from '../components/FormRegister.vue';

export default {
  components: { FormRegister },
  data() {
    return {
      form: { nome: '', email: '', senha: '' },
      errorMessage: '',
    };
  },
  methods: {
    enviarCadastro(formData) {
      api
        .post('/register', formData)
        .then(() => {
          this.form.nome = '';
          this.form.email = '';
          this.form.senha = '';
          this.$refs.successModal.showModal();
        })
        .catch((error) => {
          this.errorMessage =
            error.response?.data?.message ||
            'Erro ao cadastrar. Tente novamente.';
          this.$refs.errorModal.showModal();
        });
    },
    closeSuccessModalAndRedirect() {
      this.$refs.successModal.close();
      this.$router.push('/login'); // redireciona para o login
    },
    closeErrorModal() {
      this.$refs.errorModal.close();
    },
  },
};
</script>
