# API de Gestão de Pedidos de Viagem

Esta é uma aplicação de API construída com **Laravel** e **JWT Authentication** para gerenciar pedidos de viagem, com funcionalidades como criação de pedidos, consulta, atualização de status e notificação para os solicitantes.

## Funcionalidades

- **Cadastro de Pedidos de Viagem**: Criar um novo pedido de viagem com dados como solicitante, destino e datas de viagem.
- **Atualização de Status**: Alterar o status de um pedido (por exemplo, "Aprovado", "Cancelado").
- **Consulta de Pedidos**: Buscar um pedido específico através de seu ID.
- **Listagem de Pedidos**: Listar todos os pedidos com possibilidade de filtragem por **status**, **período** e **destino**.
- **Notificação de Aprovação ou Cancelamento**: Enviar notificações para os solicitantes quando um pedido for aprovado ou cancelado.

## Tecnologias

- **Laravel**: Framework PHP para o backend.
- **JWT (JSON Web Token)**: Utilizado para autenticação de usuários.
- **MySQL**: Banco de dados relacional utilizado pela aplicação.
- **Docker**: Contêinerização da aplicação para fácil execução em qualquer ambiente.
- **Postman**: Ferramenta de testes de APIs.

## Pré-requisitos

- Docker e Docker Compose instalados.
- Composer instalado (para execução local, sem Docker).
- Postman ou qualquer cliente HTTP para testar os endpoints da API.

## Rodando a aplicação via Docker

### Passo 1: Clonar o repositório

```bash
git clone https://github.com/danielsantos150/pedidos_viagem.git
cd pedidos_viagem
```

### Passo 2: Subir os contêineres com Docker Compose

```bash
docker-compose up -d
```

Este comando vai:
- Criar e subir os contêineres necessários.
- Subir a aplicação Laravel, banco de dados MySQL e outras dependências.


### Passo 3: Rodar as Migrations

A aplicação depende de migrations para a criação do banco de dados e das tabelas. Para executar as migrations, use o seguinte comando:

```bash
docker-compose exec app php artisan migrate
```

Esse comando executará as migrations para configurar o banco de dados corretamente.

### Passo 4: Gerar a Chave JWT

Antes de testar a autenticação com JWT, é necessário gerar a chave JWT para a aplicação:

```bash
docker-compose exec app php artisan jwt:secret
```

Isso criará uma chave secreta que será usada para gerar e validar os tokens JWT.

## Ednpoints da API

Aqui estão os principais endpoints da API, incluindo o método HTTP, descrição e exemplos de uso.

1 - Cadastro de Pedido de Viagem

POST /api/pedidos

Cria um novo pedido de viagem.

- body:
```json
{
  "solicitante_nome": "João Silva",
  "destino": "Rio de Janeiro",
  "data_ida": "2024-12-01 10:00:00",
  "data_volta": "2024-12-10 18:00:00",
  "status": "pendente",
  "email": "joao@exemplo.com"
}
```

2 - Atualizar Status de Pedido

PATCH /api/pedidos/{id}/status

Atualiza o status de um pedido de viagem. É possível marcar o status como “Aprovado” ou “Cancelado”.
Caso deseje enviar a notificação informe o campo notiticar = true

- body:
```json
{
  "status": "aprovado",
  "notificar": true
}
```

3 - Consultar Pedido Específico

GET /api/pedidos/{id}

Recupera os detalhes de um pedido específico através de seu ID.

4 - Listar Pedidos

GET /api/pedidos

Lista todos os pedidos. É possível aplicar filtros por status, período e destino.

- Filtros:
    - status (opcional)
    - data_inicio (opcional)
    - data_fim (opcional)
    - destino (opcional)

```bash
GET /api/pedidos?status=aprovado&data_inicio=2024-12-01&data_fim=2024-12-31&destino=Belo Horizonte
```

5 -  Autenticação e Registro

POST /api/auth/register

Cria um novo usuário.

- body:
```json
{
  "name": "João Silva",
  "email": "joao@exemplo.com",
  "password": "senha123",
  "password_confirmation": "senha123"
}
```

POST /api/auth/login

Realiza login e retorna um token JWT.

- body:
```json
{
  "email": "joao@exemplo.com",
  "password": "senha123"
}
```

## Configurações Adicionais

Variáveis de Ambiente

Certifique-se de que as variáveis de ambiente estão configuradas corretamente no arquivo .env:
(No git do projeto foi adicionado o arquivo .env, mesmo que não seja indicado o versionamento do mesmo em algumas situações, com o objetivo de facilitar a configuracao)
Deixei uns comentarios no .env abaixo dos campos caso queira modificar e os que precisam ser alterados.

```ini
#MUDE APENAS SE QUISER
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:vj+lkPHTjzSekCIGTFlg0ynKuj/JflYPLZwNU0JJjkQ=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

#OS VALORES AQUI PRESENTES TEM QUE SER IGUAIS AO DO DOCKER-COMPOSE
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=gerenciador_viagens
DB_USERNAME=root
DB_PASSWORD=abc_123456

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

#AQUI TEM QUE SER ALTERADO
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME={coloque_aqui_suas_credenciais_do_mailtrap}
MAIL_PASSWORD={coloque_aqui_suas_credenciais_do_mailtrap}

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

#AQUI TEM QUE SER ALTERADO
JWT_SECRET={token_jwt_gerado_pelo_comando}

```


- Docker Compose

No arquivo docker-compose.yml, a aplicação foi configurada com os seguintes serviços:
    - app: O contêiner da aplicação Laravel.
	- mysql: Banco de dados MySQL.

Exemplo de docker-compose.yml:

```yaml
version: '3.8'

services:
  app:
    image: laravelapp
    container_name: laravel-app
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - mysql
    environment:
      - DB_CONNECTION=mysql
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=gerenciador_viagens
      - DB_USERNAME=root
      - DB_PASSWORD=abc_123456

  mysql:
    image: mysql:8.0
    container_name: mysql-db
    environment:
      MYSQL_ROOT_PASSWORD: abc_123456
      MYSQL_DATABASE: gerenciador_viagens
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"

volumes:
  db_data:

```

## Para facilitar, segue o arquivo do postman para auxiliar nos testes das rotas:

[Onfly.postman_collection.json](https://github.com/user-attachments/files/17791981/Onfly.postman_collection.json)
