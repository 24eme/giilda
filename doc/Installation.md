# dépendances

Pour Symfony :

	$ sudo aptitude install couchdb libapache2-mod-php5 php5-cli php5-curl

Pour la génération des pdf en latex :

	$ sudo aptitude install texlive-fonts-recommended texlive-latex-extra pdflatex pdftk texlive-lang-french texlive-lang-greek

# git

	$ cd /var/www (ou à un autre emplacement)
	$ git clone https://git.gitorious.org/vinsdeloire/vinsdeloire.git
	$ cd vinsdeloire
	$ git pull origin prod

# mise à jour du code 

A la racine du projet :

	$ git pull origin prod

# configuration d'apache

Ajouter un vhost avec les éléments suivants :

	<VirtualHost *:80>
	ServerName declaration.dev.vinsdeloire.fr
	DocumentRoot /var/www/vinsdeloire/project/web
	DirectoryIndex index.php
	<Directory "/var/www/vinsdeloire/project/web">
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>

	Alias /sf "/var/www/vinsdeloire/project/lib/vendor/symfony/data/web/sf"
	<Directory "/var/www/vinsdeloire/project/lib/vendor/symfony/data/web/sf">
		AllowOverride All
		Allow from All
	</Directory>

	# Dans le cas ou xdebug est installé
	# php_value xdebug.max_nesting_level 120
	</VirtualHost>

# création de la base couchdb

	$ curl -X PUT http://localhost:5984/vinsdeloire

# configuration de symfony

	$ cd project
	$ mkdir log cache
	$ chmod g+wx log cache
	$ sudo chown www-data log cache
	$ cp config/databases.yml{.example,}

adapter l'adresse du couchdb si nécessaire

	$ cp config/app.yml{.example,}
	$ cp bin/config{.example.inc,.inc}

gestion des droits pour les fichiers générés :

	$ sudo mkdir data/latex
	$ sudo chown www-data data/latex

# import/initialisation de données de l'application

	$ php symfony cc
