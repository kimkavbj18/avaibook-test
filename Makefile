#!/bin/bash
# --- MAKE INSTALLATION ---
# Windows :
# 	- Install Git for windows
# 	- Go to https://sourceforge.net/projects/ezwinports/files/ and download make-4.3-without-guile-w32-bin.zip
# 	- Extract zip
#   - Copy the contents to your GitInstallDirectory\mingw64 merging the folders
# Linux : Usually make command is installed, To install it, you can do it from the repositories (sudo apt install make or similar)
# Macos: Normally available by default, if not run in a terminal xcode-select --install

# Docker containers
DOCKER_BE = avcodt_php
DOCKER_DB = avcodt_db

# Env files
ENV_LOCAL = .env.local
DEFAULT_ENV = dev
DOCKER_ENV = docker/.env

# Alias
UID = 1000:1000
EXEC_PHP = php
DOCKER_EXEC = docker exec -i -u ${UID}
DOCKER_SSH = ${DOCKER_EXEC} ${DOCKER_BE}
DOCKER_COMPOSE_SSH = docker-compose --env-file ${DOCKER_ENV} exec -T -u ${UID} avapi_php env TERM=xterm
DOCKER_ROOT_SSH = docker exec -it -u root ${DOCKER_BE}

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

net-create:
	docker network inspect avaibook-network >/dev/null || docker network create avaibook-network
	@echo -e '#\e[38;2;0;240;0m avaibook-network active!\e[0m'

install-windows: ## Install project in Windows
	make env-install-windows
	make net-create
	make start
	$(DOCKER_ROOT_SSH) composer config --global disable-tls true
	$(DOCKER_ROOT_SSH) composer config --global secure-http false
	./docker/scripts/welcome.bash
	@echo -e '#\e[38;2;0;240;0m Installed successfully, now go to http://localhost:87 on your navigator\e[0m'
	@echo -e '#\e[38;2;0;240;0m Remember, mysql server is available on localhost:3306.\e[0m'
	@echo -e '\r\n\r\n\r\n\r\n\r\n\r\n\r\n'

install: ## Install project in Unix
	make env-install-unix
	make net-create
	make start
	$(DOCKER_ROOT_SSH) composer config --global disable-tls true
	$(DOCKER_ROOT_SSH) composer config --global secure-http false
	./docker/scripts/welcome.bash
	@echo -e '#\e[38;2;0;240;0m Installed successfully, now go to http://127.0.7.14 on your navigator!\e[0m'
	@echo -e '#\e[38;2;0;240;0m Remember, mysql server is available on 127.0.0.1:3312.\e[0m'
	@echo -e '\r\n\r\n\r\n\r\n\r\n\r\n\r\n'


uninstall: ## Uninstall project
	make stop
	docker-compose --env-file ${DOCKER_ENV} rm
	docker volume rm -f docker_avcodt_dbdata
	@echo -e '#\e[38;2;0;240;0m Uninstalled successfully!\e[0m'

env-install-%:
	touch $(DOCKER_ENV)
	cat "docker/.$*.conf" > $(DOCKER_ENV)

start: ## Starts docker containers
	docker-compose --env-file ${DOCKER_ENV} up -d --remove-orphans

stop: ## Stops docker containers
	docker-compose --env-file ${DOCKER_ENV} down --remove-orphans

build:
	docker-compose --env-file ${DOCKER_ENV} build