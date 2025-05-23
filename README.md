# Vendas Smart

Bem-vindo ao **Vendas Smart**, uma aplicação web para gerenciamento de clientes, marcas, produtos e vendas.
Este projeto é composto por uma API RESTful (back-end) e uma interface de usuário (front-end),
desenvolvida para demonstrar habilidades full stack. A aplicação permite operações CRUD para clientes,
marcas e produtos, além de criação e listagem de vendas, com autenticação segura.

## Tecnologias Utilizadas

### Back-end

- **Laravel 12.14.1**: Framework PHP para construção da API RESTful, com Eloquent ORM e validação.
- **PHP 8.2**: Linguagem principal do back-end.
- **MySQL**: Banco de dados relacional para armazenamento de dados (clientes, vendas, etc.).
- **Laravel Sanctum 4.1**: Autenticação baseada em tokens para proteger rotas da API.
- **Fruitcake PHP-CORS**: Gerenciamento de CORS para integração com o front-end.

### Front-end

- **Vue.js 3**: Framework JavaScript para construção de interfaces dinâmicas e reativas.
- **Tailwind CSS**: Framework CSS utilitário para estilização responsiva e moderna.
- **DaisyUI**: Biblioteca de componentes Tailwind para interfaces prontas e consistentes.
- **Heroicons**: Conjunto de ícones SVG para uso em componentes Vue.js.
- **Vue Mask**: Utilização para uso de regex simplificado (ex.: CPF, CNPJ, telefone).
- **JavaScript (ES Modules)**: Lógica do front-end, integrada com Vue.js e Vite.
- **Vite 6.2.4**: Ferramenta de build e hot-reload para desenvolvimento rápido do front-end.
- **Axios**: Biblioteca para requisições HTTP à API.

### Outros

- **Composer**: Gerenciador de dependências do PHP.
- **NPM**: Gerenciador de pacotes do front-end.
- **Git**: Controle de versão do projeto.

## Pré-requisitos

Antes de inicializar o projeto, certifique-se de ter instalado:

- **PHP 8.2** ou superior
- **Composer** (gerenciador de dependências PHP)
- **Node.js 18+** e **NPM** (para o front-end)
- **MySQL 8.0** ou superior
- **Git** (para clonar o repositório)

## Instalação e Inicialização

### 1. Clonar o Repositório

```bash
git clone <url-do-repositorio>
cd sistema-vendas
```

### 2. Configurar o Back-end

1. **Instalar dependências do PHP**:

   ```bash
   composer install
   ```

2. **Configurar MySQL**:

- Crie um banco de dados MySQL com o nome "vendas_sistema"

3. **Configurar o ambiente**:

   - Crie o arquivo `.env` e depois,
   - Copie o arquivo `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```
   - Edite o `.env` com suas configurações do MySQL:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=vendas_sistema
     DB_USERNAME=seu_usuario
     DB_PASSWORD=sua_senha
     ```
   - Configure o domínio do front-end para Sanctum (se necessário):
     ```env
     SANCTUM_STATEFUL_DOMAINS=localhost:5173
     ```

4. **Gerar a chave da aplicação**:

   ```bash
   php artisan key:generate
   ```

5. **Executar migrações**:

   - Crie o banco de dados no MySQL (ex.: `vendas_sistema`).
   - Aplique as migrações para criar as tabelas:
     ```bash
     php artisan migrate
     ```

6. **Iniciar o servidor do back-end**:
   ```bash
   php artisan serve
   ```
   A API estará disponível em `http://localhost:8000`.

### 3. Configurar o Front-end

1. **Instalar dependências do Node.js**:

   ```bash
   npm install
   ```

2. **Iniciar o servidor de desenvolvimento**:
   ```bash
   npm run dev
   ```
   O front-end estará disponível em `http://localhost:5173`.

- **Acesse o front-end**:
  - Abra `http://localhost:5173` no navegador para interagir com a interface Vue.js.

## Funcionalidades

- **Autenticação**: Registro e login de usuários com tokens Sanctum.
- **Clientes**: CRUD completo (criar, listar, editar, excluir).
- **Marcas**: CRUD completo, com listagem pública.
- **Produtos**: CRUD completo, associados a marcas.
- **Vendas**: Criação e listagem, com itens e cálculo de total.
- **Interface**: Design com Vue.js, Tailwind CSS, DaisyUI, e ícones Heroicons.

## Notas

- Certifique-se de que o MySQL está rodando antes de executar `php artisan migrate`.
- O front-end assume que a API está em `http://localhost:8000`. Ajuste a URL base no Axios se necessário.
- Vue Mask está configurado no componentes de formulário ao cadastrar clientes.

## 👨‍🔧 Possiveis melhorias no futuro

- Melhorias no front-end e criação de página HOME, Perfil do usuário/cliente e contato SAC ou Chatbot
- Consertar erros de responsabilidade
- Regex para preço
- Importação para download de arquivo como PDF, CSV e etc..
- Implementação de confirmação via e-mail para autenticação de usuário
- Melhorias de performance
- Reduzir código em páginas Views para melhor uso de estrutura de components
- Implementação de lógica de status e dinâmica visual nos datatable

## Contribuição

1. Faça um fork do repositório.
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`).
3. Commit suas alterações (`git commit -m 'Adiciona nova funcionalidade'`).
4. Push para a branch (`git push origin feature/nova-funcionalidade`).
5. Abra um Pull Request.

## Licença

Este projeto é para fins educacionais e de demonstração.
