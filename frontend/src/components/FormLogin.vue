<template>
  <form
    @submit.prevent="$emit('submit', formData)"
    class="space-y-6 p-6 bg-gray-800 rounded-lg shadow-xl"
  >
    <!-- #email -->
    <div class="form-control">
      <label for="email-login" class="label">
        <span class="label-text text-white text-sm font-medium">E-mail</span>
      </label>
      <input
        id="email-login"
        type="email"
        v-model="formData.email"
        placeholder="seu.email@exemplo.com"
        class="input input-bordered w-full bg-gray-700 text-white border-gray-600 placeholder-gray-400 focus:border-primary focus:ring-2 focus:ring-primary transition-all duration-300"
        required
        autocomplete="email"
      />
    </div>

    <!-- #senha -->
    <div class="form-control">
      <label for="senha-login" class="label">
        <span class="label-text text-white text-sm font-medium">Senha</span>
      </label>
      <div class="relative">
        <input
          id="senha-login"
          :type="mostrarSenha ? 'text' : 'password'"
          v-model="formData.senha"
          placeholder="Digite sua senha"
          class="input input-bordered w-full bg-gray-700 text-white border-gray-600 placeholder-gray-400 focus:border-primary focus:ring-2 focus:ring-primary transition-all duration-300 pr-10"
          required
          autocomplete="current-password"
        />
        <button
          type="button"
          @click="mostrarSenha = !mostrarSenha"
          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary transition-colors duration-200"
          aria-label="Mostrar/esconder senha"
        >
          <svg
            v-if="!mostrarSenha"
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
            <path d="M3 3l18 18"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- #login -->
    <div class="form-control mt-6">
      <button
        type="submit"
        class="btn w-full bg-black text-white hover:bg-gray-700 focus:ring-4 focus:ring-primary/50 transition-all duration-300"
      >
        Entrar
      </button>
    </div>
  </form>
</template>

<script>
export default {
  name: 'FormLogin',
  props: {
    formData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      mostrarSenha: false,
    };
  },
  emits: ['submit'],
};
</script>
