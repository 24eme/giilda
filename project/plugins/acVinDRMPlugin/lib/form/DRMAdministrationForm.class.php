<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMAdministrationForm
 *
 * @author mathurin
 */
class DRMAdministrationForm extends acCouchdbObjectForm {

    private $drm = null;
    private $detailsSortiesVrac = null;
    private $detailsSortiesExport = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object;
        $this->detailsSortiesVrac = $this->drm->getVracs();
        $this->detailsSortiesExport = $this->drm->getExports();
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        if (count($this->detailsSortiesVrac)) {
            $this->setWidget('dsa_daa_debut', new sfWidgetFormInputText());
            $this->setWidget('dsa_daa_fin', new sfWidgetFormInputText());
            $this->widgetSchema->setLabel('dsa_daa_debut', 'DSA/DAA début');
            $this->widgetSchema->setLabel('dsa_daa_fin', 'DSA/DAA fin');
            $this->setValidator('dsa_daa_debut', new sfValidatorNumber(array('required' => false)));
            $this->setValidator('dsa_daa_fin', new sfValidatorNumber(array('required' => false)));
        }

        if (count($this->detailsSortiesExport)) {
            $this->setWidget('dae_debut', new sfWidgetFormInputText());
            $this->setWidget('dae_fin', new sfWidgetFormInputText());
            $this->widgetSchema->setLabel('dae_debut', 'DSA/DAA début');
            $this->widgetSchema->setLabel('dae_fin', 'DSA/DAA fin');
            $this->setValidator('dae_debut', new sfValidatorNumber(array('required' => false)));
            $this->setValidator('dae_fin', new sfValidatorNumber(array('required' => false)));
        }
        $this->widgetSchema->setNameFormat('drmAddTypeForm[%s]');
    }

}
