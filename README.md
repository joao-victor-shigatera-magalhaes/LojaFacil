# LojaFácil

Sistema de consulta e gerenciamento de preços de produtos, desenvolvido como projeto acadêmico do curso de Análise e Desenvolvimento de Sistemas (ADS).

## Sobre o projeto

O LojaFácil tem como objetivo facilitar o cadastro, a consulta e o gerenciamento de produtos e seus preços.

O sistema possui funcionalidades para gerenciamento de usuários, categorias e produtos, permitindo organizar as informações de maneira simples.

## Tecnologias utilizadas

- **PHP:** desenvolvimento do backend utilizando programação estruturada.
- **MySQL:** gerenciamento do banco de dados.
- **MySQLi:** comunicação entre PHP e MySQL.
- **HTML:** estrutura das páginas.
- **JavaScript:** interatividade e comunicação com o backend.
- **Git e GitHub:** versionamento e compartilhamento do código.

## Estrutura do projeto

```text
LojaFacil/
│
├── backend/
│   ├── categorias/
│   ├── produtos/
│   ├── usuarios/
│   ├── conexao.php
│   ├── logout.php
│   ├── verificar_login.php
│   └── verificar_permissao.php
│
├── frontend/
│   ├── cadastro_produto.html
│   ├── cadastro_usuario.html
│   ├── index.html
│   ├── login_loja.html
│   ├── menu.html
│   └── produtos.html
│
├── .env.example
├── .gitignore
└── README.md
```

## Requisitos para executar o projeto

- Windows (ou ambiente compatível).
- XAMPP com Apache e PHP.
- MySQL Server 8.0 ou compatível.
- Extensão MySQLi habilitada no PHP.
- Navegador de internet.

## Como instalar e executar

### 1. Clonar o repositório

Abra o terminal e execute:

```bash
git clone https://github.com/joao-victor-shigatera-magalhaes/LojaFacil.git
```

### 2. Colocar o projeto no servidor local

Se estiver utilizando XAMPP, coloque a pasta `LojaFacil` dentro de:

```text
C:\xampp\htdocs\
```

### 3. Configurar o banco de dados

Crie um banco de dados MySQL chamado:

```sql
CREATE DATABASE consulta_precos;
```

**Importante:** as tabelas necessárias também precisam ser criadas. Utilize o script SQL de estrutura e dados iniciais do projeto, quando disponibilizado.

### 4. Configurar as variáveis de ambiente

Na raiz do projeto, crie um arquivo chamado `.env`.

Utilize como referência o arquivo `.env.example`:

```ini
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=consulta_precos
DB_USER=root
DB_PASSWORD=
```

Altere os valores conforme a configuração do seu MySQL.

- `DB_HOST`: endereço do servidor MySQL.
- `DB_PORT`: porta utilizada pelo MySQL.
- `DB_NAME`: nome do banco de dados.
- `DB_USER`: usuário do banco.
- `DB_PASSWORD`: senha do usuário.

**Atenção:** não compartilhe seu arquivo `.env`, pois ele contém informações privadas de conexão. Cada integrante deve criar seu próprio arquivo.

### 5. Iniciar o servidor

Abra o XAMPP e inicie o Apache.

Certifique-se de que o servidor MySQL também esteja funcionando.

### 6. Acessar o projeto

Abra o navegador e acesse:

```text
http://localhost/LojaFacil/frontend/login_loja.html
```

## Funcionalidades desenvolvidas

- Cadastro e gerenciamento de usuários.
- Cadastro e gerenciamento de categorias.
- Cadastro de produtos.
- Listagem e consulta de produtos.
- Edição de produtos.
- Ativação e inativação de produtos.
- Autenticação de usuários.
- Controle de permissões.

## Organização do desenvolvimento

O backend foi desenvolvido utilizando PHP procedural e MySQLi, seguindo os requisitos acadêmicos de programação estruturada:

- Sequência.
- Decisão.
- Repetição.
- Utilização de funções.

## Desenvolvedor

**João Victor Magalhães**

Estudante de Análise e Desenvolvimento de Sistemas (ADS).

## Repositório

[GitHub — LojaFacil](https://github.com/joao-victor-shigatera-magalhaes/LojaFacil)

---

*Projeto acadêmico desenvolvido para fins educacionais.*