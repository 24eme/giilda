<?php
class RevendicationEtablissementView extends acCouchdbView
{
    const KEY_ETABLISSEMENT_IDENTIFIANT = 0;
    const KEY_CAMPAGNE = 1;
    const KEY_ODG = 2; 
    const KEY_PRODUIT_HASH = 3;
    const KEY_LIGNE_STATUT = 4;
    const KEY_LIGNE_IDENTIFIANT = 5;
    const KEY_LIGNE_CODE_DOUANE = 6;

    const VALUE_VOLUME = 0;
    const VALUE_DATE_INSERTION = 1;
    const VALUE_PRODUIT_LIBELLE_ODG = 2;
    const VALUE_DECLARANT_CVI = 3;
    const VALUE_DECLARANT_NOM = 4;
    const VALUE_DECLARANT_COMMUNE = 5;
    const VALUE_DATE_TRAITEMENT = 6;
    const VALUE_BAILLEUR_IDENTIFIANT = 7;
    const VALUE_BAILLEUR_NOM = 8;    
    const VALUE_PRODUIT_LIBELLE = 9;
    
    public static function getInstance() {

        return acCouchdbManager::getView('revendication', 'etablissement', 'Revendication');
    }

    public function findByEtablissementAndCampagne($identifiant, $campagne) {    

        return $this->builds($this->getViewByEtablissementAndCampagne($identifiant, $campagne, $this->client->reduce(false))->rows);
    }

    public function getOdgByEtablissementAndCampagne($identifiant, $campagne) {

        $rows = $this->getViewByEtablissementAndCampagne($identifiant, $campagne, $this->client->reduce(true))->rows;
    
        foreach($rows as $row) {

            return $row->key[self::KEY_ODG];
        }

        return null;
    }

    public function getViewByEtablissement($identifiant, $query = null) {

        if(!$query) {
            $query = $this->client;
        }

        return $query->startkey(array($identifiant))
                     ->endkey(array($identifiant, array()))
                     ->getView($this->design, $this->view);
    }

    public function getViewByEtablissementAndCampagne($identifiant, $campagne, $query = null) {

        if(!$query) {
            $query = $this->client;
        }

        return $query->startkey(array($identifiant, $campagne))
                     ->endkey(array($identifiant, $campagne, array()))
                     ->getView($this->design, $this->view);
    }


    public function builds($rows) {
        $revs = array();

        foreach($rows as $row) {
            $revs[] = $this->build($row);
        }

        return $revs;
    }

    public function build($row) {
        $rev = new stdClass();
        $rev->id = $row->id;
        $rev->etablissement_identifiant = $row->key[self::KEY_ETABLISSEMENT_IDENTIFIANT];
        $rev->declarant_nom = $row->value[self::VALUE_DECLARANT_NOM];
        $rev->declarant_cvi = $row->value[self::VALUE_DECLARANT_CVI];
        $rev->campagne = $row->key[self::KEY_CAMPAGNE];
        $rev->odg = $row->key[self::KEY_ODG];
        $rev->statut = $row->key[self::KEY_LIGNE_STATUT];
        $rev->produit_hash = $row->key[self::KEY_PRODUIT_HASH];
        $rev->produit_libelle_odg = $row->value[self::VALUE_PRODUIT_LIBELLE_ODG];        
        $rev->produit_libelle = $row->value[self::VALUE_PRODUIT_LIBELLE];
        $rev->ligne_identifiant = $row->key[self::KEY_LIGNE_IDENTIFIANT];
        $rev->date_insertion = $row->value[self::VALUE_DATE_INSERTION];
        $rev->volume = $row->value[self::VALUE_VOLUME];
        $rev->num_certif = $row->key[self::KEY_LIGNE_IDENTIFIANT];
        $date_traitement = $row->value[self::VALUE_DATE_TRAITEMENT];        
        if($date_traitement)
            $rev->date_traitement = substr($date_traitement, 0,4).'-'.substr($date_traitement, 4,2).'-'.substr($date_traitement, 6);
        $rev->code_douane = $row->key[self::KEY_LIGNE_CODE_DOUANE];
        $rev->bailleur_identifiant = $row->value[self::VALUE_BAILLEUR_IDENTIFIANT];
        $rev->bailleur_nom = $row->value[self::VALUE_BAILLEUR_NOM];
        return $rev;
    }
}  