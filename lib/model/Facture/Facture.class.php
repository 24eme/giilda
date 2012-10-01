<?php

/**
 * Model for Facture
 *
 */
class Facture extends BaseFacture implements InterfaceDeclarantDocument {

    private $documents_origine = array();
    protected $declarant_document = null;

    public function __construct() {
        parent::__construct();
        $this->declarant_document = new DeclarantDocument($this);
    }

    public function storeEmetteur($emetteur) {
        $this->emetteur = $emetteur;
    }

    public function storeDatesCampagne($date_facturation, $campagne) {
        $this->date_emission = date('Y-m-d');
        $this->date_facturation = $date_facturation;
        if (!$this->date_facturation)
            $this->date_facturation = date('Y-m-d');
        $this->campagne = $campagne;
    }

    public function constructIds($identifiant) {
        $this->identifiant = $identifiant;
        $this->numero_facture = FactureClient::getInstance()->getNextNoFacture($this->identifiant, date('Ymd'));
        $this->_id = FactureClient::getInstance()->getId($this->identifiant, $this->numero_facture);
    }

    public function getDocumentsOrigine() {
        return $this->documents_origine;
    }

    public function getTaxe() {
        return $this->total_ttc - $this->total_ht;
    }

    public function save() {
        if ($this->isNew() && $this->total_ht > 0) {
            $this->facturerMouvements();
        }
        if (!$this->versement_comptable) {
            $this->versement_comptable = 0;
        }
        parent::save();
        $this->saveDocumentsOrigine();
    }

    public function saveDocumentsOrigine() {
        foreach ($this->getDocumentsOrigine() as $doc) {
            $doc->save();
        }
    }

    public function getDocumentOrigine($id) {
        if (!array_key_exists($id, $this->documents_origine)) {
            $this->documents_origine[$id] = acCouchdbManager::getClient()->find($id);
        }

        return $this->documents_origine[$id];
    }

    public function facturerMouvements() {
        foreach ($this->getLignes() as $l) {
            $l->facturerMouvements();
        }
    }

    public function defacturer() {
        foreach ($this->getLignes() as $ligne) {
            $ligne->defacturerMouvements();
        }
        $this->statut = FactureClient::STATUT_REDRESSEE;
    }

    public function isRedressee() {
        return ($this->statut == self::STATUT_REDRESSEE);
    }

    public function isRedressable() {
        return ($this->statut != self::STATUT_REDRESSEE && $this->montant_ht > 0);
    }

    public function getEcheances() {
        $e = $this->_get('echeances')->toArray();
        usort($e, 'Facture::triEcheanceDate');
        return $e;
    }

    public function getLignesArray() {
        $l = $this->_get('lignes')->toArray();
        usort($l, 'Facture::triOrigineDate');
        return $l;
    }

    static function triOrigineDate($ligne_0, $ligne_1) {
        return self::triDate("origine_date", $ligne_0, $ligne_1);
    }

    static function triEcheanceDate($ligne_0, $ligne_1) {
        return self::triDate("echeance_date", $ligne_0, $ligne_1);
    }

    static function triDate($champ, $ligne_0, $ligne_1) {
        if ($ligne_0->{$champ} == $ligne_1->{$champ}) {

            return 0;
        }
        return ($ligne_0->{$champ} > $ligne_1->{$champ}) ? -1 : +1;
    }

    public function storeLignes($mvts, $famille) {
        foreach ($mvts as $lignesByType) {
            $this->storeLigne($lignesByType, $famille);
        }
    }

    public function storeLigne($ligneByType, $famille) {        
        $ligne = $this->lignes->add($ligneByType->key[MouvementFacturationView::KEYS_MATIERE])->add();
        $ligne->cotisation_taux = $ligneByType->value[MouvementFacturationView::VALUE_CVO];
        $ligne->volume = $ligneByType->value[MouvementFacturationView::VALUE_VOLUME];        
        $ligne->origine_type = $ligneByType->key[MouvementFacturationView::KEYS_ORIGIN];
        $ligne->origine_identifiant = $ligneByType->value[MouvementFacturationView::VALUE_NUMERO];
        $ligne->contrat_identifiant = $ligneByType->key[MouvementFacturationView::KEYS_CONTRAT_ID];
        $ligne->origine_date = $ligneByType->key[MouvementFacturationView::KEYS_PERIODE];
        $ligne->produit_type = $ligneByType->key[MouvementFacturationView::KEYS_MATIERE];
        $ligne->produit_libelle = $ligneByType->value[MouvementFacturationView::VALUE_PRODUIT_LIBELLE];
        $ligne->produit_hash = $ligneByType->key[MouvementFacturationView::KEYS_PRODUIT_ID];
        $ligne->montant_ht = $ligne->cotisation_taux * $ligne->volume * -1;
        $ligne->origine_mouvements = $this->createLigneOriginesMouvements($ligneByType->value[MouvementFacturationView::VALUE_ID_ORIGINE]);
        $transacteur = $ligneByType->value[MouvementFacturationView::VALUE_VRAC_DEST];
        $ligne->origine_libelle = $this->createOrigineLibelle($ligne, $transacteur, $famille);
    }
    
    private function createLigneOriginesMouvements($originesTable) {
        $origines = array();
        foreach ($originesTable as $origineFormatted) {
            $origineKeyValue = explode(':', $origineFormatted);
            if(count($origineKeyValue)!=2) throw new Exception('Le mouvement est mal formé : %s',  print_r($origineKeyValue));
            $key = $origineKeyValue[0];
            $value = $origineKeyValue[1];
            if(!array_key_exists($key, $origines))
            {
                $origines[$key] = array();
            }
            $origines[$key][] = $value;            
        }
        return $origines;
    }
    
    private function createOrigineLibelle($ligne,$transacteur,$famille) {     
        if($ligne->origine_type == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV){
            $origine_libelle = 'Contrat du '.  VracClient::getInstance()->getLibelleContratNum($ligne->contrat_identifiant);
            $origine_libelle .= ' ('.$transacteur.') ';
            if($famille==EtablissementFamilles::FAMILLE_NEGOCIANT)
                $origine_libelle .= SV12Client::getInstance()->getLibelleFromId($ligne->origine_identifiant);
            return $origine_libelle;
        }
        
        if($ligne->origine_type == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_DRM){
            if($ligne->produit_type == FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS)
            {
                $origine_libelle = 'Contrat du '.VracClient::getInstance()->getLibelleContratNum($ligne->contrat_identifiant);
                $origine_libelle .= ' ('.$transacteur.') ';
                if($famille==EtablissementFamilles::FAMILLE_PRODUCTEUR)
                    $origine_libelle .= DRMClient::getInstance()->getLibelleFromId($ligne->origine_identifiant);
                return $origine_libelle;
            }
            return DRMClient::getInstance()->getLibelleFromId($ligne->origine_identifiant);
        }
    }

    public function storePapillons() {
        foreach ($this->lignes as $typeLignes) {
            foreach ($typeLignes as $ligne) {
                switch ($ligne->produit_type) {
                    case FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS:
                    case FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS:
                        if (strstr($ligne->produit_hash, 'mentions/SL/')) {
                            $this->createOrUpdateEcheanceC($ligne);
                        } else {
                            $this->createOrUpdateEcheanceB($ligne);
                        }
                        break;
                    default :
                        $this->createOrUpdateEcheanceA($ligne);
                        break;
                }
            }
        }
    }

    public function createOrUpdateEcheanceA($ligne) {
        $ligne->echeance_code = 'A';
        $this->updateEcheance('A', date('Y-m-d', strtotime("+60 days")), $ligne->montant_ht);
    }

    public function createOrUpdateEcheanceB($ligne) {
        $ligne->echeance_code = 'B';
        $date = date('Ymd');
        $d1 = date('Y', strtotime("-1 years")) . '0801'; // 01/08/N-1
        $d2 = date('Y') . '0331'; // 31/03/N
        $d3 = date('Y') . '0531'; // 31/05/N    
        //        
//          if(01/08/N-1 < date < 31/03/N) { 50% au 31/03 et 50% au 31/05 }            
        if (($d1 < $date) && ($date < $d2)) {
            $this->updateEcheance('B', date('Y').'-03-31', $ligne->montant_ht * 0.5);
            $this->updateEcheance('B', date('Y').'-05-31', $ligne->montant_ht * 0.5);
            return;
        }

//          if(01/04/N < date < 31/05/N)   { 50% comptant et  50% au 31/05 }              
        if (($d2 < $date) && ($date <= $d3)) {
            $this->updateEcheance('B', date('Y-m-d'), $ligne->montant_ht * 0.5);
            $this->updateEcheance('B', date('Y').'-05-31', $ligne->montant_ht * 0.5);
            return;
        }

//            if(date > 31/05/N) { 100% comptant } 
        if ($date > $d3) {
            $this->updateEcheance('B', date('Y-m-d'), $ligne->montant_ht);
            return;
        }
    }

    public function createOrUpdateEcheanceC($ligne) {
        $ligne->echeance_code = 'C';
        $date = date('Y') . '-09-30';
        $this->updateEcheance('C', $date, $ligne->montant_ht);
    }

    public function updateEcheance($echeance_code, $date, $montant_ht) {
        $Aexist = false;
        foreach ($this->echeances as $echeance) {
            if (($echeance->echeance_code == $echeance_code) && ($echeance->echeance_date == $date)) {
                $echeance->montant_ttc += $this->ttc($montant_ht);
                $Aexist = true;
                break;
            }
        }
        if (!$Aexist) {
            $echeance = new stdClass();
            $echeance->echeance_code = $echeance_code;
            $echeance->montant_ttc = $this->ttc($montant_ht);
            $echeance->echeance_date = $date;
            $this->add("echeances")->add(count($this->echeances), $echeance);
        }
    }
    
    public function storeOrigines() {
        foreach ($this->getLignes() as $lignesType) {
            foreach ($lignesType as $ligne) {
                if(!array_key_exists($ligne->origine_identifiant, $this->origines))
                        $this->origines->add($ligne->origine_identifiant,$ligne->origine_identifiant);
            }
        }
    }


    public function updateTotaux() {
        $this->updateTotalHT();
        $this->updateTotalTTC();
        $this->updateTotalTaxe();
    }


    public function updateTotalHT() {
        $this->total_ht = 0;
        foreach ($this->lignes as $typeLignes) {
            foreach ($typeLignes as $ligne) {                
                $this->total_ht += $ligne->montant_ht;
            }
        }        
    }
    
    public function updateTotalTTC() {
        $this->total_ttc = 0;
        foreach ($this->echeances as $echeance) {              
                $this->total_ttc += $echeance->montant_ttc;
        }        
    }
    
    public function updateTotalTaxe() {
        $this->total_taxe = $this->total_ttc - $this->total_ht;        
    }


    /****** DECLARANT *******/

    public function getEtablissementObject() {
        return $this->declarant_document->getEtablissementObject();
    }

    public function storeDeclarant() {
        $this->declarant_document->storeDeclarant();
    }
    
    private function ttc($p) {
        return $p + $p * 0.196;
    }

}
