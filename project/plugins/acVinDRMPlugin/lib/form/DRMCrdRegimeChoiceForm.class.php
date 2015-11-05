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
        $to_removes = array();
        foreach ($this->drm->getOrAdd('crds') as $regime => $crds) {
            if ($crd_regime != $regime) {
                $to_removes[$regime] = $crds;
            }
        }
        
        foreach ($to_removes as $removeRegime => $crds) {
            $this->drm->getOrAdd('crds')->remove($removeRegime);
            $this->drm->getOrAdd('crds')->add($crd_regime, $crds);
            if (($removeRegime == EtablissementClient::REGIME_CRD_COLLECTIF_ACQUITTE) &&
                    ($crd_regime == EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU) || ($crd_regime == EtablissementClient::REGIME_CRD_PERSONNALISE)) {
                foreach ($this->drm->getProduits() as $produit) {
                    foreach ($produit->getProduitsDetails(true) as $detailsKey => $details) {
                        if($details->get('sorties')->exist('ventefrancebouteillecrdacquitte') && $details->get('sorties')->ventefrancebouteillecrdacquitte){
                            $details->get('sorties')->add('ventefrancebouteillecrd', $details->get('sorties')->ventefrancebouteillecrdacquitte);
                            $details->get('sorties')->ventefrancebouteillecrdacquitte = 0;
                        }
                        if($details->get('sorties')->exist('ventefrancebibcrdacquitte') && $details->get('sorties')->ventefrancebibcrdacquitte){
                            $details->get('sorties')->add('ventefrancebibcrd', $details->get('sorties')->ventefrancebibcrdacquitte);
                            $details->get('sorties')->ventefrancebibcrdacquitte = 0;
                        }
                          if($details->get('entrees')->exist('achatcrdacquitte') && $details->get('entrees')->achatcrdacquitte){
                            $details->get('entrees')->add('achatcrd', $details->get('entrees')->achatcrdacquitte);
                            $details->get('entrees')->achatcrdacquitte = 0;
                        }
                    }
                }
            }
            if (($crd_regime == EtablissementClient::REGIME_CRD_COLLECTIF_ACQUITTE) &&
                    ($removeRegime == EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU) || ($removeRegime == EtablissementClient::REGIME_CRD_PERSONNALISE)) {
                foreach ($this->drm->getProduits() as $produit) {
                    foreach ($produit->getProduitsDetails(true) as $detailsKey => $details) {
                        if($details->get('sorties')->exist('ventefrancebouteillecrd') && $details->get('sorties')->ventefrancebouteillecrd){
                            $details->get('sorties')->add('ventefrancebouteillecrdacquitte', $details->get('sorties')->ventefrancebouteillecrd);
                            $details->get('sorties')->ventefrancebouteillecrd = 0;
                        }
                         if($details->get('sorties')->exist('ventefrancebibcrd') && $details->get('sorties')->ventefrancebibcrd){
                            $details->get('sorties')->add('ventefrancebibcrdacquitte', $details->get('sorties')->ventefrancebibcrd);
                            $details->get('sorties')->ventefrancebibcrd = 0;
                        }
                        if($details->get('entrees')->exist('achatcrd') && $details->get('entrees')->achatcrd){
                            $details->get('entrees')->add('achatcrdacquitte', $details->get('entrees')->achatcrd);
                            $details->get('entrees')->achatcrd = 0;
                        }
                    }
                }
            }
        }
        $this->drm->save();
    }

}
