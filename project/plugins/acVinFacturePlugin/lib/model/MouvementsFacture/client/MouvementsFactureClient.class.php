<?php

class MouvementsFactureClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("MouvementsFacture");
    }

    public function createMouvementsFacture($date = null) {
        $mouvementsFacture = new MouvementsFacture();
        $mouvementsFacture->constructIds($date);
        return $mouvementsFacture;
    }

    public function getId($identifiant) {
        return 'MOUVEMENTSFACTURE-'.$identifiant;
    }

    public function findAll($identifiant) {
        return 'MOUVEMENTSFACTURE-'.$identifiant;
    }

    public function getNextNoMouvementsFacture($date)
    {
        $num = '';
    	$mouvementsfacture = self::getAtDate($date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();

        if (count($mouvementsfacture) > 0) {
           $incr = ((double)str_replace('MOUVEMENTSFACTURE-'.$date, '', max($mouvementsfacture)) + 1);

            $num .= $date.sprintf('%02d',$incr);
        } else {
            $num.= $date.'01';
        }
        return $num;
    }

    public function getAtDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('MOUVEMENTSFACTURE-'.$date.'00')->endkey('MOUVEMENTSFACTURE-'.$date.'99')->execute($hydrate);
    }
}
