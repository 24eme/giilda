# GIILDE : Groupement IVSO IVBD de Logiciel de déclaration éléctronique

# Installation

Dependance :

> sudo aptitude install couchdb libapache2-mod-php5 php5-cli php5-curl

Get the source project :

> git clone git@github.com:24eme/giilde.git #Https recuperation https://github.com/24eme/giilde.git

> cd giilde

Configure the projet :

> make

You can configure your database settings in project/config/databases.yml

Create database :

> curl -X PUT http://localhost:5984/you_database_name
