<?php

class AddAliasToProduitForm  extends acCouchdbObjectForm {
    
    protected $alias;
    protected $_choices_produits;
    protected $date;


    public function __construct(acCouchdbJson $object, $alias, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
        $this->date = $object->getDate();
        $this->alias = $alias;
    }


    public function configure() {
        parent::configure();
        $this->setWidget('produit_hash', new sfWidgetFormChoice(array('choices' => $this->getProduits()), array('class' => 'autocomplete')));
        $this->setValidator('produit_hash', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits()))));
        
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('addAlias[%s]');
    }
    
    public function getProduits() {
        if (is_null($this->_choices_produits)) {
            $this->_choices_produits = array_merge(array("" => ""),
            $this->getConfig()->formatProduits($this->date, "%format_libelle% (%code_produit%)", array(_ConfigurationDeclaration::ATTRIBUTE_CVO_FACTURABLE)));
        }
        return $this->_choices_produits;
    }
    
    protected function getConfig() {

    	return ConfigurationClient::getConfiguration($this->date);
    }
    
    public function getAlias(){
        return $this->alias;
    }
    
    public function doUpdate() {        
        $configuration = $this->getConfig();
        $configuration->updateAlias($this->values['produit_hash'],$this->alias);
        $configuration->save();
    }
    
}