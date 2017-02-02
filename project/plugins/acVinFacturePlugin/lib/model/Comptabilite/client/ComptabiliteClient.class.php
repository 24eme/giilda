<?php

class ComptabiliteClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Comptabilite");
    }

    public function findCompta() {
        $compta = $this->find('COMPTABILITE');

        if(!$compta) {
            $compta = new Comptabilite();
        }

        return $compta;
    }
}
