# Dépendances

Pour Symfony :

	$ sudo aptitude install couchdb libapache2-mod-php5 php5-cli php5-curl php5-ldap

Pour la génération des pdf en latex :

	$ sudo aptitude install texlive-fonts-recommended texlive-latex-extra pdflatex pdftk texlive-lang-french texlive-lang-greek

# Déploiement du code via git

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

# installation du moteur de recherche des comptes

Pour réaliser cette installation, il faut que vous installiez d'abord [ElasticSearch](ElasticSearch.md) (avec la river couchdb comme [indiqué dans la documentation fournie](ElasticSearch.md)).

Une fois elasticsearch fonctionnel avec la river couchdb, vous pouvez executer la commande suivante :

        $ bash bin/elastic_configure

# mise en place de l'export LDAP

Afin de permettre aux utilisateurs pouvoir  consulter le contenu des comptes VINSI sur leurs outils de mails, une passerelle LDAP a été développée. Pour l'activer, il faut installer d'abord [LDAP en suivant la procédure fournie](LDAP.md).

Un script permet d'importer tous les comptes de la base dans lDAP :

	$ bash bin/comptes_update_ldap.sh
