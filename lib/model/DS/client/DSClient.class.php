<?php

class DSClient extends acCouchdbClient {

    const STATUT_VALIDE = 'valide';
    const STATUT_A_SAISIR = 'a_saisir';

    public static function getInstance() {
        return acCouchdbManager::getClient("DS");
    }

    public function buildId($identifiant, $periode) {
        return sprintf('DS-%s-%s', $identifiant, $periode);
    }

    public function buildPeriode($date) {
        preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $date, $matches);
        
        return sprintf('%d%02d', $matches[1], $matches[2]);
    }

    public function buildDate($periode) {

        return sprintf('%4d-%02d-%02d', $this->getAnnee($periode), $this->getMois($periode), date("t", $this->getMois($periode)));
    }

    public function buildCampagne($periode) {

        return ConfigurationClient::getInstance()->buildCampagne($this->buildDate($periode));
    }

    public function getAnnee($periode) {

        return preg_replace('/([0-9]{4})([0-9]{2})/', '$1', $periode);
    }

    public function getMois($periode) {

        return preg_replace('/([0-9]{4})([0-9]{2})/', '$2', $periode);
    }
    
    public function createDateStock($date_stock) {
	if (preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date_stock)) {
		return $date_stock;
        }
        $v = date_create_from_format('d/m/Y',$date_stock);
	if (!$v) {
		throw new sfException("Unexepected date format for $date_stock");
	}
        return $v->format('Y-m-d');
    }

    public function createDsByEtb($etablissement, $date_stock) {
        return $this->createDsByEtbId($etablissement->identifiant,$date_stock);
    }

    public function createDsByEtbId($etablissementId, $date_stock) {
        $ds = new DS();
        $ds->date_emission = date('Y-m-d');
        $ds->date_stock = $this->createDateStock($date_stock);
        $ds->identifiant = $etablissementId;
        $ds->storeDeclarant();
        $ds->updateProduits();
        return $ds;
    }

    public function createOrFind($etablissementId, $date_stock) {
        $ds = $this->findByIdentifiantAndPeriode($etablissementId, $this->buildPeriode($this->createDateStock($date_stock)));

        if(!$ds) {
            return $this->createDsByEtbId($etablissementId, $date_stock);
        }

        return $ds;
    }

    public function getHistoryByOperateur($etablissement) {

        return DSHistoryView::getInstance()->findByEtablissement($etablissement->identifiant);
    }

    public function findByIdentifiantAndPeriode($identifiant, $periode) {

        return $this->find($this->buildId($identifiant, $periode));
    }

    public function findLastByIdentifiant($identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
		$result = DSHistoryView::getInstance()->findByEtablissement($identifiant);
		$tabDs = array();
        foreach($result as $id => $ds) {
        	$tabDs[$ds->key[DSHistoryView::KEY_PERIODE]] = $ds->id;
        }
        krsort($tabDs);
		if (count($tabDs) > 0) {
			reset($tabDs);
			return $this->find(current($tabDs), $hydrate);
		}
        return null;
    }

    public function getLinkLibelleForHistory($statut) {
        if ($statut == self::STATUT_A_SAISIR)
            return '> Démarrer la saisie';
        if ($statut == self::STATUT_VALIDE)
            return '> Consulter';
        return '';
    }

    public function getLibelleStatutForHistory($statut) {
        if ($statut == self::STATUT_A_SAISIR)
            return 'A saisir';
        if ($statut == self::STATUT_VALIDE)
            return 'Validé';
        return '';
    }

    public function createGenerationForAllEtablissements($etablissements, $campagne, $date_declaration) {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $generation = new Generation();
        $generation->date_emission = date('Y-m-d-H:i');
        $generation->type_document = GenerationClient::TYPE_DOCUMENT_DS;
        $generation->documents = array();
        $generation->somme = 0;
        $cpt = 0;

        foreach ($etablissements as $etablissementView) {
            $declarationDs = $this->createDsByEtbId($etablissementView->key[EtablissementAllView::KEY_IDENTIFIANT], $campagne);
            $declarationDs->save();
            $generation->add('documents')->add($cpt, $declarationDs->_id);
            $cpt++;
        }
        return $generation;
    }

}
