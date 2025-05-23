import { createVenda } from './api';

// Adiciona um item à lista de itens da venda
export function addItem(state, produtos) {
  const { selectedProduto, quantidade, selectedCliente, itensVenda } = state;
  const produto = produtos.find((p) => p.id === selectedProduto);
  if (!produto || quantidade <= 0) return false;

  const newClienteId = selectedCliente || null;

  // não permite clientes diferentes na mesma venda
  if (itensVenda.length > 0) {
    const existingClienteId = itensVenda[0].clienteId;
    if (existingClienteId !== newClienteId) {
      alert(
        'Não é possível adicionar itens com clientes diferentes. Finalize a venda atual ou remova os itens existentes.'
      );
      return false;
    }
  }

  const subtotal = produto.preco * quantidade;
  itensVenda.push({
    produtoId: selectedProduto,
    quantidade,
    subtotal,
    clienteId: newClienteId,
  });

  // Reseta os campos
  state.selectedProduto = '';
  state.quantidade = 1;
  return true;
}

// Remove um item da lista de itens da venda
export function removeItem(state, index) {
  state.itensVenda.splice(index, 1);
}

// Finaliza a venda e envia ao backend
export async function finalizeVenda(state, vendasData) {
  if (state.itensVenda.length === 0) return false;

  try {
    const clienteIds = state.itensVenda.map((item) => item.clienteId);
    const uniqueClientIds = [
      ...new Set(clienteIds.filter((id) => id !== null)),
    ];

    // Validação onde todos os itens devem ter o mesmo cliente (ou nenhum)
    if (
      uniqueClientIds.length > 1 ||
      (uniqueClientIds.length === 1 && clienteIds.includes(null))
    ) {
      throw new Error(
        'Não é possível finalizar a venda. Todos os itens devem estar associados ao mesmo cliente ou nenhum cliente.'
      );
    }

    const clienteId = uniqueClientIds.length > 0 ? uniqueClientIds[0] : null;

    const vendaData = {
      clienteId,
      itens: state.itensVenda.map((item) => ({
        produtoId: item.produtoId,
        quantidade: item.quantidade,
      })),
    };

    const newVenda = await createVenda(vendaData);

    // Adiciona a nova venda ao histórico
    vendasData.data.unshift({
      id: newVenda.id,
      cliente_id: clienteId,
      total:
        newVenda.total ??
        state.itensVenda.reduce((total, item) => total + item.subtotal, 0),
      created_at: newVenda.created_at ?? new Date().toISOString().split('T')[0],
    });

    // Reseta os itens e cliente
    state.itensVenda = [];
    state.selectedCliente = '';
    return true;
  } catch (error) {
    console.error('Erro ao finalizar venda:', error);
    alert(
      error.message ||
        'Erro ao finalizar a venda. Verifique os dados e tente novamente.'
    );
    return false;
  }
}
