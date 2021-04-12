cc: ## Clear cache
	php bin/console  c:c --env=dev
	php bin/console  c:c --env=prod

vendor:composer.json ## installé les dépendances
	composer install

help: ## afficher les description des commandes de makeFile
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

build:  ## construire les conteneurs docker
	docker-compose up --build

up:  ## construire les conteneurs docker
	docker-compose up

stop:  ## éteindre les conteneurs docker
	docker-compose stop

down:  ## éteindre les conteneurs docker
	docker-compose down

php:  ## Acceder au container php
	 docker exec -it  EventsManager-php-fpm bash

access:  ## Acceder au container php avec les préviléges Root
#	docker exec -it  EventsManager-php-fpm chmod -R 777 /application
	chmod -R 777 /application


test:  ## Lance les tests unitaire
	vendor/bin/simple-phpunit

reload:  ## reload database(delete,create,charge les données)
	php bin/console doctrine:schema:drop --force \
	&& php bin/console doctrine:schema:update --force \
	&& php bin/console doctrine:fixtures:load -n

php-cs-fixer: ## l'analyseeur du code php-cs-fixer
	php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix src/


phpstanSetup:  ## Lance l'installation de code PhpStan
	composer require --dev phpstan/phpstan

phpstan:  ## Lance l'installation de code PhpStan
	vendor/bin/phpstan analyse --level 6 src
	#vendor/bin/phpstan analyse -c phpstan.neon

phpmd:  ## Lance l'analyseeur du code phpmd
	#vendor/phpmd/phpmd/src/bin/phpmd src/ text cleancode
	vendor/phpmd/phpmd/src/bin/phpmd src/ text naming
#	vendor/phpmd/phpmd/src/bin/phpmd src/ text design

phpmdHelp:  ## afficher le help de l'analyseeur du code phpmd avec l'option -h
	vendor/phpmd/phpmd/src/bin/phpmd -h

nginxReload:  ## afficher le help de l'analyseeur du code phpmd avec l'option -h
	docker exec -it EventsManager-webserver nginx -s reload
	#docker exec EventsManager-webserver nginx -s reload
	#docker restart EventsManager-webserver



