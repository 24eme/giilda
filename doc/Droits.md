# Gestions des droits

Dans VINSI, chaque utilisateur interne se voit attribué un role via le champs *description* de l'annuaire interne. Ces roles ouvrent le droit d'accès à certaines parties de l'application ainsi que des droits de modification de certaines information.

##Les roles et les droits attribués

Il existe 8 roles distincts :

 - **admin** : role ouvrant tous les droits y compris ceux lié à la configuration de l'arbre produit et la configuration CVO.
 - **transactions** : role ouvrant l'accès à toutes les fonctionnalités sauf la configuration. Pour les contacts, ce rôle ne permet **pas** de **modifier** les comptes *presse*, *syndicat* et *institution*
 - **compta** : role permettant d'accéder qu'aux contacts. Les utilisateurs ayant ce role ne peuvent **pas modifier** les contacts *presse*, *syndicat* et *institution*.
 - **presse** : role permettant d'accéder et modifier des contacts. Ne permet pas de modifier les contacts tagués *transactions*, *institution* et *syndicat*. Des informations liées à la facturation et la transaction ne leur sont pas accessible.
 - **direction** : role permettant d'accéder et modifier des contacts. Ne permet pas de modifier les contacts tagués *transactions*, *syndicats* et  *presse*. Des informations liées à la facturation et la transaction ne leur sont pas accessibles.
 - **bureau** : role permettant d'accéder en lecture aux contacts. **Seuls** les contacts *syndicat* leur sont modifiables.
 - **autre** : role permettant d'accéder en lecture seul aux contacts.
 - **DRM** : role permettant seulement l'édition des DRM.

##Les comptes de test

Des comptes de tests ont été créé permettant d'accéder à l'application avec des roles particuliers :

 - **user1** : compte ayant le role *admin*
 - **user2** : compte ayant le role *presse*
 - **user3** : compte ayant le role *compta*
 - **user4** : compte ayant le role *transactions*
 - **user5** : compte ayant le role *direction*
 - **user6** : compte ayant le role *autre*
 - **user7** : compte ayant le role *bureau*
 - **user8** : compte ayant le role *DRM*

##Implémentation

La majorité des fonctionnalités liées aux roles ont été centralisées dans deux classes :

 - [security/Roles.class.php](https://github.com/24eme/vinsdeloire/blob/prod/project/lib/security/Roles.class.php)
 - [sfCredentialActions.class.php](https://github.com/24eme/vinsdeloire/blob/prod/project/lib/sfCredentialActions.class.php#L95)

La classe *Roles* est utilisées pour ouvrir des onglets et les routes de l'application. La classe *sfCerdentialActions* permet de gérer la lecture/écriture et le masquage de certaines informations des contacts.
