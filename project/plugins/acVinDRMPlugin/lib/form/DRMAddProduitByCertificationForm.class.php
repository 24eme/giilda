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
class DRMAddProduitByCertificationForm extends acCouchdbObjectForm {

    protected $_configurationCertification = null;
    protected $_drm = null;
    protected $_choices_produits = null;

    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->_drm = $drm;        
        $this->_configurationCertification = ConfigurationClient::getCurrent()->get($options['configurationCertification']->getHash());
        parent::__construct($drm, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'produit' => new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getProduits()))
        ));
        $this->widgetSchema->setLabels(array(
            'produit' => 'Produit : '
        ));

        $this->setValidators(array(
            'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits())), array('required' => "Aucun produit n'a été saisi !")),
        ));

        $this->widgetSchema->setNameFormat('add_produit_' . $this->_configurationCertification->getKey() . '[%s]');
    }

    public function getDRM() {
        return $this->_drm;
    }

    public function getProduits() {
        if (is_null($this->_choices_produits)) {

            $this->_choices_produits = array("" => "");
            $date = $this->_drm->getFirstDayOfPeriode();
            $produits = $this->_configurationCertification->formatProduits($date,$this->_drm->getInterpro()->get('_id'), 
            $this->_drm->getDepartement(),"%format_libelle% (%code_produit%)",true);
            if (!is_null($produits)) {
                foreach ($produits as $hash => $produit) {
                     $p = ConfigurationClient::getCurrent()->get($hash);
                     $this->_choices_produits[$p->getHashForKey()] = $p->formatProduitLibelle();
                }
            }
        }
        return $this->_choices_produits;
    }

    public function doUpdateObject($values) { 
       parent::doUpdateObject($values);     
        $produit = str_replace('-', '/', $values['produit']);
        $this->_drm->getOrAdd($produit.'/details/DEFAUT');
        $this->_drm->save();
    }    

}
