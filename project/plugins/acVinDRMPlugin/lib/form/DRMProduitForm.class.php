<?php

class DRMProduitForm extends acCouchdbForm
{
	protected $_choices_produits;
    protected $_drm = null;
    protected $_config = null;
	protected $_detailsKey = null;
    protected $_produits_existant = null;
    protected $_isTeledeclarationMode = false;

    public function __construct(DRM $drm, _ConfigurationDeclaration $config, $detailsKey, $isTeledeclarationMode = false, $options = array(), $CSRFSecret = null) {
		$this->_drm = $drm;
        $this->_interpro = $drm->getInterpro();
        $this->_config = $config;
				$this->_detailsKey = $detailsKey;
        $this->_isTeledeclarationMode = $isTeledeclarationMode;
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
        $produit_existant = $this->getProduitsExistant();
        $produits = $this->_drm->getConfigProduits($this->_isTeledeclarationMode);

        foreach($produits as $hash => $produit) {
            if(array_key_exists($hash."/".$this->_detailsKey."/DEFAUT", $produit_existant)) {
                unset($produits[$hash]);
            }
        }

        return $produits;
    }

    public function getProduitsExistant() {
        if(is_null($this->_produits_existant)) {
            $this->_produits_existant = array();
            foreach($this->_drm->getProduitsDetails($this->_isTeledeclarationMode, $this->_detailsKey) as $key => $produit) {
                if(!$produit->hasMovements()) { continue; }
                $this->_produits_existant[$key] = sprintf("%s", $produit->getLibelle("%format_libelle% %la%"));
                if($produit->getCodeProduit()) {
                    $this->_produits_existant[$key] .= sprintf(" (%s)", $produit->getCodeProduit());
                }
            }
        }

        return $this->_produits_existant;
    }

    public function addProduit() {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }
        $detail = $this->_drm->addProduit($this->values['hashref'], $this->_detailsKey, array());

        return $detail;
    }

}
