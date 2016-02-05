# GIILDE : Module de statistiques

## Installation

### Prérecquis

    sudo aptitude install openjdk-7-jre

### ElasticSearch 2.1.1

Récupération du .deb :

    wget -P /tmp/ https://download.elasticsearch.org/elasticsearch/release/org/elasticsearch/distribution/deb/elasticsearch/2.1.1/elasticsearch-2.1.1.deb

Installation :

    sudo dpkg -i /tmp/elasticsearch-2.1.1.deb

Installation du plugin HEAD :

    sudo /usr/share/elasticsearch/bin/plugin -install mobz/elasticsearch-head

### Logstash 2.1.1

Récupération du .deb :

    wget -P /tmp/ https://download.elastic.co/logstash/logstash/packages/debian/logstash_2.1.1-1_all.deb 

Installation :

    sudo dpkg -i /tmp/logstash_2.1.1-1_all.deb

Installation des plugins necessaires au module de statistiques :

    cd /opt/logstash
    sudo bin/plugin install logstash-input-couchdb_changes
    sudo bin/plugin install logstash-output-elasticsearch
    sudo bin/plugin install logstash-output-http

### Kibana

Installation via le site : [https://www.elastic.co/downloads/kibana](https://www.elastic.co/downloads/kibana)

## Indexation

Configurer le fichier project/bin/config.inc basé sur le fichier project/bin/config.example.inc

Lancer le script dans project/ :

    bash bin/elastic2_configure

Relancer le service logstash :

> sudo service logstash restart

S'assurer que le numéro de sequence relatif à la base de donnée n'existe pas dans le fichier */var/lib/logstash/.couchdb_seq*

## Visualisation

### ElasticSearch

Visiter [http://127.0.0.1:9200/_plugin/head/](http://127.0.0.1:9200/_plugin/head/)

### Kibana

Lancer, depuis le dossier d'installation de Kibana :

    bin/kibana

Visiter [http://0.0.0.0:5601](http://0.0.0.0:5601)

Dans les settings, specifiez le nom de l'index ElasticSearch et utilisez la date de signature.

Importer, via l'onglet objects des settings le fichier *project/data/kibana/contrats.json*.

 
