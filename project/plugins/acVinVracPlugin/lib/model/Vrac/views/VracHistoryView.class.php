<?php
class VracHistoryView extends acCouchdbView 
{
	const KEY_TELEDECLARE = 0;
	const KEY_DATE_SAISIE = 1;
	const KEY_ID = 2;
	
	const VALUE_CAMPAGNE = 0;
	const VALUE_STATUT = 1;
	const VALUE_ID = 2;
	const VALUE_NUMERO = 3;
	const VALUE_ARCHIVE = 4;
	const VALUE_ACHETEUR_ID = 5;
	const VALUE_ACHETEUR_NOM = 6;
	const VALUE_VENDEUR_ID = 7;
	const VALUE_VENDEUR_NOM = 8;
	const VALUE_MANDATAIRE_ID = 9;
	const VALUE_MANDATAIRE_NOM = 10;
	const VALUE_TYPE = 11;
	const VALUE_PRODUIT = 12;
	const VALUE_PRODUITLIBELLE = 13;
	const VALUE_VOLUME_PROPOSE = 14;
	const VALUE_VOLUME_ENLEVE = 15;
	const VALUE_PRIX_UNITAIRE_INITIAL = 16;
	const VALUE_PRIX_UNITAIRE = 17;
	const VALUE_PRIX_VARIABLE = 18;
	const VALUE_INTERNE = 19;
	const VALUE_ORIGINAL = 20;
	const VALUE_DOUBLONTYPE = 21;
	const VALUE_DATE_SIGNATURE = 22;
	const VALUE_DATE_CAMPAGNE = 23;
	const VALUE_DATE_SAISIE = 24;
	const VALUE_MILLESIME = 25;
	const VALUE_CATEGORIE_VIN = 26;
	const VALUE_DOMAINE = 27;
	const VALUE_PART_VARIABLE = 28;
	const VALUE_CVO_REPARTITION = 29;
	const VALUE_CVO_NATURE = 30;
	const VALUE_CEPAGE = 31;
	const VALUE_CEPAGELIBELLE = 32;

    public static function getInstance() 
    {
        return acCouchdbManager::getView('vrac', 'history','Vrac');
    }
	
}