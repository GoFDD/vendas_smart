<template>
  <div v-if="isOpen" class="modal modal-open">
    <div class="modal-box">
      <h3 class="font-bold text-lg">Detalhes da Venda #{{ venda.id }}</h3>
      <button
        @click="closeModal"
        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
      >
        ✕
      </button>

      <!-- Tabela de Produtos -->
      <div class="mt-4">
        <h4 class="font-semibold">Produtos</h4>
        <table class="table w-full mt-2">
          <thead>
            <tr>
              <th>Produto</th>
              <th>Marca</th>
              <th>Quantidade</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in venda.itens_venda" :key="item.id">
              <td>
                {{ item.produto?.nome_produto || 'Produto não encontrado' }}
              </td>
              <td>
                {{ item.produto?.marca?.nome || 'Marca não especificada' }}
              </td>
              <td>{{ item.quantidade }}</td>
              <td>
                R$ {{ item.subtotal ? item.subtotal.toFixed(2) : '0.00' }}
              </td>
              <!-- Adiciona um log para depurar -->
              <td style="display: none">
                {{ console.log('Produto no item:', item.produto) }}
              </td>
            </tr>
            <tr v-if="!venda.itens_venda || venda.itens_venda.length === 0">
              <td colspan="4">Nenhum item encontrado para esta venda.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Informações do Cliente -->
      <div class="mt-4">
        <h4 class="font-semibold">Cliente</h4>
        <p>{{ clienteNome || 'Nenhum' }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalVendas',
  props: {
    venda: {
      type: Object,
      required: true,
    },
    clientes: {
      type: Array,
      required: true,
    },
    isOpen: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    clienteNome() {
      console.log('Venda no modal:', this.venda);
      if (!this.venda.cliente_id) return 'Nenhum';
      const cliente = this.clientes.find((c) => c.id === this.venda.cliente_id);
      return cliente ? cliente.nome : 'Cliente não encontrado';
    },
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
  },
};
</script>
