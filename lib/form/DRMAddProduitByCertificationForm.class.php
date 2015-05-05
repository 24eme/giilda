<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMAddProduitByCertificationForm
 *
 * @author mathurin
 */
class DRMAddProduitByCertificationForm extends acCouchdbForm {

    protected $_configurationCertification = null;
    protected $_drm = null;
    protected $_choices_produits = null;

    public function __construct(ConfigurationCertification $configurationCertification, DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->_drm = $drm;
        $this->_configurationCertification = ConfigurationClient::getCurrent()->get($configurationCertification->getHash()); 
        $defaults = array();
        parent::__construct($drm, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'produits' => new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getProduits())) 
        ));
        $this->widgetSchema->setLabels(array(
            'produits' => 'Produit : '
        ));

        $this->setValidators(array(
            'produits' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits())), array('required' => "Aucun produit n'a été saisi !")),
         ));

        $this->validatorSchema->setPostValidator(new DRMProduitValidator(null, array('drm' => $this->_drm)));
        $this->widgetSchema->setNameFormat('add_produit_' . $this->_configurationCertification->getKey() . '[%s]');
    }

    public function getDRM() {
        return $this->_drm;
    }


    public function getProduits() {
        if (is_null($this->_choices_produits)) {
           
            $this->_choices_produits = array("" => "");
            foreach ($this->_configurationCertification->getProduits() as $hash => $produit) {
                $this->_choices_produits[$produit->getHashForKey()] = $produit->formatProduitLibelle();
            }
        }
        return $this->_choices_produits;
    }

    public function addProduit() {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }
    }

}
