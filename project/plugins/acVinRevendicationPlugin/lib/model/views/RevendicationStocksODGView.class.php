<?php
class RevendicationStocksODGView extends acCouchdbView
{
    const KEY_CAMPAGNE = 0;
    const KEY_ODG = 1;
    const KEY_SOCIETE_IDENTIFIANT = 2;
    const KEY_ETABLISSEMENT_IDENTIFIANT = 3;
    const KEY_PRODUIT_HASH = 4;
    const KEY_LIGNE_STATUT = 5;
    const KEY_LIGNE_IDENTIFIANT = 6;

    const VALUE_VOLUME = 0;
    const VALUE_DATE_INSERTION = 1;
    const VALUE_PRODUIT_LIBELLE = 2;
    const VALUE_DECLARANT_CVI = 3;
    const VALUE_DECLARANT_NOM = 4;


    public static function getInstance() {

        return acCouchdbManager::getView('revendication', 'stocks_odg', 'Revendication');
    }

    public function findByCampagneAndODG($campagne, $odg) {    

        return $this->builds(
                            $this->client->startkey(array($campagne, $odg))
                            ->endkey(array($campagne, $odg, array()))
                            ->getView($this->design, $this->view)->rows
                            );
    }

    public function findByCampagneAndEtablissement($campagne, Etablissement $etablissement) {    

        return $this->findByCampagneAndSocieteAndEtablissement($campagne, $etablissement->id_societe, $etablissement->identifiant);
    }

    public function findByCampagneAndSocieteAndEtablissement($campagne, $societe_identifiant, $etablissement) {    
        $revs = array();

        foreach($this->client->getODGs() as $odg => $odg_libelle) {
            $revs = array_merge($revs, $this->builds(
                            $this->client->startkey(array($campagne, $odg, $societe, $etablissement))
                                         ->endkey(array($campagne, $odg, $societe, $etablissement, array()))
                                         ->getView($this->design, $this->view)->rows
                            ));
        }

        return $revs;
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
        $rev->societe_identifiant = $row->key[self::KEY_SOCIETE_IDENTIFIANT];
        $rev->etablissement_identifiant = $row->key[self::KEY_ETABLISSEMENT_IDENTIFIANT];
        $rev->declarant_nom = $row->value[self::VALUE_DECLARANT_NOM];
        $rev->declarant_cvi = $row->value[self::VALUE_DECLARANT_CVI];
        $rev->campagne = $row->key[self::KEY_CAMPAGNE];
        $rev->odg = $row->key[self::KEY_ODG];
        $rev->statut = $row->key[self::KEY_LIGNE_STATUT];
        $rev->produit_hash = $row->key[self::KEY_PRODUIT_HASH];
        $rev->produit_libelle = $row->value[self::VALUE_PRODUIT_LIBELLE];
        $rev->ligne_identifiant = $row->key[self::KEY_LIGNE_IDENTIFIANT];
        $rev->date_insertion = $row->value[self::VALUE_DATE_INSERTION];
        $rev->volume = $row->value[self::VALUE_VOLUME];

        return $rev;
    }
}  