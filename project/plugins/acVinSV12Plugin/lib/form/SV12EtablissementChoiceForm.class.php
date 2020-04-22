<?php

class SV12EtablissementChoiceForm extends EtablissementChoiceForm {

    public function configure()
    {
        parent::configure();
        $this->configureFamilles(array(EtablissementFamilles::FAMILLE_NEGOCIANT,EtablissementFamilles::FAMILLE_NEGOCIANT_PUR));
    }

}
