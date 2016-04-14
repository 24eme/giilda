# GIILDA : Groupement IVSO IVBD de Logiciels dediés aux Déclarations Administratives

Projet de dématérialisation informatique des activités des interprofessions des Vins du Sud Ouest (IVSO) et de Bergerac (IVBD)

# Aperçu de l'application

![Écran de saisie de la DRM](doc/captures/drm.jpg)

# License utilisée

Ce logiciel est mis à disposition en licence AGPL

# Installation

Dépendances :

> sudo aptitude install couchdb libapache2-mod-php5 php5-cli php5-curl curl texlive-lang-french texlive-latex-extra libjson-perl

Get the source project :

> git clone git@github.com:24eme/giilde.git #Https recuperation https://github.com/24eme/giilde.git

> cd giilde

Configure the projet :

> make

You can configure your database settings in project/config/databases.yml

Create database :

> curl -X PUT http://localhost:5984/you_database_name

Load fixtures testing data (optional) :

> bash bin/fixtures_init_and_load.sh
