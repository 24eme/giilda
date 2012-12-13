<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationContratsNonSoldes
 * @author mathurin
 */
class AlerteGenerationVracsNonSoldes extends AlerteGeneration {

    public function getTypeAlerte() {

        return AlerteClient::VRAC_NON_SOLDES;
    }

    public function creations() {
        $vracs = VracClient::getInstance()->retreiveByStatutsTypesAndDate(
                array(VracClient::STATUS_CONTRAT_NONSOLDE), array(VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
            VracClient::TYPE_TRANSACTION_VIN_VRAC), $this->getConfig()->getOptionDelaiDate('creation_delai', $this->getDate()));
        foreach ($vracs as $vrac) {
            $alerte = $this->createOrFind($vrac->id, $vrac->key[VracStatutAndTypeView::KEY_IDENTIFIANT], $vrac->key[VracStatutAndTypeView::KEY_NOM]);
            $contrat = VracClient::getInstance()->find($vrac->id);
            $alerte->campagne = $contrat->campagne;
            $alerte->region = $this->getRegionFromIdEtb($contrat->vendeur_identifiant);
            if ($alerte->isNew() || $alerte->isClosed()) {
                $alerte->open($this->getDate());
            }
            $alerte->save();
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $vrac = VracClient::getInstance()->find($id_document);
            if (isset($vrac)) {
                if ($vrac->isSolde()) {
                    $alerte = AlerteClient::getInstance()->find($alerteView->id);
                    $alerte->updateStatut(AlerteClient::STATUT_FERME);
                    $alerte->save();
                    continue;
                }
            }
        }
        parent::updates();
    }

    public function setDatasRelance(Alerte $alerte) {
        $this->setDatasRelanceForVrac($alerte);
    }

}