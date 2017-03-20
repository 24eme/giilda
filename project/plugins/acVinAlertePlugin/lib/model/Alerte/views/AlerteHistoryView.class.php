<?php

/**
 * Description of class AlerteHistoryView
 * @author mathurin
 */
class AlerteHistoryView extends acCouchdbView {

    const KEY_TYPE_ALERTE = 0;
    const KEY_STATUT_ALERTE = 1;
    const KEY_DATE_ALERTE = 2;
    const KEY_ID_DOCUMENT_ALERTE = 3;
    const KEY_DATE_CREATION_ALERTE = 4;
    const KEY_IDENTIFIANT = 5;
    const VALUE_NOM = 0;
    const VALUE_LIBELLE_DOCUMENT = 1;

    public static function getInstance() {
        return acCouchdbManager::getView('alerte', 'history', 'Alerte');
    }

    public function getHistory() {
        return acCouchdbManager::getClient()->limit(100)
                        ->getView($this->design, $this->view)->rows;
    }

    public function getAllHistory() {
        return acCouchdbManager::getClient()->getView($this->design, $this->view)->rows;
    }

    public function findByType($type_alerte) {
        return acCouchdbManager::getClient()
                        ->startkey(array($type_alerte))
                        ->endkey(array($type_alerte, array()))
                        ->getView($this->design, $this->view)->rows;
    }

    public function findByTypesAndStatut($types_alerte, $statut_alerte) {
        $result = array();
        foreach ($types_alerte as $type_alerte) {
            $result = array_merge($this->findByTypeAndStatut($type_alerte, $statut_alerte), $result);
        }
        return $result;
    }

    public function findByTypeAndStatuts($type_alerte, $statuts_alerte) {
        $result = array();
        foreach ($statuts_alerte as $statut_alerte) {
            $result = array_merge($this->findByTypeAndStatut($type_alerte, $statut_alerte), $result);
        }
        return $result;
    }

    public function findByTypeAndStatut($type_alerte, $statut_alerte) {
      
        return acCouchdbManager::getClient()
                        ->startkey(array($type_alerte, $statut_alerte))
                        ->endkey(array($type_alerte, $statut_alerte, array()))
                        ->getView($this->design, $this->view)->rows;
    }

//    public static function getLibelleForIdDocument($id_document) {
//        if (substr($id_document, 0, 5) == 'VRAC-') {
//            return 'Contrat du ' . VracClient::getInstance()->getLibelleContratNum(str_replace('VRAC-', '', $id_document));
//        }
//        if (substr($id_document, 0, 4) == 'DRM-') {
//            return 'DRM du ' . DRMClient::getInstance()->getLibelleContratNum(str_replace('VRAC-', '', $id_document));
//        }
//        return '';
//    }
}
