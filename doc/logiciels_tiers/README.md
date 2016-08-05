#Spécifications techniques de l'implémentation du format de DRM pour les logiciels tiers attendues sur le portail d'Interloire

La spécification complète du format d'import attendue est détaillée ici : [1] .
Cette documentation référence l'ensemble des ligne exportable dans le fichier .csv, ainsi que leur interprétation au sein de l'applicatif d'Interloire.

## Catalogue des produits spécifiques au portail d'Interloire

Le catalogue produit nécessaire aux imports de DRM pour Interloire est décrit dans le fichier suivant : [2]
Ce fichier comporte les différentes colonnes suivantes :

La certification : la certification du produit (AOC, IGP ou Vins sans IG)
Le genre : Tanquille, Fines bulles ou Mousseux
L'appellation : Anjou, Touraine, Saumur...
La mention : Sur lie, Villages...
Le lieu : Clisson, Chenin...
La couleur : Rouge/Rosé/Blanc
Le cepage : Muscadet AC

Ces colonnes permettent de définir le produit à importer comme ici :

Il est aussi possible d'utiliser le dernière colonne pour définir le produit grace à son libellé complet [3] .

## Catalogue des mouvements de DRM spécifiques au portail d'Interloire

Le catalogue des mouvements de DRM admis par le portail d'Interloire [3] est composé de trois colonnes :

Le type de DRM : suspendu ou acquitte
La catégorie du mouvement : stocks_debut, stocks_fin, entrees ou sorties
Le type du mouvement : achatcrd, vrac, repli...

## Exemple complet de fichier d'import de DRM

Un exemple spécifique de DRM à importer pour le portail d'Interloire est disponible ici : [4] .
Elle reprend l'ensemble des spécificités décrites ci-dessus.

[1]: https://jasig.github.io/cas/4.0.x/index.html
[2]: https://jasig.github.io/cas/4.0.x/index.html
[3]: https://jasig.github.io/cas/4.0.x/index.html
[4]: https://jasig.github.io/cas/4.0.x/index.html
