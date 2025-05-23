import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
});

// Adiciona o token ao header de todas as requisições
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Busca produtos
export async function fetchProdutos() {
  try {
    const response = await api.get('/produtos');
    return response.data;
  } catch (error) {
    console.error('Erro ao carregar produtos:', error);
    throw error;
  }
}

export async function createProduto(data) {
  try {
    const response = await api.post('/produtos', data);
    return response.data;
  } catch (error) {
    console.error('Erro ao criar produto:', error);
    throw error;
  }
}

export async function updateProduto(id, data) {
  try {
    const response = await api.put(`/produtos/${id}`, data);
    return response.data;
  } catch (error) {
    console.error('Erro ao atualizar produto:', error);
    throw error;
  }
}

export async function deleteProduto(id) {
  try {
    await api.delete(`/produtos/${id}`);
  } catch (error) {
    console.error('Erro ao excluir produto:', error);
    throw error;
  }
}

// Busca vendas com paginação
export async function fetchVendas(page = 1, perPage = 10) {
  try {
    const response = await api.get('/vendas', {
      params: {
        page,
        per_page: perPage,
        order_by: 'created_at',
        order_direction: 'desc',
      },
    });
    return response.data;
  } catch (error) {
    console.error('Erro ao carregar vendas:', error);
    throw error;
  }
}

// Finaliza uma venda
export async function createVenda(data) {
  try {
    const response = await api.post('/vendas', data);
    return response.data;
  } catch (error) {
    console.error('Erro ao finalizar venda:', error);
    throw error;
  }
}

// Funções para Clientes
export async function fetchClientes() {
  try {
    const response = await api.get('/clientes');
    return response.data;
  } catch (error) {
    console.error('Erro ao carregar clientes:', error);
    throw error;
  }
}

export async function createCliente(data) {
  try {
    const response = await api.post('/clientes', data);
    return response.data;
  } catch (error) {
    console.error('Erro ao criar cliente:', error);
    throw error;
  }
}

export async function updateCliente(id, data) {
  try {
    const response = await api.put(`/clientes/${id}`, data);
    return response.data;
  } catch (error) {
    console.error('Erro ao atualizar cliente:', error);
    throw error;
  }
}

export async function deleteCliente(id) {
  try {
    await api.delete(`/clientes/${id}`);
  } catch (error) {
    console.error('Erro ao excluir cliente:', error);
    throw error;
  }
}

// Função para Marcas
export async function fetchMarcas() {
  try {
    const response = await api.get('/marcas');
    return response.data;
  } catch (error) {
    console.error('Erro ao carregar marcas:', error);
    throw error;
  }
}

export default api;
