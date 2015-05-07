<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DRMCrdsForm extends acCouchdbObjectForm {

    private $drm = null;
    private $crds = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object;
        $this->crds = $this->drm->getAllCrds();
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        
        foreach ($this->crds as $crdTypeKey => $crd) {
            $this->setWidget('entrees_'.$crdTypeKey, new sfWidgetFormInput());
            $this->setWidget('sorties_'.$crdTypeKey, new sfWidgetFormInput());
            $this->setWidget('pertes_'.$crdTypeKey, new sfWidgetFormInput());

            $this->widgetSchema->setLabel('entrees_' . $crdTypeKey, 'Entrées');
            $this->widgetSchema->setLabel('sorties_' . $crdTypeKey, 'Sortie');
            $this->widgetSchema->setLabel('pertes_' . $crdTypeKey, 'Perte');

            $this->setValidator('entrees_'. $crdTypeKey, new sfValidatorNumber(array('required' => false)));
            $this->setValidator('sorties_'. $crdTypeKey, new sfValidatorNumber(array('required' => false)));
            $this->setValidator('pertes_'. $crdTypeKey, new sfValidatorNumber(array('required' => false)));
        }

        $this->widgetSchema->setNameFormat('drmCrds[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($values as $key => $value) {
            $matches = array();
            if(preg_match('/^(entrees|sorties|pertes)_(.*)$/', $key,$matches)){
                $crdField = $matches[1];
                $crdKey = $matches[2];
                $crd = $this->drm->getOrAdd('crds')->getOrAdd($crdKey);
                $crd->{$crdField} = $value;
            }
        }
        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->getCrds()->udpateStocksFinDeMois();
        $this->drm->save();
    }
    
    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        foreach ($this->crds as $crdTypeKey => $crd) {
                $this->setDefault('entrees_' . $crdTypeKey, ($crd->entrees)? $crd->entrees : 0);
                $this->setDefault('sorties_' . $crdTypeKey, ($crd->sorties)? $crd->sorties : 0);
                $this->setDefault('pertes_' . $crdTypeKey, ($crd->pertes)? $crd->pertes : 0);
            
        }
    }
}
    