#Installation de CAS

## Installation de LDAP

Vérifier, avec la commande ``hostname`` le nom attribué à la machine : ce nom sera celui utilisé par LDAP

Voici la commande qui vous permettra de le changer si vous le souhaitez :

    hostname mondomain.example.org

Installation de ldap et de ses outils :

    sudo aptitude install ldap-server ldap-utils

Pour créer l'arborescence LDAP nécessaire à l'hébergement des utilisateurs, il faut créer le fichier ``arbo.ldif`` suivant :

    dn: dc=ivso,dc=example,dc=org
    objectClass: top
    objectClass: dcObject
    objectClass: organization
    o: ivso.actualys.com
    dc: ivso
    
    dn: ou=People,dc=ivso,dc=example,dc=org
    objectClass: top
    objectClass: organizationalUnit
    ou:People
    
    dn: ou=Groups,dc=ivso,dc=example,dc=org
    objectClass: top
    objectClass: organizationalUnit
    ou:Groups


Puis inserrez ce fichier dans LDAP :

    ldapadd -x -h localhost -D cn=admin,dc=dc=example,dc=org -W -f /tmp/ldap.dif

## Mise en connexion du LDAP et du Symfony

Pour mettre en connexion le LDAP et le symfony, vous devez configuer le ldap dans ``config/app.yml`` et executer la tache ``bin/comptes_update_ldap.sh``

##Génération du CAS

    cd /tmp/
    git clone https://github.com/24eme/cas-gradle-ldap
    cd cas-gradle-ldap

Changer de branche pour adopter l'une des branches spécifique à l'interpro considérée (ivso ou ivbd) 

    git checkout ivso

Changer le fichier de configuration ``cas/src/main/webapp/WEB-INF/cas.properties`` pour adopter les éléments liés au LDAP que vous avez configuré

Il est possible maintenant de construire ``cas.war``

    ./gradlew clean build

##Installer CAS avec tomcat

    sudo aptitude install tomcat8
    cp /tmp/cas-gradle-overlay-template/cas/build/libs/cas.war /var/lib/tomcat8/webapps/cas_ivso.war


De même changer le nom du ``cas.war`` (``cas_ivso.war`` dans notre exemple) dans le repertoire ``/var/lib/tomcat8/webapps/`` 

Pour une meilleure prise en compte de tomcat par apache, activez ``ajp`` en décommantant les deux lignes suivante du fichier ````

    <!-- Define an AJP 1.3 Connector on port 8009 -->

    <Connector port="8009" protocol="AJP/1.3" redirectPort="8443" />
    
## Configurer Apache

Voici le virtualhost à ajouter à votre configuration apache pour qu'il dialogue en ``ajp`` avec tomcat

    <VirtualHost *:443>
        ServerName login.example.org
        Redirect /cas https://login.example.org/cas_ivso
        RedirectMatch /$ https://login.example.org/cas_ivso/
        SSLEngine On
        SSLCertificateFile      [...]
        SSLCertificateKeyFile   [...]
        SSLCertificateChainFile [...]
        ProxyRequests on
        ProxyPass /cas_ivso ajp://localhost:8009/cas_ivso
        ProxyPassReverse /cas_ivso ajp://localhost:8009/cas_ivso
    </VirtualHost>

N'oubliez pas d'activer ``proxy_ajp`` :

    sudo a2enmod proxy_ajp


