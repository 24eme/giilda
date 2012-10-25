<?php

/**
 * Description of class RevendicationEtablissementChoiceForm
 * @author mathurin
 */
class RevendicationEtablissementChoiceForm extends EtablissementChoiceForm {

    public function configure()
    {
        parent::configure();
        $this->configureFamilles(array(EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::FAMILLE_NEGOCIANT));
    }

}
