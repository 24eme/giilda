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
        $this->crds = $this->drm->getAllCrdsByRegimeAndByGenre();
       
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        
        foreach ($this->crds as $regime => $crdAllGenre) {
             foreach ($crdAllGenre as $genre => $crds) {
                 foreach ($crds as $key => $crd) {
                     $keyWidgetsSuffixe = $regime.'_'.$key;
            $this->setWidget('entrees_'.$keyWidgetsSuffixe, new sfWidgetFormInput());
            $this->setWidget('sorties_'.$keyWidgetsSuffixe, new sfWidgetFormInput());
            $this->setWidget('pertes_'.$keyWidgetsSuffixe, new sfWidgetFormInput());

            $this->widgetSchema->setLabel('entrees_' . $keyWidgetsSuffixe, 'Entrées');
            $this->widgetSchema->setLabel('sorties_' . $keyWidgetsSuffixe, 'Sortie');
            $this->widgetSchema->setLabel('pertes_' . $keyWidgetsSuffixe, 'Perte');

            $this->setValidator('entrees_'. $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));
            $this->setValidator('sorties_'. $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));
            $this->setValidator('pertes_'. $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));
                 }
             }
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
    