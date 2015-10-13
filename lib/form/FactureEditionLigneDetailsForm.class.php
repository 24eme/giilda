<?php

class FactureEditionLigneDetailsForm extends acCouchdbObjectForm {

    public function configure()
    {
        foreach($this->getObject() as $detail) {
            $this->embedForm($detail->getKey(), new FactureEditionLigneDetailForm($detail));
        }

        $this->widgetSchema->setNameFormat('facture_edition_ligne_details[%s]');
    }     

}
