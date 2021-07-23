<?php

class FacturePaiementsMultipleForm extends acCouchdbObjectForm {

    public function configure()
    {
      $this->getObject()->add('paiements');
      $this->getObject()->paiements->add();
      $this->embedForm('paiements', new FacturePaiementsForm($this->getObject()));
      $this->widgetSchema->setNameFormat('facture_paiements_multiple[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);

        $paiementsToDelete = array();

        foreach($this->getObject()->paiements as $paiement) {
            if(!$paiement->exist('montant') || !$paiement->montant) {
                $paiementsToDelete[$paiement->getKey()] = $true;
            }
        }

        foreach($paiementsToDelete as $key => $void) {
            $this->getObject()->paiements->remove($key);
        }

        $this->getObject()->updateMontantPaiement();
    }

}
