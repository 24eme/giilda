# Installation d'ElasticSearch sous debian dans le cadre du projet VINSI

##Installation du package 

Pour installer le package *elasticsearch* supporté par debian :

        $ sudo aptitude install elasticsearch

##Activation du service elasticsearch

Pour que le service soit activable depuis les commande *service* ou */etc/init.d*, il faut le spécifier dans le fichier */etc/default/elasticsearch* en décommantant la directive *START_DAEMON* :

        # Start Elasticsearch automatically
        START_DAEMON=true

##Restriction des interfaces réseaux

Par mesure de sécurité, il est préférable de ne brancher elasticsearch que sur 127.0.0.1. Pour se faire, décommantez la directive *network.host* et indiquez lui la valeur *127.0.0.1* dans le fichier */etc/elasticsearch/elasticsearch.yml* :

       ############################## Network And HTTP ###############################
       
       # Elasticsearch, by default, binds itself to the 0.0.0.0 address, and listens
       # on port [9200-9300] for HTTP traffic and on port [9300-9400] for node-to-node
       # communication. (the range means that if the port is busy, it will automatically
       # try the next port).
       
       # Set the bind address specifically (IPv4 or IPv6):
       #
       # network.bind_host: 192.168.0.1
       
       # Set the address other nodes will use to communicate with this node. If not
       # set, it is automatically derived. It must point to an actual IP address.
       #
       # network.publish_host: 192.168.0.1
       
       # Set both 'bind_host' and 'publish_host':
       #
       network.host: 127.0.0.1

##Démarrage du service elasticsearch


       $ sudo service elasticsearch restart

Pour vérifier qu'elasticsearch fonctionnne vous pouvez tester l'existance de processus executés par l'utilisateur *elasticsearch* :

       $ ps aux | grep elastic
       elastic+ 29321  1.6  1.3 3488884 212404 ?      Sl   18:35   0:11 /usr/lib/jvm/java-7-openjdk-amd64//bin/java -Xms256m -Xmx1g ...

Et que le port 9200 est bien en écoute :

       $ sudo netstat -atpn | grep 9200
       tcp6       0      0 127.0.0.1:9200          :::*                    LISTEN      29321/java      

##Installation du plugin river-couchdb

Le plugin *river couchdb* doit être installé pour la bonne version de votre elasticsearch. Vous trouverez la correspondance des versions du plugin compatibles avec votre elasticsearch sur le site du plugin : [https://github.com/elastic/elasticsearch-river-couchdb]

Pour connaitre la version de votre elasticsearch :

       $ /usr/share/elasticsearch/bin/elasticsearch -v
       Version: 1.0.3, Build: NA/NA, JVM: 1.7.0_91

Appelons, la version compatible X.X.X. Pour installer ce plugin, vous executez la commande suivante :

       $ sudo /usr/share/elasticsearch/bin/plugin -install elasticsearch/elasticsearch-river-couchdb/X.X.X

##Installation du plugin head

Le plugin head permet d'avoir une interface d'administration de la base elasticsearch. Pour l'installer, executez la commande suivante :

       $ sudo /usr/share/elasticsearch/bin/plugin -install mobz/elasticsearch-head

##Redémarrer elasticsearch

Pour que les installations de plugins soient effectives, il faut redémarrer elasticsearch :

       $ sudo /etc/init.d/elasticsearch restart

Une fois redémarré, le plugin head vous sera accessible à l'adresse suivante : [http://127.0.0.1/_plugin/head/]

##Réduire le nombre de réplicas

Par défaut, elasticsearch est prévu pour fonctionner sur plusieurs noeuds. Ce n'est pas notre besoin pour ce projet. Pour désactiver cette fonctionnalité, il faut executer la commande suivante :

       $ curl -XPUT "http://127.0.0.1:9200/_settings" -d'{"number_of_replicas" : 0}'

Une fois cette opération réalisée, elasticsearch devrait apparaitre avec un status (*cluster health*) vert (*green*)

# Détail du fonctionnement de la connexion avec CouchDB

Un plugin permet de connecter ElasticSearch et CouchDB : river-couchdb

## Installation et configuration de river-couchdb

L'installation du plugin peut se réaliser via la commande suivante :

<code>
$ sudo /usr/share/elasticsearch/bin/plugin -install elasticsearch/elasticsearch-river-couchdb/X.X.X
</code>

Changer X.X.X suivant le tableau indiqué dans la doc de la river [https://github.com/elastic/elasticsearch-river-couchdb]

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

# Exploration du fonctionnement d'ElasticSearch

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

