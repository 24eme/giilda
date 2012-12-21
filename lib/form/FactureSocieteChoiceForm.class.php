<?php

class FactureSocieteChoiceForm extends SocieteChoiceForm {

    public function configure()
    {
        parent::configure();
        $this->configureFamilles(array(EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::FAMILLE_NEGOCIANT));
    }

}
