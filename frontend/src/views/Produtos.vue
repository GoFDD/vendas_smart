<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-800 py-10">
    <div
      class="card w-full max-w-4xl bg-base-200 text-base-content shadow-lg p-6"
    >
      <h1 class="text-4xl font-bold text-center mb-6">Produtos</h1>
      <p class="text-center mb-6">Gerencie os produtos registrados.</p>
      <div class="flex justify-between mb-4">
        <input
          type="text"
          placeholder="Pesquisar produtos..."
          class="input input-bordered w-full max-w-xs bg-base-100"
          v-model="search"
          @input="filterProdutos"
        />
        <button class="btn btn-primary" @click="openAddModal">
          Adicionar Produto
        </button>
      </div>
      <div>
        <table class="table w-full">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Marca</th>
              <th>Preço</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="produto in filteredProdutos" :key="produto.id">
              <td>{{ produto.id }}</td>
              <td>{{ produto.nome_produto }}</td>
              <td>{{ getMarcaNome(produto.marca_id) }}</td>
              <td>R$ {{ produto.preco }}</td>
              <td class="flex space-x-2">
                <button class="btn btn-sm" @click="openViewModal(produto)">
                  <EyeIcon class="h-5 w-5" />
                </button>
                <button
                  class="btn btn-error btn-sm"
                  @click="openDeleteModal(produto.id)"
                >
                  <TrashIcon class="h-5 w-5" />
                </button>
              </td>
            </tr>
            <tr v-if="filteredProdutos.length === 0">
              <td colspan="5" class="text-center">
                Nenhum produto encontrado.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Modal de Adicionar Produto -->
      <dialog ref="addModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Adicionar Produto</h3>
          <form @submit.prevent="createProduto" class="space-y-4 mt-4">
            <input
              type="text"
              placeholder="Nome do Produto"
              class="input input-bordered w-full"
              v-model="newProduto.nome_produto"
              required
            />
            <select
              class="select select-bordered w-full"
              v-model.number="newProduto.marca_id"
              required
            >
              <option disabled value="">Selecione a Marca</option>
              <option v-for="marca in marcas" :key="marca.id" :value="marca.id">
                {{ marca.nome }}
              </option>
            </select>
            <input
              type="number"
              placeholder="Preço"
              class="input input-bordered w-full"
              v-model.number="newProduto.preco"
              step="0.01"
              min="0"
              required
            />
            <textarea
              placeholder="Descrição do Produto"
              class="textarea textarea-bordered w-full"
              v-model="newProduto.descricao"
              required
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
      <!-- Modal de Visualizar/Editar Produto -->
      <dialog ref="viewModal" class="modal">
        <div class="modal-box relative">
          <button
            class="btn btn-sm btn-circle absolute right-2 top-2"
            @click="closeViewModal"
          >
            ✕
          </button>
          <h3 class="font-bold text-lg">Detalhes do Produto</h3>
          <div v-if="viewProduto" class="space-y-4 mt-4">
            <div>
              <label class="font-semibold">ID:</label>
              <p>{{ viewProduto.id }}</p>
            </div>
            <div>
              <label class="font-semibold">Nome:</label>
              <input
                type="text"
                class="input input-bordered w-full"
                v-model="viewProduto.nome_produto"
                :disabled="!isEditing"
                required
              />
            </div>
            <div>
              <label class="font-semibold">Marca:</label>
              <select
                class="select select-bordered w-full"
                v-model.number="viewProduto.marca_id"
                :disabled="!isEditing"
                required
              >
                <option disabled value="">Selecione a Marca</option>
                <option
                  v-for="marca in marcas"
                  :key="marca.id"
                  :value="marca.id"
                >
                  {{ marca.nome }}
                </option>
              </select>
            </div>
            <div>
              <label class="font-semibold">Preço:</label>
              <input
                type="number"
                class="input input-bordered w-full"
                v-model.number="viewProduto.preco"
                :disabled="!isEditing"
                step="0.01"
                min="0"
                required
              />
            </div>
            <div>
              <label class="font-semibold">Descrição:</label>
              <textarea
                class="textarea textarea-bordered w-full"
                v-model="viewProduto.descricao"
                :disabled="!isEditing"
                required
              ></textarea>
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
                @click="updateProduto"
              >
                Salvar
              </button>
              <button class="btn" @click="closeViewModal">Fechar</button>
            </div>
          </div>
          <div v-else class="space-y-4 mt-4">
            <p class="text-error">Erro: Nenhum produto selecionado.</p>
            <div class="modal-action">
              <button class="btn" @click="closeViewModal">Fechar</button>
            </div>
          </div>
        </div>
      </dialog>
      <!-- Modal de Confirmação de Exclusão -->
      <dialog ref="deleteModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Confirmar Exclusão</h3>
          <p class="py-4">Tem certeza de que deseja excluir este produto?</p>
          <div class="modal-action">
            <button class="btn btn-error" @click="deleteProduto">Sim</button>
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
  fetchProdutos,
  createProduto,
  updateProduto,
  deleteProduto,
  fetchMarcas,
} from '../services/api';

export default {
  name: 'Produtos',
  components: {
    EyeIcon,
    TrashIcon,
  },
  data() {
    return {
      produtos: [],
      marcas: [],
      search: '',
      newProduto: {
        nome_produto: '',
        marca_id: '',
        preco: null,
        descricao: '',
      },
      viewProduto: null,
      isEditing: false,
      deleteId: null,
    };
  },
  computed: {
    filteredProdutos() {
      return this.produtos.filter((produto) =>
        produto.nome_produto.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },
  mounted() {
    this.fetchProdutos();
    this.fetchMarcas();
  },
  methods: {
    async fetchProdutos() {
      try {
        this.produtos = await fetchProdutos();
        console.log('Produtos carregados:', this.produtos);
      } catch (error) {
        console.error('Erro ao carregar produtos:', error);
      }
    },
    async fetchMarcas() {
      try {
        this.marcas = await fetchMarcas();
        console.log('Marcas carregadas:', this.marcas);
      } catch (error) {
        console.error('Erro ao carregar marcas:', error);
      }
    },
    getMarcaNome(marcaId) {
      const marca = this.marcas.find((m) => m.id === marcaId);
      return marca ? marca.nome : 'Sem marca';
    },
    openAddModal() {
      this.newProduto = {
        nome_produto: '',
        marca_id: '',
        preco: null,
        descricao: '',
      };
      this.$refs.addModal.showModal();
    },
    closeAddModal() {
      this.$refs.addModal.close();
    },
    async createProduto() {
      try {
        const newProduto = await createProduto(this.newProduto);
        this.produtos.push(newProduto);
        this.closeAddModal();
      } catch (error) {
        console.error('Erro ao criar produto:', error);
      }
    },
    openViewModal(produto) {
      this.viewProduto = { ...produto };
      this.isEditing = false;
      this.$refs.viewModal.showModal();
    },
    closeViewModal() {
      this.$refs.viewModal.close();
      this.viewProduto = null;
      this.isEditing = false;
    },
    async updateProduto() {
      try {
        const updatedProduto = await updateProduto(
          this.viewProduto.id,
          this.viewProduto
        );
        const index = this.produtos.findIndex(
          (p) => p.id === this.viewProduto.id
        );
        if (index !== -1) this.produtos[index] = updatedProduto;
        this.isEditing = false;
      } catch (error) {
        console.error('Erro ao atualizar produto:', error);
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
    async deleteProduto() {
      try {
        await deleteProduto(this.deleteId);
        this.produtos = this.produtos.filter((p) => p.id !== this.deleteId);
        this.closeDeleteModal();
      } catch (error) {
        console.error('Erro ao excluir produto:', error);
      }
    },
    filterProdutos() {},
  },
};
</script>
