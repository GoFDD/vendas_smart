<template>
  <div class="navbar bg-base-300">
    <div class="navbar-start">
      <!-- Menu Hamburger -->
      <div class="dropdown">
        <label tabindex="0" class="btn btn-ghost lg:hidden">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
        </label>
        <ul
          tabindex="0"
          class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52"
        >
          <li><router-link to="/vendas">Vendas</router-link></li>
          <li><router-link to="/clientes">Clientes</router-link></li>
          <li><router-link to="/produtos">Produtos</router-link></li>
        </ul>
      </div>
      <router-link to="/vendas" class="btn btn-ghost normal-case text-xl"
        >Venda Smart</router-link
      >
    </div>

    <!-- #navbar  -->
    <div class="navbar-center hidden lg:flex">
      <ul class="menu menu-horizontal px-1">
        <li><router-link to="/vendas">Vendas</router-link></li>
        <li><router-link to="/clientes">Clientes</router-link></li>
        <li><router-link to="/produtos">Produtos</router-link></li>
        <li><router-link to="/marcas">Marcas</router-link></li>
      </ul>
    </div>

    <!--  End  -->
    <div class="navbar-end">
      <div class="dropdown dropdown-end">
        <label tabindex="0" class="btn btn-ghost btn-circle avatar">
          <div class="w-10 rounded-full">
            <img
              src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
              alt="User Avatar"
            />
          </div>
        </label>
        <ul
          tabindex="0"
          class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52"
        >
          <li>
            <button @click="logout">Logout</button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AppNavbar',
  data() {
    return {
      apiUrl: 'http://localhost:8000/api',
      token: localStorage.getItem('auth_token') || '',
    };
  },
  methods: {
    async logout() {
      try {
        await axios.post(
          `${this.apiUrl}/logout`,
          {},
          {
            headers: { Authorization: `Bearer ${this.token}` },
          }
        );
        localStorage.removeItem('auth_token');
        this.$router.push({ name: 'Login' });
      } catch (error) {
        console.error('Erro ao fazer logout:', error);
        // logout forcado
        localStorage.removeItem('auth_token');
        this.$router.push({ name: 'Login' });
      }
    },
  },
};
</script>
