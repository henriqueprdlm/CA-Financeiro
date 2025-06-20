# CA Financeiro

Sistema web para gerenciamento financeiro do Centro Acadêmico de Ciência da Computação.
Permite a visualização e controle de categorias e lançamentos financeiros (receitas e despesas), além da gestão de usuários administradores com autenticação segura via Clerk e registro de logs de todas modificações feitas no sistema.

---

## Tecnologias Utilizadas

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP (API REST)
- **Banco de Dados:** MySQL
- **Autenticação:** [Clerk](https://clerk.dev/)
- **Ambiente de desenvolvimento:** XAMPP

---

## Funcionalidades

- Visualização pública do saldo e lançamentos financeiros por categoria
- Área administrativa com:
  - Cadastro de categorias
  - Cadastro de lançamentos (receitas e despesas)
  - Gerenciamento de usuários administradores
- Autenticação segura via Clerk
- Interface responsiva

---

## Instalação e Execução (usando XAMPP)

### 1. Pré-requisitos

- [XAMPP](https://www.apachefriends.org/index.html) instalado
- Conta no [Clerk](https://clerk.dev)
- [Composer](https://getcomposer.org/) instalado
- Git (opcional)

### 2. Clone o projeto

```bash
git clone https://github.com/henriqueprdlm/CA-Financeiro.git
```

Ou copie os arquivos manualmente para o diretório do XAMPP:

```
C:/xampp/htdocs/cafinanceiro
```

### 3. Crie o banco de dados

No **phpMyAdmin** ou via terminal MySQL:

```sql
CREATE DATABASE CAFinanceiro;
```

Depois, importe o arquivo `CAFinanceiro.sql` que está no projeto com a estrutura das tabelas.

### 4. Instale as dependências

Para instalar as dependências do projeto via Composer, execute:

```bash
composer install
```

### 5. Configure o arquivo `.env`

Crie um arquivo `.env` na raiz do projeto com o seguinte conteúdo:

```env
CLERK_SECRET_KEY=insira-sua-clerk-secret-key-aqui
```

Esse arquivo é usado para autenticar as requisições na API do Clerk, especialmente nas rotas protegidas.

Para saber mais sobre a API, [aqui está o link da documentação] (https://documenter.getpostman.com/view/45854706/2sB2x6nsn6).

> **Nunca envie o arquivo `.env` para o GitHub!** Adicione `.env` no `.gitignore`.

### 6. Inicie o XAMPP

- Ative o **Apache** e o **MySQL**
- Acesse o projeto em:  
  [http://localhost/cafinanceiro](http://localhost/cafinanceiro)

---

## Autenticação com Clerk

O sistema utiliza **Clerk** para autenticação de administradores:

- O login e o cadastro são gerenciados externamente pelo Clerk
- O cadastro é feito via convite por e-mail
- O JWT (token) obtido pelo Clerk é usado para autenticar chamadas à API

---

## Observação

[Neste link](https://documenter.getpostman.com/view/45854706/2sB2x6nsn6) está a documentação da API.

---

## Autores

- **Henrique Pieri de Lima**
- **Alexandre Willian de Bastiani**

Projeto interdisciplinar desenvolvido nas disciplinas de Desenvolvimento Web II, Engenharia de Software I e Projeto Aplicado I no curso de Ciência da Computação do Instituto Federal Catarinense - Campus Videira.
