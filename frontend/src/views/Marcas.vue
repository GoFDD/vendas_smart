<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-800 py-10">
    <div
      class="card w-full max-w-4xl bg-base-200 text-base-content shadow-lg p-6"
    >
      <h1 class="text-4xl font-bold text-center mb-6">Marcas</h1>
      <p class="text-center mb-6">Gerencie as marcas registradas.</p>
      <div class="flex justify-between mb-4">
        <input
          type="text"
          placeholder="Pesquisar marcas..."
          class="input input-bordered w-full max-w-xs bg-base-100"
          v-model="search"
          @input="filterMarcas"
        />
        <button class="btn btn-primary" @click="openAddModal">
          Adicionar Marca
        </button>
      </div>
      <div class="overflow-x-auto">
        <table class="table w-full">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="marca in filteredMarcas" :key="marca.id">
              <td>{{ marca.id }}</td>
              <td>{{ marca.nome }}</td>
              <td>
                <button
                  class="btn btn-soft btn-sm mr-2"
                  @click="openEditModal(marca)"
                >
                  <PencilIcon class="h-5 w-5" />
                </button>
                <button
                  class="btn btn-soft btn-sm"
                  @click="openDeleteModal(marca.id)"
                >
                  <TrashIcon class="h-5 w-5" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <dialog ref="addModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Adicionar Marca</h3>
          <form @submit.prevent="createMarca" class="space-y-4 mt-4">
            <input
              type="text"
              placeholder="Nome da Marca"
              class="input input-bordered w-full"
              v-model="newMarca.nome"
              required
            />
            <div class="modal-action">
              <button type="submit" class="btn btn-primary">Salvar</button>
              <button type="button" class="btn" @click="closeAddModal">
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </dialog>
      <dialog ref="editModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Editar Marca</h3>
          <form @submit.prevent="updateMarca" class="space-y-4 mt-4">
            <input
              type="text"
              placeholder="Nome da Marca"
              class="input input-bordered w-full"
              v-model="editMarca.nome"
              required
            />
            <div class="modal-action">
              <button type="submit" class="btn btn-primary">Salvar</button>
              <button type="button" class="btn" @click="closeEditModal">
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </dialog>
      <dialog ref="deleteModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Confirmar Exclusão</h3>
          <p class="py-4">Tem certeza de que deseja excluir esta marca?</p>
          <div class="modal-action">
            <button class="btn btn-error" @click="deleteMarca">Sim</button>
            <button class="btn" @click="closeDeleteModal">Não</button>
          </div>
        </div>
      </dialog>
    </div>
  </div>
</template>

<script>
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';

export default {
  data() {
    return {
      marcas: [],
      search: '',
      newMarca: { nome: '' },
      editMarca: { id: null, nome: '' },
      deleteId: null,
      apiUrl: 'http://localhost:8000/api',
      token: localStorage.getItem('auth_token') || '',
    };
  },
  components: {
    PencilIcon,
    TrashIcon,
  },
  computed: {
    filteredMarcas() {
      return this.marcas.filter((marca) =>
        marca.nome.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },
  mounted() {
    this.fetchMarcas();
  },
  methods: {
    async fetchMarcas() {
      try {
        const response = await axios.get(`${this.apiUrl}/marcas`, {
          headers: { Authorization: `Bearer ${this.token}` },
        });
        this.marcas = response.data;
      } catch (error) {
        console.error('Erro ao carregar marcas:', error);
      }
    },
    openAddModal() {
      this.newMarca = { nome: '' };
      this.$refs.addModal.showModal();
    },
    closeAddModal() {
      this.$refs.addModal.close();
    },
    async createMarca() {
      try {
        const response = await axios.post(
          `${this.apiUrl}/marcas`,
          this.newMarca,
          {
            headers: { Authorization: `Bearer ${this.token}` },
          }
        );
        this.marcas.push(response.data);
        this.closeAddModal();
      } catch (error) {
        console.error('Erro ao criar marca:', error);
      }
    },
    openEditModal(marca) {
      this.editMarca = { ...marca };
      this.$refs.editModal.showModal();
    },
    closeEditModal() {
      this.$refs.editModal.close();
    },
    async updateMarca() {
      try {
        const response = await axios.put(
          `${this.apiUrl}/marcas/${this.editMarca.id}`,
          this.editMarca,
          {
            headers: { Authorization: `Bearer ${this.token}` },
          }
        );
        const index = this.marcas.findIndex((m) => m.id === this.editMarca.id);
        if (index !== -1) this.marcas[index] = response.data;
        this.closeEditModal();
      } catch (error) {
        console.error('Erro ao atualizar marca:', error);
      }
    },
    openDeleteModal(id) {
      this.deleteId = id;
      this.$refs.deleteModal.showModal();
    },
    closeDeleteModal() {
      this.$refs.deleteModal.close();
      this.deleteId = null;
    },
    async deleteMarca() {
      try {
        await axios.delete(`${this.apiUrl}/marcas/${this.deleteId}`, {
          headers: { Authorization: `Bearer ${this.token}` },
        });
        this.marcas = this.marcas.filter((m) => m.id !== this.deleteId);
        this.closeDeleteModal();
      } catch (error) {
        console.error('Erro ao excluir marca:', error);
      }
    },
    filterMarcas() {},
  },
};
</script>
