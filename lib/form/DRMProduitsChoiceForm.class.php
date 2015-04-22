<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMProduitsChoiceForm
 *
 * @author mathurin
 */
class DRMProduitsChoiceForm extends acCouchdbObjectForm {

    private $_drm = null;
    private $_produits = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->_drm = $object;
        $this->_produits = $this->_drm->declaration->getProduitsDetails();
        parent::__construct($this->_drm, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->_produits as $produit) {
            $this->setWidget('produit' . $produit->getHashForKey(), new sfWidgetFormInputCheckbox(array('value_attribute_value' => '1', 'default' => false)));

            $this->widgetSchema->setLabel('produit' . $produit->getHashForKey(), '');

            $this->setValidator('produit' . $produit->getHashForKey(), new sfValidatorString(array('required' => false)));
        }

        $this->widgetSchema->setNameFormat('produitsChoice[%s]');
    }

    protected function doUpdateObject($values) {

        foreach ($values as $key => $value) {
            $matches = array();
            if (preg_match('/^produit(.*)/', $key, $matches)) {
                $key = str_replace('-', '/', $matches[1]);
                $this->_drm->get($key)->getCepage()->add('no_movements', !! $value);
                $this->_drm->etape = DRMClient::ETAPE_SAISIE;
            }
        }
        $this->_drm->save();
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        foreach ($this->_produits as $produit) {
            if ($produit->getCepage()->exist('no_movements') && $produit->getCepage()->no_movements) {
                $this->setDefault('produit' . $produit->getHashForKey(), true);
            }
        }
    }

}
