<?php

class DRMAjoutReserveInterproForm extends acCouchdbForm
{
    protected $_drm;
    protected $_config;
    protected $_produits_existant;
    protected $_isTeledeclarationMode;
    protected $_nameFormat;

    public function __construct(DRM $drm, _ConfigurationDeclaration $config, $isTeledeclarationMode = false, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->_drm = $drm;
        $this->_config = $config;
        $this->_isTeledeclarationMode = $isTeledeclarationMode;
        $this->_nameFormat = 'produit_' . $this->_config->getKey();

        parent::__construct($drm, $defaults, $options, $CSRFSecret);
    }

    public function configure()
    {
        $this->setWidget('hashref', new bsWidgetFormChoice(array('choices' => $this->getProduitsExistant()), array("class" => "form-control select2")));
        $this->setWidget('volume', new bsWidgetFormInputFloat(array(), array('placeholder' => '0.00', 'autocomplete' => 'off')));

        $this->setValidators(array(
            'hashref' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduitsExistant())),array('required' => "Aucun produit n'a été saisi !")),
            'volume' => new sfValidatorNumber(array('required' => true), array('required' => "Aucun volume n'a été saisi !")),
        ));

        $this->widgetSchema->setNameFormat($this->_nameFormat . '[%s]');
    }

    public function getProduitsExistant() {
        if(is_null($this->_produits_existant)) {
            $this->_produits_existant = array();
            foreach($this->_drm->getProduitsDetails($this->_isTeledeclarationMode) as $key => $produit) {
                if(!$produit->hasMovements()) { continue; }
                $this->_produits_existant[$key] = sprintf("%s", $produit->getLibelle("%format_libelle% %la%"));
                if($produit->getCodeProduit()) {
                    $this->_produits_existant[$key] .= sprintf(" (%s)", $produit->getCodeProduit());
                }
            }
        }
        return $this->_produits_existant;
    }

    public function save() {
        $values = $this->getValues();
        $drm = $this->getDocument();

        $produit = $drm->get($values['hashref'])->getCepage();
        $produit->add('reserve_interpro', $values['volume']);

        $drm->save();
    }
}
