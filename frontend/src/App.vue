<template>
  <div id="app">
    <!-- Renderiza a navbar apenas se o usuário estiver logado -->
    <AppNavbar v-if="isAuthenticated" @auth-changed="updateAuthState" />
    <router-view @auth-changed="updateAuthState" />
  </div>
</template>

<script>
import AppNavbar from './components/AppNavbar.vue';

export default {
  name: 'App',
  components: {
    AppNavbar,
  },
  data() {
    return {
      authToken: localStorage.getItem('auth_token') || '',
    };
  },
  computed: {
    isAuthenticated() {
      return !!this.authToken;
    },
  },
  watch: {
    $route: function () {
      this.authToken = localStorage.getItem('auth_token') || '';
    },
  },
  mounted() {
    window.addEventListener('storage', this.handleStorageChange);
  },
  beforeDestroy() {
    window.removeEventListener('storage', this.handleStorageChange);
  },
  methods: {
    handleStorageChange(event) {
      if (event.key === 'auth_token') {
        this.authToken = event.newValue || '';
      }
    },
    updateAuthState() {
      this.authToken = localStorage.getItem('auth_token') || '';
    },
  },
};
</script>
