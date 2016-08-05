#Spécifications techniques de l'implémentation du format de DRM attendues sur le portail d'Interloire

La spécification complète du format d'import attendue est détaillée ici : [Spécification générique DRM logiciels tiers](https://github.com/24eme/mutualisation-douane/blob/master/logiciels-tiers/edi/speficication_technique.md) .

Cette documentation référence l'ensemble des lignes exportables dans le fichier csv, ainsi que leur interprétation au sein de l'applicatif d'Interloire.

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

Il est aussi possible d'utiliser la dernière colonne pour définir le produit grace à son libellé complet [Spécification générique DRM logiciels tiers, section : identification du vin](https://github.com/24eme/mutualisation-douane/blob/master/logiciels-tiers/edi/speficication_technique.md#description-des-lignes-cave) .

## Catalogue des mouvements de DRM spécifiques au portail d'Interloire

Le catalogue des mouvements de DRM admis par le portail d'Interloire  [Catalogue mouvements](catalogue_mouvements.csv) est composé de trois colonnes :

1. Le type de DRM : suspendu ou acquitte
2. La catégorie du mouvement : stocks_debut, stocks_fin, entrees ou sorties
3. Le type du mouvement : achatcrd, vrac, repli...

## Exemple complet de fichier d'import de DRM

Un exemple spécifique de DRM à importer pour le portail d'Interloire est disponible ici : [Exemple de fichier d'import pour Interloire](exemple_export_drm.csv) .

Ce fichier reprend l'ensemble des spécificités décrites ci-dessus.
