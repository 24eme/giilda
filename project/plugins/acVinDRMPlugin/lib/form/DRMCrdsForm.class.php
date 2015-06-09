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
                    $keyWidgetsSuffixe = '_'.$regime . '_' . $key;
                    $this->setWidget('entrees_achats' . $keyWidgetsSuffixe, new sfWidgetFormInput());
                    $this->setWidget('entrees_retours' . $keyWidgetsSuffixe, new sfWidgetFormInput());
                    $this->setWidget('entrees_excedents' . $keyWidgetsSuffixe, new sfWidgetFormInput());

                    $this->setWidget('sorties_utilisations' . $keyWidgetsSuffixe, new sfWidgetFormInput());
                    $this->setWidget('sorties_destructions' . $keyWidgetsSuffixe, new sfWidgetFormInput());
                    $this->setWidget('sorties_manquants' . $keyWidgetsSuffixe, new sfWidgetFormInput());

                    $this->widgetSchema->setLabel('entrees_achats' . $keyWidgetsSuffixe, 'Achats');
                    $this->widgetSchema->setLabel('entrees_retours' . $keyWidgetsSuffixe, 'Retours');
                    $this->widgetSchema->setLabel('entrees_excedents' . $keyWidgetsSuffixe, 'Excédents');

                    $this->widgetSchema->setLabel('sorties_utilisations' . $keyWidgetsSuffixe, 'Utilisations');
                    $this->widgetSchema->setLabel('sorties_destructions' . $keyWidgetsSuffixe, 'Destructions');
                    $this->widgetSchema->setLabel('sorties_manquants' . $keyWidgetsSuffixe, 'Manquants');

                    $this->setValidator('entrees_achats' . $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));
                    $this->setValidator('entrees_retours' . $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));
                    $this->setValidator('entrees_excedents' . $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));

                    $this->setValidator('sorties_utilisations' . $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));
                    $this->setValidator('sorties_destructions' . $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));
                    $this->setValidator('sorties_manquants' . $keyWidgetsSuffixe, new sfValidatorNumber(array('required' => false)));
                }
            }
        }

        $this->widgetSchema->setNameFormat('drmCrds[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($values as $key => $value) {
            $matches = array();
            if (preg_match('/^(entrees_achats|entrees_retours|entrees_excedents|sorties_utilisations|sorties_destructions|sorties_manquants)_(.*)_(.*)$/', $key, $matches)) {
                $crdField = $matches[1];
                $crdRegimeKey = $matches[2];
                $crdKey = $matches[3];
                $crd = $this->drm->getOrAdd('crds')->getOrAdd($crdRegimeKey)->getOrAdd($crdKey);
                $crd->{$crdField} = $value;
            }
        }
        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->updateStockFinDeMoisAllCrds();
        $this->drm->save();
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        foreach ($this->crds as $regime => $crdAllGenre) {
            foreach ($crdAllGenre as $genre => $crds) {
                foreach ($crds as $key => $crd) {
                    $keyWidgetsSuffixe = $regime . '_' . $key;
                    $this->setDefault('entrees_achats_' . $keyWidgetsSuffixe, ($crd->entrees_achats) ? $crd->entrees_achats : 0);
                    $this->setDefault('entrees_retours_' . $keyWidgetsSuffixe, ($crd->entrees_retours) ? $crd->entrees_retours : 0);
                    $this->setDefault('entrees_excedents_' . $keyWidgetsSuffixe, ($crd->entrees_excedents) ? $crd->entrees_excedents : 0);
                
                     $this->setDefault('sorties_utilisations_' . $keyWidgetsSuffixe, ($crd->sorties_utilisations) ? $crd->sorties_utilisations : 0);
                    $this->setDefault('sorties_destructions_' . $keyWidgetsSuffixe, ($crd->sorties_destructions) ? $crd->sorties_destructions : 0);
                    $this->setDefault('sorties_manquants_' . $keyWidgetsSuffixe, ($crd->sorties_manquants) ? $crd->sorties_manquants : 0);
                
                }
            }
        }
    }

}
