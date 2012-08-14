<?php

class DRMProduitForm extends acCouchdbForm 
{
	protected $_choices_produits;
    protected $_drm = null;
    protected $_config = null;

    public function __construct(DRM $drm, _ConfigurationDeclaration $config, $options = array(), $CSRFSecret = null) {
		$this->_drm = $drm;
        $this->_interpro = $drm->getInterpro();
        $this->_config = $config;
        $defaults = array();
        parent::__construct($drm, $defaults, $options, $CSRFSecret);
    }
    
    public function configure() 
    {
        $this->setWidgets(array(
            'hashref' => new sfWidgetFormChoice(array('choices' => $this->getChoices())),
        ));
        $this->widgetSchema->setLabels(array(
            'hashref' => 'Produit: ',
        ));

        $this->setValidators(array(
            'hashref' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits())),array('required' => "Aucun produit n'a été saisi !")),
        ));

        /*$this->validatorSchema->setPostValidator(new DRMProduitValidator(null, array('drm' => $this->_drm)));*/
        $this->widgetSchema->setNameFormat('produit_'.$this->_config->getKey().'[%s]');
    }

    public function getChoices() {
        if (is_null($this->_choices_produits)) {
            $this->_choices_produits = array_merge(array("" => ""),
                                                   array("existant" => $this->getProduitsExistant()),
												   array("nouveau" => $this->getProduits()));
        }

        return $this->_choices_produits;
    }

    public function getProduits() {

        return $this->_config->formatProduits($this->_interpro->get('_id'), $this->_drm->getDepartement());
    }

    public function getProduitsExistant() {
        $choices = array();
        foreach($this->_drm->getDetails() as $key => $produit) {
            $choices[$key] = sprintf("%s (%s)", $produit->getLibelle("%g% %a% %l% %co% %ce% %la%"), $produit->getCode());
        }

        return $choices;
    }

    public function addProduit() {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }

        $detail = $this->_drm->addProduit($this->values['hashref'], array());

        return $detail;
    }

}