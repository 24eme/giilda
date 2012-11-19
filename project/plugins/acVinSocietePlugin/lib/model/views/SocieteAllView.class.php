<?php

class SocieteAllView extends acCouchdbView
{
	const KEY_INTERPRO_ID = 0;
	const KEY_ID = 1;
	const KEY_IDENTIFIANT = 2;
	const KEY_RAISON_SOCIALE = 3;
	const KEY_SIRET = 4;
	const KEY_COMMUNE = 5;
	const KEY_CODE_POSTAL = 6;

    public static function getInstance() {
        return acCouchdbManager::getView('societe', 'all', 'Societe');
    }

    public function findByInterpro($interpro) {

    	return $this->client->startkey(array($interpro))
                    		->endkey(array($interpro, array()))
                    		->getView($this->design, $this->view);
    }

    public function findBySociete($identifiant) {
        $societe = $this->client->find($identifiant, acCouchdbClient::HYDRATE_JSON);

        if(!$societe) {
            return null;
        }

        return $this->client->startkey(array($societe->interpro, $societe->_id))
                            ->endkey(array($societe->interpro,  $societe->_id, array()))
                            ->getView($this->design, $this->view);
        
    }

    public static function makeLibelle($datas) {
        $libelle = '';
        
        if (isset($datas[self::KEY_RAISON_SOCIALE]) && $rs = $datas[self::KEY_RAISON_SOCIALE]) {
            if ($libelle) {
                $libelle .= ' / ';
            }
            $libelle .= $rs;
        }
        $libelle .= ' ('.$datas[self::KEY_IDENTIFIANT];
        if (isset($datas[self::KEY_SIRET]) && $siret = $datas[self::KEY_SIRET]) {
            $libelle .= ' / '.$siret;
        }
        $libelle .= ') ';

    	if (isset($datas[self::KEY_COMMUNE]))
    	  	$libelle .= ' '.$datas[self::KEY_COMMUNE];

    	if (isset($datas[self::KEY_CODE_POSTAL]))
    	  	$libelle .= ' '.$datas[self::KEY_CODE_POSTAL];
        
        return trim($libelle);
    }

}  