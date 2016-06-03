<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AddRowRevendicationForm
 * @author mathurin
 */
class AddRowRevendicationForm extends EditionRevendicationForm {

    protected $revendication;

    public function __construct(stdClass $revendication, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->revendication = $revendication;
        parent::__construct($revendication, null, null, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->setWidget('etablissement', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-inter-loire', 'familles' => array(EtablissementFamilles::FAMILLE_NEGOCIANT, EtablissementFamilles::FAMILLE_PRODUCTEUR))));
        $this->setValidator('etablissement', new ValidatorEtablissement(array('required' => true)));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('revendication_creation_row[%s]');
    }

    public function doUpdate() {

        return RevendicationClient::getInstance()->addVolumeSaisiByStdClass($this->revendication,
                                                                            $this->values['etablissement'],
                                                                            $this->values['produit_hash'],
                                                                            $this->values['volume'],
                                                                            date('Y-m-d'));
    }

}

?>
