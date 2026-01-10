.PHONY: help up down restart build bash logs clean install composer-install npm-install migrate fresh seed test cache-clear permission

# Variáveis
DOCKER_COMPOSE = docker compose
PHP_CONTAINER = app
DB_CONTAINER = mysql

# Exibe a ajuda com todos os comandos disponíveis
help:
	@echo "Comandos disponíveis:"
	@echo "  make up              - Inicia os containers"
	@echo "  make down            - Para e remove os containers"
	@echo "  make restart         - Reinicia os containers"
	@echo "  make build           - Constrói as imagens Docker"
	@echo "  make bash            - Acessa o terminal do container PHP"
	@echo "  make logs            - Exibe os logs dos containers"
	@echo "  make clean           - Remove containers, volumes e imagens"
	@echo "  make install         - Instalação completa do projeto"
	@echo "  make composer-install - Instala dependências PHP"
	@echo "  make npm-install     - Instala dependências Node.js"
	@echo "  make migrate         - Executa as migrations"
	@echo "  make fresh           - Reseta o banco e executa migrations"
	@echo "  make seed            - Executa os seeders"
	@echo "  make test            - Executa os testes"
	@echo "  make cache-clear     - Limpa todos os caches"
	@echo "  make permission      - Corrige permissões de pastas"

# Inicia os containers
up:
	$(DOCKER_COMPOSE) up -d

# Para e remove os containers
down:
	$(DOCKER_COMPOSE) down

# Reinicia os containers
restart:
	$(DOCKER_COMPOSE) restart

# Constrói as imagens Docker
build:
	$(DOCKER_COMPOSE) build --no-cache

# Acessa o terminal do container PHP
bash:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) bash

# Exibe os logs dos containers
logs:
	$(DOCKER_COMPOSE) logs -f

# Remove containers, volumes e imagens
clean:
	$(DOCKER_COMPOSE) down -v --rmi all --remove-orphans

# Instalação completa do projeto
install: build up composer-install npm-install permission migrate seed
	@echo "Instalação concluída!"

# Instala dependências PHP
composer-install:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) composer install

# Atualiza dependências PHP
composer-update:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) composer update

# Instala dependências Node.js
npm-install:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm install

# Compila assets (desenvolvimento)
npm-dev:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm run dev

# Compila assets (produção)
npm-build:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm run build

# Executa as migrations
migrate:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan migrate

# Reseta o banco e executa migrations
fresh:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan migrate:fresh

# Executa os seeders
seed:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan db:seed

# Reseta banco, migrations e seeders
fresh-seed: fresh seed

# Executa os testes
test:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan test

# Limpa cache de configuração
cache-config:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan config:clear
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan config:cache

# Limpa cache de rotas
cache-route:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan route:clear
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan route:cache

# Limpa cache de views
cache-view:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan view:clear
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan view:cache

# Limpa todos os caches
cache-clear:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan cache:clear
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan config:clear
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan route:clear
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan view:clear

# Otimiza a aplicação
optimize:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan optimize

# Corrige permissões de pastas
permission:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) chmod -R 775 storage bootstrap/cache
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) chown -R www-data:www-data storage bootstrap/cache

# Cria um novo controller
controller:
	@read -p "Nome do controller: " name; \
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan make:controller $$name

# Cria um novo model
model:
	@read -p "Nome do model: " name; \
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan make:model $$name

# Cria uma nova migration
migration:
	@read -p "Nome da migration: " name; \
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan make:migration $$name

# Cria um novo seeder
seeder:
	@read -p "Nome do seeder: " name; \
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan make:seeder $$name

# Executa o tinker
tinker:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan tinker

# Acessa o MySQL
mysql:
	$(DOCKER_COMPOSE) exec $(DB_CONTAINER) mysql -u root -p

# Status dos containers
status:
	$(DOCKER_COMPOSE) ps
