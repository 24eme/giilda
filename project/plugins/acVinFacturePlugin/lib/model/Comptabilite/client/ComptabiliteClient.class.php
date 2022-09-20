<?php

class ComptabiliteClient extends acCouchdbClient {

    const DOC_ID = "COMPTABILITE";

    public static function getInstance()
    {
      return acCouchdbManager::getClient("Comptabilite");
    }

    public function findCompta($region = null) {
        $id = $this->determineId($region);
        $compta = $this->find($id);
        if(!$compta) {
            $compta = new Comptabilite();
            $compta->set("_id", $id);
        }
        return $compta;
    }

    private function determineId($region = null) {
        return ($region)? self::DOC_ID.'-'.$region : self::DOC_ID;
    }
}
