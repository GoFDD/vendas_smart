// Formata uma data para o formato brasileiro
export function formatDate(date) {
  return new Date(date).toLocaleDateString('pt-BR');
}

// Retorna o nome de um cliente com base no ID
export function getClienteNome(clientes, id) {
  const cliente = clientes.find((c) => c.id === id);
  return cliente ? cliente.nome : '-';
}

// Retorna o nome de um produto com base no ID
export function getProdutoNome(produtos, id) {
  const produto = produtos.find((p) => p.id === id);
  return produto ? produto.nome_produto : 'Produto não encontrado';
}
