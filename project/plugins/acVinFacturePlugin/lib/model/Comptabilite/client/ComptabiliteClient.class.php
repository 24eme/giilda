<?php

class ComptabiliteClient extends acCouchdbClient {

    const DOC_ID = "COMPTABILITE";

    public static function getInstance()
    {
      return acCouchdbManager::getClient("Comptabilite");
    }

    public function findCompta($interpro = null) {
        $id = $this->determineId($interpro);
        $compta = $this->find($id);
        if(!$compta) {
            $compta = new Comptabilite();
            $compta->set("_id", $id);
        }
        return $compta;
    }

    private function determineId($interpro = null) {
        return ($interpro)? self::DOC_ID.'-'.$interpro : self::DOC_ID;
    }
}
