# Migration de CONFIGURATION à l'inter-campagne

L'intercampagne (juillet/août) est l'occasion de faire des changements structurants dans la CONFIGURATION.

Voici les étapes auxquelles il faut penser :

## Duplication de la configuration

## Suppression du noeud Alias

## Renommage des noeuds de produits (et gestion de l'Alias)

## Ajout de nouveaux produits

## Changement des droits (CVO et Douane)

### Lister les droits existants

Une tache permet de lister les droits existants :

    php symfony configuration:list-droits --application=APP 2018-08-01 cvo

Cette tache produit une ligne par type de droit avec les colonnes suivantes :

 - taux
 - hash du taux
 - nombre de produits concernés

Une option ``with_produit`` permet d'avoir une ligne par produit :

 - taux
 - hash du taux
 - produit concerné

Il est ainsi possible de voir le détail des produits concernés par certains noeuds afin rechercher les optitimisations possibles :

    php symfony configuration:list-droits --application=APP --with_produit=true 2018-08-01 cvo | grep '^3.46;'

Ces options fonctionne aussi pour les droits douane : 

    php symfony configuration:list-droits --application=APP 2018-08-01 douane

### Ajouter des noeuds droits

### Nettoyer des noeuds non nessaires

## Changement des mouvements

## changement de favoris mouvements

## Changement du CURRENT
