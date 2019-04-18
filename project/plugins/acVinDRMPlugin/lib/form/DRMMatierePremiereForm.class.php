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
        $this->setWidget('stocks_debut', new bsWidgetFormInputFloat());
        $this->setValidator('stocks_debut', new sfValidatorNumber(array('required' => false)));

        $formProduits = new BaseForm();
        foreach($this->getDetailsAlcool() as $detail) {
            $formProduit = new BaseForm(array("volume" => null, "tav" => $detail->tav));
            $formProduit->setWidget('volume', new bsWidgetFormInputFloat());
            $formProduit->setValidator('volume', new sfValidatorNumber(array('required' => false)));
            $formProduit->setWidget('tav', new bsWidgetFormInputFloat());
            $formProduit->setValidator('tav', new sfValidatorNumber(array('required' => false)));

            $formProduits->embedForm($detail->getHash(), $formProduit);
        }

        $this->embedForm('sorties', $formProduits);
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
        $this->getDocument()->save();
    }

}
