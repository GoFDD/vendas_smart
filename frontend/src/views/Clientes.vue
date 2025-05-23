<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-800 py-10">
    <div
      class="card w-full max-w-4xl bg-base-200 text-base-content shadow-lg p-6"
    >
      <h1 class="text-4xl font-bold text-center mb-6">Clientes</h1>
      <p class="text-center mb-6">Gerencie os clientes registrados.</p>
      <div class="flex justify-between mb-4">
        <input
          type="text"
          placeholder="Pesquisar clientes..."
          class="input input-bordered w-full max-w-xs bg-base-100"
          v-model="search"
          @input="filterClientes"
        />
        <button class="btn btn-primary" @click="openAddModal">
          Adicionar Cliente
        </button>
      </div>
      <div>
        <table class="table w-full">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Email</th>
              <th>CPF/CNPJ</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cliente in filteredClientes" :key="cliente.id">
              <td>{{ cliente.id }}</td>
              <td>{{ cliente.nome }}</td>
              <td>{{ cliente.email }}</td>
              <td>{{ cliente.cpf_cnpj }}</td>
              <td class="flex space-x-2">
                <button class="btn btn-sm" @click="openViewModal(cliente)">
                  <EyeIcon class="h-5 w-5" />
                </button>
                <button
                  class="btn btn-error btn-sm"
                  @click="openDeleteModal(cliente.id)"
                >
                  <TrashIcon class="h-5 w-5" />
                </button>
              </td>
            </tr>
            <tr v-if="filteredClientes.length === 0">
              <td colspan="5" class="text-center">
                Nenhum cliente encontrado.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Modal de Adicionar Cliente -->
      <dialog ref="addModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Adicionar Cliente</h3>
          <form @submit.prevent="createCliente" class="space-y-4 mt-4">
            <input
              type="text"
              placeholder="Nome"
              class="input input-bordered w-full"
              v-model="newCliente.nome"
              required
            />
            <input
              type="email"
              placeholder="Email"
              class="input input-bordered w-full"
              v-model="newCliente.email"
              required
            />
            <input
              type="text"
              placeholder="Telefone"
              class="input input-bordered w-full"
              v-model="newCliente.telefone"
              v-mask="'(##) #####-####'"
            />
            <input
              type="text"
              placeholder="CPF/CNPJ"
              class="input input-bordered w-full"
              v-model="newCliente.cpf_cnpj"
              v-mask="'###.###.###-##'"
              title="Apenas números"
            />
            <select
              class="select select-bordered w-full"
              v-model="newCliente.tipo_cliente"
              required
            >
              <option disabled value="">Selecione o Tipo</option>
              <option value="pessoa_fisica">Pessoa Física</option>
              <option value="empresa">Empresa</option>
            </select>
            <textarea
              placeholder="Endereço"
              class="textarea textarea-bordered w-full"
              v-model="newCliente.endereco"
            ></textarea>
            <div class="modal-action">
              <button type="submit" class="btn btn-primary">Salvar</button>
              <button type="button" class="btn" @click="closeAddModal">
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </dialog>
      <!-- Modal de Visualizar/Editar Cliente -->
      <dialog ref="viewModal" class="modal">
        <div class="modal-box relative">
          <button
            class="btn btn-sm btn-circle absolute right-2 top-2"
            @click="closeViewModal"
          >
            ✕
          </button>
          <h3 class="font-bold text-lg">Detalhes do Cliente</h3>
          <div v-if="viewCliente" class="space-y-4 mt-4">
            <div>
              <label class="font-semibold">ID:</label>
              <p>{{ viewCliente.id }}</p>
            </div>
            <div>
              <label class="font-semibold">Nome:</label>
              <input
                type="text"
                class="input input-bordered w-full"
                v-model="viewCliente.nome"
                :disabled="!isEditing"
                required
              />
            </div>
            <div>
              <label class="font-semibold">Email:</label>
              <input
                type="email"
                class="input input-bordered w-full"
                v-model="viewCliente.email"
                :disabled="!isEditing"
                required
              />
            </div>
            <div>
              <label class="font-semibold">Telefone:</label>
              <input
                type="text"
                class="input input-bordered w-full"
                v-model="viewCliente.telefone"
                :disabled="!isEditing"
              />
            </div>
            <div>
              <label class="font-semibold">CPF/CNPJ:</label>
              <input
                type="text"
                class="input input-bordered w-full"
                v-model="viewCliente.cpf_cnpj"
                :disabled="!isEditing"
                required
                pattern="[0-9]+"
                title="Apenas números"
              />
            </div>
            <div>
              <label class="font-semibold">Tipo:</label>
              <select
                class="select select-bordered w-full"
                v-model="viewCliente.tipo_cliente"
                :disabled="!isEditing"
                required
              >
                <option disabled value="">Selecione o Tipo</option>
                <option value="pessoa_fisica">Pessoa Física</option>
                <option value="empresa">Empresa</option>
              </select>
            </div>
            <div>
              <label class="font-semibold">Endereço:</label>
              <textarea
                class="textarea textarea-bordered w-full"
                v-model="viewCliente.endereco"
                :disabled="!isEditing"
              ></textarea>
            </div>
            <div>
              <label class="font-semibold">Data Cadastro:</label>
              <p>{{ formatDate(viewCliente.data_cadastro) }}</p>
            </div>
            <div class="modal-action">
              <button
                v-if="!isEditing"
                class="btn btn-primary"
                @click="isEditing = true"
              >
                Editar
              </button>
              <button
                v-if="isEditing"
                class="btn btn-primary"
                @click="updateCliente"
              >
                Salvar
              </button>
              <button class="btn" @click="closeViewModal">Fechar</button>
            </div>
          </div>
          <div v-else class="space-y-4 mt-4">
            <p class="text-error">Erro: Nenhum cliente selecionado.</p>
            <div class="modal-action">
              <button class="btn" @click="closeViewModal">Fechar</button>
            </div>
          </div>
        </div>
      </dialog>
      <!--  Confirmação de Exclusão -->
      <dialog ref="deleteModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Confirmar Exclusão</h3>
          <p class="py-4">Tem certeza de que deseja excluir este cliente?</p>
          <div class="modal-action">
            <button class="btn btn-error" @click="deleteCliente">Sim</button>
            <button class="btn" @click="closeDeleteModal">Não</button>
          </div>
        </div>
      </dialog>
    </div>
  </div>
</template>

<script>
import { EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
import {
  fetchClientes,
  createCliente,
  updateCliente,
  deleteCliente,
} from '../services/api';
import { formatDate } from '../utils/utils';

export default {
  name: 'Clientes',
  components: {
    EyeIcon,
    TrashIcon,
  },
  data() {
    return {
      clientes: [],
      search: '',
      newCliente: {
        nome: '',
        email: '',
        telefone: '',
        cpf_cnpj: '',
        tipo_cliente: '',
        endereco: '',
      },
      viewCliente: null,
      isEditing: false,
      deleteId: null,
    };
  },
  computed: {
    filteredClientes() {
      return this.clientes.filter(
        (cliente) =>
          cliente.nome.toLowerCase().includes(this.search.toLowerCase()) ||
          cliente.email.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },
  mounted() {
    this.fetchClientes();
  },
  methods: {
    async fetchClientes() {
      try {
        this.clientes = await fetchClientes();
        console.log('Clientes carregados:', this.clientes);
      } catch (error) {
        console.error('Erro ao carregar clientes:', error);
      }
    },
    openAddModal() {
      this.newCliente = {
        nome: '',
        email: '',
        telefone: '',
        cpf_cnpj: '',
        tipo_cliente: '',
        endereco: '',
      };
      this.$refs.addModal.showModal();
    },
    closeAddModal() {
      this.$refs.addModal.close();
    },
    async createCliente() {
      try {
        const newCliente = await createCliente(this.newCliente);
        this.clientes.push(newCliente);
        this.closeAddModal();
      } catch (error) {
        console.error('Erro ao criar cliente:', error);
      }
    },
    openViewModal(cliente) {
      this.viewCliente = { ...cliente };
      this.isEditing = false;
      this.$refs.viewModal.showModal();
    },
    closeViewModal() {
      this.$refs.viewModal.close();
      this.viewCliente = null;
      this.isEditing = false;
    },
    async updateCliente() {
      try {
        const updatedCliente = await updateCliente(
          this.viewCliente.id,
          this.viewCliente
        );
        const index = this.clientes.findIndex(
          (c) => c.id === this.viewCliente.id
        );
        if (index !== -1) this.clientes[index] = updatedCliente;
        this.isEditing = false;
      } catch (error) {
        console.error('Erro ao atualizar cliente:', error);
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
    async deleteCliente() {
      try {
        await deleteCliente(this.deleteId);
        this.clientes = this.clientes.filter((c) => c.id !== this.deleteId);
        this.closeDeleteModal();
      } catch (error) {
        console.error('Erro ao excluir cliente:', error);
      }
    },
    formatDate,
    filterClientes() {},
  },
};
</script>
