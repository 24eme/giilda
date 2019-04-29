<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMAnnexesForm
 *
 * @author mathurin
 */
class DRMMatierePremiereForm extends acCouchdbForm {

    private $detail = null;

    public function __construct(acCouchdbJson $detail, $options = array(), $CSRFSecret = null) {
        $this->detail = $detail;
        $this->doc = $detail->getDocument();

        $defaults = array();
        $defaults['stocks_debut'] = $this->detail->stocks_debut->initial;

        parent::__construct($detail->getDocument(), $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        if ($this->detail->hasStockFinDeMoisDRMPrecedente()) {
            $this->setWidget('stocks_debut', new bsWidgetFormInputFloat(array(), array('readonly' => 'readonly')));
        }else{
            $this->setWidget('stocks_debut', new bsWidgetFormInputFloat());
        }
        $this->setValidator('stocks_debut', new sfValidatorNumber(array('required' => false)));

        $formProduits = new BaseForm();
        foreach($this->getDetailsAlcool() as $detail) {
            $isreadonly = array();
            if ($detail->hasStockFinDeMoisDRMPrecedente()) {
                $isreadonly = array('readonly' => 'readonly');
            }
            $volume = null;
            $keyDetail = str_replace('/', '-', $detail->getHash());
            if($this->detail->sorties->transfertsrecolte_details->exist($keyDetail)) {
                $volume = $this->detail->sorties->transfertsrecolte_details->get($keyDetail)->volume;
            }
            $formProduit = new BaseForm(array("volume" => $volume, "tav" => $detail->tav));
            $formProduit->setWidget('volume', new bsWidgetFormInputFloat());
            $formProduit->setValidator('volume', new sfValidatorNumber(array('required' => false)));
            $formProduit->setWidget('tav', new bsWidgetFormInputFloat(array(), $isreadonly));
            $formProduit->setValidator('tav', new sfValidatorNumber(array('required' => false)));

            $formProduits->embedForm($detail->getHash(), $formProduit);
        }

        $this->embedForm('sorties', $formProduits);

        $this->widgetSchema->setNameFormat('drm_matiere_premiere[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function getDetailsAlcool() {
        $produits = array();

        foreach($this->getDocument()->getProduitsDetails() as $detail) {
            if(!$detail->exist('tav')) {
                continue;
            }
            $produits[] = $detail;
        }

        return $produits;
    }

    public function save() {
        if(!$this->isBound()) {
            return;
        }

        $this->detail->stocks_debut->initial = $this->getValue('stocks_debut');
        $this->detail->edited = true;

        $sortiesValues = $this->getValue('sorties');

        foreach($sortiesValues as $hash => $sortie) {
            $detailAlcool = DRMESDetailAlcoolPur::freeInstance($this->getDocument());
            $detailAlcool->setProduit($this->getDocument()->get($hash));
            $detailAlcool->tav = $sortie['tav'];
            $detailAlcool->volume = $sortie['volume'];
            $this->detail->sorties->transfertsrecolte_details->addDetail($detailAlcool);
        }

        $this->getDocument()->update();
        $this->getDocument()->save();
    }

}
