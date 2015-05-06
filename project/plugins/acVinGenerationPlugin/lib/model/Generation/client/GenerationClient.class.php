<?php

class GenerationClient extends acCouchdbClient {

    const TYPE_DOCUMENT_FACTURES = 'FACTURE';
    const TYPE_DOCUMENT_DS = 'DS';
    const TYPE_DOCUMENT_RELANCE = 'RELANCE';
    const HISTORY_KEYS_TYPE_DOCUMENT = 0;
    const HISTORY_KEYS_TYPE_DATE_EMISSION = 1;
    const HISTORY_KEYS_DOCUMENT_ID = 1;
    
    const HISTORY_VALUES_NBDOC = 0;
    const HISTORY_VALUES_DOCUMENTS = 1;
    const HISTORY_VALUES_SOMME = 2;
    const HISTORY_VALUES_STATUT = 3;
    const GENERATION_STATUT_ENATTENTE = "EN ATTENTE";
    const GENERATION_STATUT_ENCOURS = "EN COURS";
    const GENERATION_STATUT_GENERE = "GENERE";
    const GENERATION_STATUT_ENERREUR = "EN ERREUR";

    public static function getInstance() {
        return acCouchdbManager::getClient("Generation");
    }

    public function getId($type_document, $date) {
        return 'GENERATION-' . $type_document . '-' . $date;
    }

    public function findHistory($limit = 10) {
        return acCouchdbManager::getClient()
                        ->limit($limit)
                        ->getView("generation", "history")
                ->rows;
    }

//     public function findHistoryWithStatusAndType($status, $type,$limit = 10) {
//        $views = acCouchdbManager::getClient()
//                        ->startkey(array($status, $type))
//                        ->endkey(array($status, $type, array()));
//           if($limit) $views = $views->limit($limit);
//        return $views->getView("generation", "history")->rows;
//    }

    public function findHistoryWithType($type, $limit = 100) {
        $views = acCouchdbManager::getClient()
                ->startkey(array($type))
                ->endkey(array($type, array()));
	$rows = $views->getView("generation", "history")->rows;
        uasort($rows, "GenerationClient::sortHistory");
        $cpt = count($rows) - 1;
        $result = array();
        while($cpt > (count($rows) - $limit) && $cpt > -1){
         $result[] = $rows[$cpt]; 
         $cpt--;
        }
	return $result;
    }

    public static function sortHistory($a, $b) {
      return strcmp($b->id, $a->id);
    }

    public function getGenerationIdEnAttente() {
        $rows = acCouchdbManager::getClient()
                        ->startkey(array(self::GENERATION_STATUT_ENATTENTE))
                        ->endkey(array(self::GENERATION_STATUT_ENATTENTE, array()))
                        ->getView("generation", "creation")
                ->rows;
        $ids = array();
        foreach ($rows as $row) {
            $ids[] = $row->id;
        }
        return $ids;
    }

    public function getDateFromIdGeneration($date) {
        $annee = substr($date, 0, 4);
        $mois = substr($date, 4, 2);
        $jour = substr($date, 6, 2);
        $heure = substr($date, 8, 2);
        $minute = substr($date, 10, 2);
        $seconde = substr($date, 12, 2);
        return $jour . '/' . $mois . '/' . $annee . ' ' . $heure . ':' . $minute . ':' . $seconde;
    }

    public function getAllStatus() {
        return array(self::GENERATION_STATUT_ENCOURS, self::GENERATION_STATUT_GENERE);
    }

}
