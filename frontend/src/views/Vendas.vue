<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-800 py-10">
    <div
      class="card w-full max-w-4xl bg-base-200 text-base-content shadow-lg p-6"
    >
      <h1 class="text-4xl font-bold text-center mb-6">Vendas</h1>
      <p class="text-center mb-6">Crie e acompanhe as vendas realizadas.</p>
      <!-- #SELECT -->
      <div class="bg-base-100 p-6 rounded-lg mb-6 gap-x-4">
        <h2 class="text-2xl font-bold mb-4">Adicionar Itens à Venda</h2>
        <div class="space-y-4 flex gap-x-4">
          <select
            class="select select-bordered w-full max-w-xs"
            v-model="selectedCliente"
          >
            <option value="">Nenhum cliente</option>
            <option
              v-for="cliente in clientes"
              :key="cliente.id"
              :value="cliente.id"
            >
              {{ cliente.nome }}
            </option>
          </select>
          <select
            class="select select-bordered w-full max-w-xs"
            v-model="selectedProduto"
            required
          >
            <option disabled value="">Selecione um Produto</option>
            <option
              v-for="produto in produtos"
              :key="produto.id"
              :value="produto.id"
            >
              {{ produto.nome_produto }} (R$ {{ produto.preco }})
            </option>
          </select>
          <input
            type="number"
            placeholder="Quantidade"
            class="input input-bordered w-full max-w-xs"
            v-model.number="quantidade"
            min="1"
            required
          />
          <p class="text-lg">Subtotal: R$ {{ subtotal }}</p>
          <button
            class="btn btn-primary"
            @click="addItem"
            :disabled="!selectedProduto || !quantidade"
          >
            Adicionar Item
          </button>
        </div>
        <!-- DATATABLE #tabela-->
        <div class="overflow-x-auto mt-6">
          <table class="table w-full" v-if="itensVenda.length > 0">
            <thead>
              <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th>Cliente</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in itensVenda" :key="index">
                <td>{{ getProdutoNome(produtos, item.produtoId) }}</td>
                <td>{{ item.quantidade }}</td>
                <td>R$ {{ item.subtotal }}</td>
                <td>
                  {{
                    item.clienteId
                      ? getClienteNome(clientes, item.clienteId)
                      : 'Nenhum'
                  }}
                </td>
                <td>
                  <button
                    class="btn btn-error btn-sm"
                    @click="removeItem(index)"
                  >
                    Remover
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <p v-if="itensVenda.length === 0" class="text-center">
            Nenhum item adicionado.
          </p>
        </div>
        <!-- funcionalidades de botão/ Total -->
        <div class="text-right mt-6">
          <p class="text-xl font-bold">Total: R$ {{ totalVenda }}</p>
          <button
            class="btn btn-success"
            @click="finalizeVenda"
            :disabled="itensVenda.length === 0"
          >
            Finalizar Venda
          </button>
        </div>
      </div>
      <!-- Detalhes de Vendas -->
      <div class="bg-base-100 p-6 rounded-lg">
        <h2 class="text-2xl font-bold mb-4">Histórico de Vendas</h2>
        <div class="overflow-x-auto">
          <table class="table w-full">
            <thead>
              <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Data</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="venda in vendas.data" :key="venda.id">
                <td>{{ venda.id }}</td>
                <td>
                  {{
                    venda.cliente_id
                      ? getClienteNome(clientes, venda.cliente_id)
                      : 'Nenhum'
                  }}
                </td>
                <td>R$ {{ venda.total }}</td>
                <td>{{ formatDate(venda.created_at) }}</td>
                <td>
                  <button @click="openModal(venda)" class="btn btn-soft">
                    <EyeIcon class="h-5 w-5" />
                  </button>
                </td>
              </tr>
              <tr v-if="vendas.data.length === 0">
                <td colspan="5">Nenhum registro encontrado.</td>
              </tr>
            </tbody>
          </table>
          <!--  Paginação -->
          <div class="flex justify-between items-center mt-4">
            <button
              class="btn btn-outline"
              :disabled="vendas.current_page === 1"
              @click="changePage(vendas.current_page - 1)"
            >
              Anterior
            </button>
            <span
              >Página {{ vendas.current_page }} de {{ vendas.last_page }}</span
            >
            <button
              class="btn btn-outline"
              :disabled="vendas.current_page === vendas.last_page"
              @click="changePage(vendas.current_page + 1)"
            >
              Próximo
            </button>
          </div>
        </div>
      </div>
      <!-- Detalhes -->
      <ModalVendas
        v-if="showModal"
        :venda="selectedVenda"
        :clientes="clientes"
        :is-open="showModal"
        @close="showModal = false"
      />
    </div>
  </div>
</template>

<script>
import { EyeIcon } from '@heroicons/vue/24/outline';
import ModalVendas from '../components/ModalVendas.vue';
import { fetchClientes, fetchProdutos, fetchVendas } from '../services/api';
import { formatDate, getClienteNome, getProdutoNome } from '../utils/utils';
import { addItem, removeItem, finalizeVenda } from '../services/vendasService';

export default {
  name: 'Vendas',
  components: {
    EyeIcon,
    ModalVendas,
  },
  data() {
    return {
      clientes: [],
      produtos: [],
      vendas: { data: [], current_page: 1, last_page: 1, per_page: 10 },
      selectedCliente: '',
      selectedProduto: '',
      quantidade: 1,
      itensVenda: [],
      showModal: false,
      selectedVenda: null,
    };
  },
  computed: {
    subtotal() {
      const produto = this.produtos.find((p) => p.id === this.selectedProduto);
      return produto ? (produto.preco * this.quantidade).toFixed(2) : 0;
    },
    totalVenda() {
      return this.itensVenda
        .reduce((total, item) => total + item.subtotal, 0)
        .toFixed(2);
    },
  },
  mounted() {
    this.fetchClientes();
    this.fetchProdutos();
    this.fetchVendas();
  },
  methods: {
    // Buscar itens no back-end
    async fetchClientes() {
      try {
        this.clientes = await fetchClientes();
        console.log('Clientes carregados:', this.clientes);
      } catch (error) {
        console.error('Erro ao carregar clientes:', error);
      }
    },
    async fetchProdutos() {
      try {
        this.produtos = await fetchProdutos();
        console.log('Produtos carregados:', this.produtos);
      } catch (error) {
        console.error('Erro ao carregar produtos:', error);
      }
    },
    async fetchVendas(page = 1) {
      try {
        this.vendas = await fetchVendas(page, this.vendas.per_page);
        console.log('Vendas carregadas:', this.vendas);
      } catch (error) {
        console.error('Erro ao carregar vendas:', error);
      }
    },
    addItem() {
      const state = {
        selectedProduto: this.selectedProduto,
        quantidade: this.quantidade,
        selectedCliente: this.selectedCliente,
        itensVenda: this.itensVenda,
      };
      addItem(state, this.produtos);
      this.selectedProduto = state.selectedProduto;
      this.quantidade = state.quantidade;
    },
    removeItem(index) {
      removeItem({ itensVenda: this.itensVenda }, index);
    },
    async finalizeVenda() {
      const state = {
        itensVenda: this.itensVenda,
        selectedCliente: this.selectedCliente,
      };
      const success = await finalizeVenda(state, this.vendas);
      if (success) {
        this.itensVenda = state.itensVenda;
        this.selectedCliente = state.selectedCliente;
        await this.fetchVendas();
      }
    },
    openModal(venda) {
      this.selectedVenda = venda;
      this.showModal = true;
      console.log('Abrindo modal para venda:', venda);
    },
    changePage(page) {
      if (page >= 1 && page <= this.vendas.last_page) {
        this.fetchVendas(page);
      }
    },
    formatDate,
    getClienteNome(clientes, id) {
      return getClienteNome(this.clientes, id);
    },
    getProdutoNome(produtos, id) {
      return getProdutoNome(this.produtos, id);
    },
  },
};
</script>
