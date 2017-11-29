<?php

class RevendicationClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("Revendication");
    }

    public function getId($odg, $campagne) {
      if (!preg_match('/[0-9]{4}-[0-9]{4}/', $campagne)) {
	throw new sfException("Wrong campagne format ($campagne)");
      }
      return 'REVENDICATION-' . strtoupper($odg) . '-' . $campagne;
    }

    public function findByOdgAndCampagne($odg, $campagne, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->find($this->getId($odg, $campagne), $hydrate);
    }

    public function getVolumeProduitObj($revendication, $cvi, $row) {
        $result = new stdClass();
        $result->produit = $revendication->getProduitNode($cvi, $row);
        $result->volume = $produit->volumes->get($row);
        return $result;
    }

    public function createOrFind($odg, $campagne) {
        $revendication = $this->find($this->getId($odg, $campagne));

        if (!$revendication) {
            $revendication = new Revendication();
            $revendication->campagne = $campagne;
            $revendication->odg = $odg;
            $revendication->_id = $this->getId($odg, $campagne);
            $revendication->date_creation = date('Y-m-d');
        }

        return $revendication;
    }

    public function getHistory($limit = 10) {
        return array_reverse(RevendicationHistoryView::getInstance()->getHistory($limit));
    }

    public function getRevendicationLibelle($id) {
        $params = $this->getParametersFromId($id);
        return 'Revendication de ' . $params['campagne'] . ' (' . $params['odg'] . ')';
    }

    public function getParametersFromId($id) {
      if (preg_match('/^REVENDICATION-([A-Z_]*)-([0-9]{4}-[0-9]{4})$/', $id, $matches)) {
        return array('odg' => $matches[1], 'campagne' => $matches[2]);
      }
      throw new sfException("$id is not a revendication ID");
    }

    public function getODGs() {
        return EtablissementClient::getRegionsWithoutHorsInterLoire(true);
    }

    public function deleteRow($revendication, $identifiant, $produit, $row) {
        if (!isset($revendication->datas->$identifiant))
            throw new sfException("Le noeud d'identifiant $identifiant n'existe pas dans la revendication");
        $produitNode = $this->getProduitNode($revendication, $identifiant, $produit);
        if (!$produitNode)
            throw new sfException("Le noeud produit d'identifiant $identifiant et de produit $produit n'existe pas dans la revendication");
        if (!$produitNode->volumes->$row)
            throw new sfException("La ligne $row n'existe pas pour le produit $produit et l'etablissement $identifiant");
        $produitNode->volumes->$row->statut = RevendicationProduits::STATUT_SUPPRIME;
        $this->storeDoc($revendication);
    }

    public function getProduitNode($revendication, $identifiant, $produit) {
        if(!isset($revendication->datas->$identifiant->produits->$produit))
            return null;
        return $revendication->datas->$identifiant->produits->$produit;
    }


    public function deleteRevendication($revendication){
        $this->delete($revendication);
    }

    public function save($document = null) {
      if (!preg_match('/[0-9]{4}-[0-9]{4}/', $this->campagne)) {
	throw new sfException("Wrong campagne format (".$this->campagne.")");
      }
      return parent::save();
    }

    public function addVolumeSaisiByStdClass(stdClass $rev, $etablissement_id_or_identifiant, $produit_hash, $volume, $date) {
        $etablissement_identifiant = EtablissementClient::getInstance()->getIdentifiant($etablissement_id_or_identifiant);
        $rev_obj = new Revendication();
	$rev_obj->campagne = $rev->campagne;
        $rev_obj->datas->add($etablissement_identifiant, isset($rev->datas->$etablissement_identifiant) ? $rev->datas->$etablissement_identifiant : null);
        $rev_obj->addVolumeSaisi($etablissement_identifiant, $produit_hash, $volume, $date);
        $rev->datas->$etablissement_identifiant = $rev_obj->datas->get($etablissement_identifiant)->toJson();

        return $rev;
    }

    public function listCampagneByEtablissementId($identifiant) {
      $rows = RevendicationEtablissementView::getInstance()->getViewByEtablissement($identifiant, $this->reduce(true)->group_level(2))->rows;
      $current = ConfigurationClient::getInstance()->getCurrentCampagne();
      $list = array($current => $current);
      foreach($rows as $r) {
    $c = $r->key[RevendicationEtablissementView::KEY_CAMPAGNE];
    $list[$c] = $c;
      }
      krsort($list);
      return $list;
    }

    public function getCampagneFromRowDate($date){
      $annee = substr($date,0,4);
      $mois = substr($date,4,2);
      return ($mois<8)? ($annee-1).'-'.$annee : $annee.'-'.($annee+1);
    }


    public static function getCsvImportedRows($revendication){
        $result ="\xef\xbb\xbf";
        foreach ($revendication->datas as $etb) {
            foreach ($etb->produits as $prod) {
                foreach ($prod->volumes as $prod) {
                $result .= str_replace('#', ';', $prod->ligne)."\n";
                }
            }
        }
        $result = substr($result, 0, strlen($result)-1);
        return $result;
    }

}
