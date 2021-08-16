<?php

class FacturePaiementsForm extends acCouchdbObjectForm {

    public function configure()
    {
      $paiements = $this->getObject()->getOrAdd('paiements');
      foreach($paiements as $paiement) {
          $this->embedForm($paiement->getKey(), new FacturePaiementEmbedForm($paiement));
      }

        $this->widgetSchema->setNameFormat('facture_paiements[%s]');
    }


    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

}
