<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMCrdRegimeChoiceForm
 *
 * @author mathurin
 */
class DRMCrdRegimeChoiceForm extends acCouchdbObjectForm {

    protected $drm = null;
    protected $etablissement = null;

    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->drm = $drm;
        $this->etablissement = $this->drm->getEtablissement();
        parent::__construct($drm, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'crd_regime' => new sfWidgetFormChoice(array('expanded' => true, 'multiple' => false, 'choices' => $this->getCRDRegimes()))
        ));
        $this->widgetSchema->setLabels(array(
            'crd_regime' => 'Régime CRD (Compte capsules) : '
        ));

        $this->setValidators(array(
            'crd_regime' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCRDRegimes())), array('required' => "Aucun régime CRD n'a été choisi")),
        ));

        $this->widgetSchema->setNameFormat('drm_regime_crd[%s]');
    }

    public function getCRDRegimes() {
        return EtablissementClient::$regimes_crds_libelles_longs;
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $crd_regime = $values['crd_regime'];
        $this->etablissement->add('crd_regime', $crd_regime);
        $this->etablissement->save();
        $this->drm->forceModified();
        $this->drm->add('crds')->add($crd_regime);        
        $this->drm->save();
    }    
}
