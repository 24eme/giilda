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
            'hashref' => new sfWidgetFormChoice(array('choices' => $this->getProduits())),
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

    public function getProduits() {
        if (is_null($this->_choices_produits)) {
            $this->_choices_produits = array_merge(array("" => ""),
												   $this->_config->formatProduits('inter-loire', 
            																   	  $this->_drm->getDepartement()));
        }

        return $this->_choices_produits;
    }

    public function addProduit() {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }

        $detail = $this->_drm->addProduit($this->values['hashref'], array());

        return $detail;
    }

}