Premières recherche autour d'elasticsearch

# Installation sous debian

<code>
$ wget http://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-0.20.2.deb
$ sudo dpkg -i elasticsearch-0.20.2.deb
</code>

Vérifier que 0.19.11 est bien la dernière version depuis https://github.com/elasticsearch/elasticsearch/downloads

# Exemple avec une base contact

## Création d'objet

On crée un objet json représentant un contact :

<code>
$ cat > /tmp/contact.json
{
"id": "CONTACT-1234",
"nom": "Adam",
"prenom": "Jean-Baptiste",
"adresse": "12 rue saint jean",
"code_postal": "75001",
"ville": "Paris",
"tags": ["presse", "vigneron", "commerce"],
"stock": 
  {
     "anjou_rouge": 50,
     "montlouis": 10
  }
}
$ curl -XPUT -d "@/tmp/contact.json" http://localhost:9200/base/contact/CONTACT-123
</code>

L'objet CONTACT-123 est maintenant dans la table "contact" de la base "base"

## Recherche

il est maintenant possible de le chercher "adam":

<code>
$ curl 'http://localhost:9200/contacts/contact/_search?q=adam'
</code>

pour avoir une plus jolie sortie :
<code>
$ curl 'http://localhost:9200/contacts/contact/_search?q=adam&pretty=true'
</code>
pour recherche dans un champ particulier on utilise : pour séparer le champ de sa valeur comme ici pour sélectionner le tags "presse"
<code>
$ curl 'http://localhost:9200/contacts/contact/_search?q=tags:presse&pretty=true'
</code>
une recherche par defaut utilise l'opérateur OR, pour une recherche avec AND, on peut l'indiquer entre chaque terme :
<code>
$ curl 'http://localhost:9200/contacts/contact/_search?q=tags:presse+AND+adam&pretty=true'
</code>
ou changer l'opérateur par défaut :
<code>
$ curl 'http://localhost:9200/contacts/contact/_search?q=tags:presse+adam&default_operator=AND&pretty=true'
</code>

pour la recherche dans des champs fils, on utilise le . :
<code>
$ curl 'http://localhost:9200/contacts/contact/_search?q=stock.anjou_rouge:50&pretty=true'
</code>

il est possible de recherche dans un interval de valeur grace à la notation [XXX TO YYYY] (attention curl n'encode pas correctement ces caractères, il faut le faire soit même :
<code>
$ curl 'http://localhost:9200/contacts/contact/_search?q=stock.anjou_rouge:%5B0%20TO%2010%5D&pretty=true'
</code>

il est possible de réaliser ces recherche à partir d'information postée au serveur :
<code>
$ cat > /tmp/search.json
{"query": {"range": { "stock.montlouis": {"gt": "9"}}}}
$ curl 'http://localhost:9200/contacts/contact/_search?pretty=true' -d '@/tmp/search.json'
</code>

## Facettes

pour créer des facettes, il faut en demander la création dans la phase de recherche :
<code>
$ cat > /tmp/tags.json
{"facets":{"tags":{"terms":{"field":"tags"}}}}
$ curl 'http://localhost:9200/contacts/contact/_search?pretty=true' -d '@/tmp/tags.json'
</code>

il est possible des faire des facettes sur des ranges : http://www.elasticsearch.org/guide/reference/api/search/facets/range-facet.html ou des stats http://www.elasticsearch.org/guide/reference/api/search/facets/statistical-facet.html

# Connexion avec CouchDB

Un plugin permet de connecter ElasticSearch et CouchDB : river-couchdb

## Installation et configuration de river-couchdb

L'installation du plugin peut se réaliser via la commande suivante :

<code>
$ sudo /usr/share/elasticsearch/bin/plugin -install elasticsearch/elasticsearch-river-couchdb/1.1.0
</code>

Une fois, le plugin installé, il faut redémarer ElasticSearch (sinon une exception "_NoClassSettingsException[Failed to load class with value [couchdb]]_" sera générée)

<code>
$ sudo service elasticsearch restart
</code>

Il faut ensuite connecter ElasticSearch avec CouchDB à l'aide de la commande suivante :

<code>
$ curl -X PUT 'http://localhost:9200/_river/vinsdeloire_full/_meta' -d '{
    "type" : "couchdb",
    "couchdb" : {
        "host" : "localhost",
        "port" : 5984,
        "db" : "vinsdeloire",
        "filter" : null
    },
    "index" : {
        "index" : "vinsdeloire",
        "type" : "full",
        "bulk_size" : "100",
        "bulk_timeout" : "10ms"
    }
}'
</code>

Une copie de la base couchdb maintenant accessible depuis http://localhost:5984/<index>/<type>. Pour chercher dans notre exemple, on peut donc executer :

<code>
$ curl  'http://localhost:9200/vinsdeloire/full/_search?pretty=true&q=societe'
</code>

et pour accéder à un document précis :

<code>
curl 'http://localhost:9200/vinsdeloire/full/COMPTE-51335901'
</code>

##Export partiel d'une base couchdb

Couchdb permet de conditionner les informations sur les changements via un filter. Imaginons que nous souhaitons avoir une base elasticSearch dédiée à un seul type de document (passé en paramètre).

### Création d'un filtre couchdb

On devra donc créer un filtre sur la base couchdb pour permettre d'être informé des seules modification sur un document dont l'attribut type sera passé en paramètre :
<code>
$ cat /tmp/filter.json
{
  "_id": "_design/app",
  "filters": {
    "type": "function(doc, req) { if(doc.type == req.query.type) { return true; } else { return false; }}"
  }
}
$ curl -X PUT -d "@/tmp/filter.json" http://localhost:5984/vinsdeloire/_design/app
</code>

Une fois ce filtre __type__ créé, couchdb peut nous notifier sur les seules modification des documents ayant pour type, le type passé en argument :

<code>
$ curl 'http://localhost:5984/vinsdeloire/_changes?filter=app/type&type=Contact'
</code>

### Configuration de la connexion CouchDB <-> ElasticSearch

<code>
$ curl -X PUT 'http://localhost:9200/_river/vinsdeloire_compte/_meta' -d '{
    "type" : "couchdb",
    "couchdb" : {
        "host" : "localhost",
        "port" : 5984,
        "db" : "vinsdeloire",
        "filter" : "app/type",
        "filter_params" : {
            "type" : "Compte"
	}
    },
    "index" : {
        "index" : "vinsdeloire",
        "type" : "compte",
        "bulk_size" : "100",
        "bulk_timeout" : "10ms"
    }
}'
</code>

Pour brancher plusieurs synchros sur la même base Elastic, il faut indiquer des noms différents dans l'url de création de la connexion (_vinsdeloire_compte_ dans notre dernier exemple).