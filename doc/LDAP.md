# Préconfiguration

Vérifier que le fichier /etc/hostname contient le domaine qu'on souhaite utiliser :

$ cat /etc/hostname
nommachine.example.org

Si ce n'est pas le cas, l'éditer, vérifier qu'il est résolvable via le fichier /etc/hosts et lancer la commande suivante :

$ /etc/init.d/hostname.sh

# Installation d'OpenLdap

$ sudo aptitude install slapd ldap-utils

Le serveur LDAP sera alors installé pour le domaine indiqué dans le fichier /etc/hostname (example.org dans notre exemple)

Un compte d'amdministrateur sera également créé avec le mot de passe indiqué lors de l'installation. Il sera accessible via le DN cn=admin,dc=example,dc=org

# Création de l'arbo

Créer un fichier arbo.ldif contenant les informations suivante :
<code>
dn: ou=People,dc=example,dc=org
objectClass: top
objectClass: organizationalUnit
ou:People

dn: ou=Groups,dc=example,dc=org
objectClass: top
objectClass: organizationalUnit
ou:Groups

dn: cn=transaction,ou=Groups,dc=example,dc=org
cn: transaction
gidNumber: 500
objectClass: posixGroup

dn: cn=contact,ou=Groups,dc=example,dc=org
cn: contact
gidNumber: 501
objectClass: posixGroup

dn: cn=test,ou=People,dc=example,dc=org
sn: test
cn: test
uid: test
uidNumber: 1001
gidNumber: 500
homeDirectory: /home/users/test
objectClass: inetOrgPerson
objectClass: posixAccount

</code>

En veillant à remplacer les dc=example,dc=org par le domaine choisi.

Puis executer la commande suivante pour inserrer ces éléments dans LDAP :

$ ldapadd -x -h localhost -D "cn=admin,dc=example,dc=org" -W -f arbo.ldif


