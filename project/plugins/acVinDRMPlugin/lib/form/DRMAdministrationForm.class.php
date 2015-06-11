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
            $this->setValidator('dsa_daa_debut', new sfValidatorString(array('required' => false)));
            $this->setValidator('dsa_daa_fin', new sfValidatorString(array('required' => false)));
        }

        if (count($this->detailsSortiesExport)) {
            $this->setWidget('dae_debut', new sfWidgetFormInputText());
            $this->setWidget('dae_fin', new sfWidgetFormInputText());
            $this->widgetSchema->setLabel('dae_debut', 'DSA/DAA début');
            $this->widgetSchema->setLabel('dae_fin', 'DSA/DAA fin');
            $this->setValidator('dae_debut', new sfValidatorString(array('required' => false)));
            $this->setValidator('dae_fin', new sfValidatorString(array('required' => false)));
        }
        $this->widgetSchema->setNameFormat('drmAddTypeForm[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (count($this->detailsSortiesVrac)) {
            $this->drm->getOrAdd('documents_administration')->add('dsa_daa_debut', $values['dsa_daa_debut']);
            $this->drm->getOrAdd('documents_administration')->add('dsa_daa_fin', $values['dsa_daa_fin']);
        }
        if (count($this->detailsSortiesExport)) {
            $this->drm->getOrAdd('documents_administration')->add('dae_debut', $values['dae_debut']);
            $this->drm->getOrAdd('documents_administration')->add('dae_fin', $values['dae_fin']);
        }

        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->save();
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($this->drm->exist('documents_administration') && $this->drm->documents_administration) {
            $this->setDefault('dsa_daa_debut', $this->drm->documents_administration->dsa_daa_debut);
            $this->setDefault('dsa_daa_fin', $this->drm->documents_administration->dsa_daa_fin);
            $this->setDefault('dae_debut', $this->drm->documents_administration->dae_debut);
            $this->setDefault('dae_fin', $this->drm->documents_administration->dae_fin);
        }
    }

}
