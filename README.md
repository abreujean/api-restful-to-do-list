# API RESTful para Gerenciamento de Tarefas (To-Do List) com Laravel

## 📋 Descrição
API RESTful desenvolvida em Laravel para gerenciamento de tarefas pessoais, com autenticação básica e operações CRUD completas.

## 🛠️ Pré-requisitos
- PHP 8.0+
- Composer
- Laravel 12.x
- SQLite (para testes) / MySQL (para desenvolvimento)

## 🚀 Configuração do Projeto

### 1. Clone o repositório
```bash
git clone https://github.com/abreujean/api-restful-to-do-list
cd nome-do-projeto
```

### 2. Instale as dependências
```bash
composer install
```

### 3. Configure o ambiente
Crie e configure o arquivo `.env` baseado no `.env.example`:
```bash
cp .env.example .env
```

### 4. Gere a chave da aplicação
```bash
php artisan key:generate
```

## 🗃️ Banco de Dados

### Migrations (Desenvolvimento)
```bash
php artisan migrate
```

### Seeders (Dados iniciais)
```bash
php artisan db:seed
```

Os seguintes status serão criados automaticamente:
- Pendente
- Em Andamento
- Concluída

## 🧪 Testes
O projeto utiliza SQLite em memória para testes:

```bash
php artisan test
```

Configuração do ambiente de teste (`.env.testing`):
```ini
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

## 🌐 Rotas da API

### Tarefas
- `GET /api/tasks` - Listar todas as tarefas
- `POST /api/tasks` - Criar nova tarefa
- `PATCH /api/tasks/{hash}/status` - Atualizar status
- `DELETE /api/tasks/{hash}` - Deletar tarefa
- `GET /api/tasks/status/{status}` - Filtrar por status

## 📚 Documentação Swagger
Acesse a documentação interativa após iniciar o servidor:
```bash
php artisan serve
```
Acesse: `http://localhost:8000/api/documentation`

## 🛠️ Tecnologias Utilizadas
- Laravel 12.x
- Eloquent ORM
- SQLite (testes)
- PHPUnit para testes
- Swagger/OpenAPI para documentação

## 🧰 Estrutura do Projeto
```
app/
├── Http/
│   ├── Controllers/
│   │   └── TaskController.php
│   └── Requests/
├── Models/
│   ├── Task.php
│   └── Status.php
database/
├── migrations/
├── seeders/
tests/
├── Feature/
│   └── TaskTest.php
```
## 📄 Licença
[MIT](https://choosealicense.com/licenses/mit/)