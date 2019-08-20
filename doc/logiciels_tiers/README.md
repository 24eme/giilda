# Spécifications techniques de l'implémentation du format de DRM attendues sur le portail d'Interloire

La spécification complète du format d'import attendu est détaillée ici : [Spécification générique du fichier d'import DRM pour logiciels tiers](https://github.com/24eme/mutualisation-douane/blob/master/logiciels-tiers/). Cette documentation "générique" est commune pour les portails déclaratifs du CIVA, du CIVP, d'Interloire, d'InterRhone, d'IVBD, d'IVSO et d'IVSE.

Cette page apporte un éclairage Loire à la documentation générique. Elle permet d'accéder à la liste des produits aujourd'hui gérés par la plateforme d'InterLoire (cette liste peut évoluer en fonction des besoins des ressortissants, n'hésitez donc pas à les remonter), la manière de les déclarer, ainsi que les mouvements désirés pour la DRM Loire.

## Catalogue des produits spécifiques au portail d'Interloire

Le catalogue produit nécessaire aux imports de DRM pour Interloire est décrit dans le fichier suivant : [Catalogue produit](catalogue_produits.csv)

Ce fichier comporte les différentes colonnes suivantes :

1. La certification : la certification du produit (AOC, IGP ou Vins sans IG)
2. Le genre : Tanquille, Fines bulles ou Mousseux
3. L'appellation : Anjou, Touraine, Saumur...
4. La mention : Sur lie, Villages...
5. Le lieu : Clisson, Chenin...
6. La couleur : Rouge/Rosé/Blanc
7. Le cepage : Muscadet AC

La dernière colonne indique le libellé complet du produit, le processus d'import ne tiendra pas compte de ce champs si les 7 champs d'identification sont remplis. Il sera utilisé que si une ambiguité ressort de l'exploitation de ces champs.

Pour plus de détails sur l'exploitation de ces champs, voir la [section "identification du vin" de la Spécification générique DRM pour logiciels tiers](https://github.com/24eme/mutualisation-douane/blob/master/logiciels-tiers/#description-des-lignes-cave).

## Catalogue des mouvements de DRM spécifiques au portail d'Interloire

Le catalogue des mouvements de DRM admis par le portail d'Interloire  [Catalogue mouvements](catalogue_mouvements.csv) est composé de trois colonnes :

1. Le type de DRM : suspendu ou acquitte
2. La catégorie du mouvement : stocks_debut, stocks_fin, entrees ou sorties
3. Le type du mouvement : achatcrd, vrac, repli...

## Exemple complet de fichier d'import de DRM

Un exemple spécifique de DRM à importer pour le portail d'Interloire est disponible ici : [Exemple de fichier d'import pour Interloire](exemple_export_drm.csv) .

Ce fichier reprend l'ensemble des spécificités décrites ci-dessus.

## Suivi du projet chez les éditeurs de registres de cave 

| Nom de l'Éditeur | Prise de contact | Génération du fichier de transfer | Recette des échanges en préproduction | Transmission opérationnelle en production | Versions compatibles |
|------------------|------------------|-----------------------------------|---------------------------------------|------------------------------------------------------|----------------------|
| Isagri           | Oui | Oui | Oui | Oui  |  |
| Solumatic        | Oui |  |  |  |  |
| Dune Informatique| Oui |  |  |  |  |
| Cer France       | Oui |  |  |  |  |
| Vinosoft         | Oui | Oui | Oui | Oui |  |
| EBC         | Oui |  |  |  |  |
