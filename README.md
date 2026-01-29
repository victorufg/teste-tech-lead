# Teste Desenvolvedor Full Stack Laravel 🚀

Este projeto é uma aplicação de gerenciamento de Cursos, Alunos e Matrículas, desenvolvida como parte de um teste técnico. A interface foi construída para ser premium, performática e intuitiva.

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade, Alpine.js (Interatividade), Tailwind CSS (Estilização)
- **Autenticação**: Laravel Breeze
- **Banco de Dados**: MySQL (via Docker/Sail)
- **Testes**: PHPUnit

## 🚀 Como Executar o Projeto

### 1. Pré-requisitos
Certifique-se de ter o **Docker** instalado em sua máquina.

### 2. Configuração Inicial

Clone o projeto e entre na pasta. Para instalar as dependências sem ter o PHP instalado localmente, use o comando abaixo (escolha conforme seu terminal):

**No Linux / macOS / WSL (Bash):**
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

**No Windows (PowerShell):**
```powershell
docker run --rm `
    -v "${PWD}:/var/www/html" `
    -w /var/www/html `
    laravelsail/php83-composer:latest `
    composer install --ignore-platform-reqs
```

### 3. Ambiente Docker (Sail)

Suba os containers:
```bash
# No Linux/WSL/Git Bash
./vendor/bin/sail up -d

# No PowerShell
php vendor/bin/sail up -d
```

### 4. Migrations e Seeds

Prepare o banco de dados e popule-o com dados iniciais (50 alunos, 10 cursos e matrículas aleatórias):

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

### 5. Compilação de Assets

Instale e compile os arquivos CSS/JS:
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

---

## 🔑 Acesso ao Sistema

O seeder cria um usuário administrativo padrão:

- **URL**: `http://localhost`
- **E-mail**: `admin@teste.com`
- **Senha**: `password`

---

## 🛡️ Testes Automatizados

A aplicação possui uma cobertura de testes unitários e de funcionalidade (52 testes).

Para rodar os testes:
```bash
./vendor/bin/sail artisan test
```

---

## 🔌 API REST

A aplicação expõe os seguintes endpoints:

### Cursos
- `GET /api/courses` - Lista todos os cursos
- `POST /api/courses` - Cria um novo curso

### Alunos
- `GET /api/students` - Lista todos os alunos
- `POST /api/students` - Cria um novo aluno

### Matrículas
- `GET /api/enrollments` - Lista todas as matrículas
- `POST /api/enrollments` - Matricula um aluno em um curso
- `DELETE /api/enrollments/{id}` - Remove uma matrícula

---

## ✨ Funcionalidades Principais

- **Busca Instantânea (AJAX)**: Filtre cursos e alunos sem recarregar a página.
- **Deleção em Massa**: Selecione múltiplos itens na tabela e exclua-os de uma vez através da barra de ações flutuante.
- **Interface Premium**: Uso de Side Drawers para formulários, micro-interações com Alpine.js e design responsivo.
- **Otimização de Performance**: Uso de indexes no banco e `withCount` para evitar o problema de N+1 queries.
