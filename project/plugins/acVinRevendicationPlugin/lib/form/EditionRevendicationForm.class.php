<?php

class EditionRevendicationForm extends acCouchdbForm {
    
    protected $_choices_produits;
    protected $revendication;
    protected $identifiant;
    protected $row;
    protected $produit_hash;
    protected $volume;
    protected $num_ligne;
    protected $code_douane;


    public function __construct(acCouchdbDocument $revendication, $identifiant, $rev, $row, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $defaults = array();
        $this->revendication = $revendication;
        $this->identifiant = $identifiant;
        $this->row = $row;        
        $volumeProduitObj = $this->getVolumeProduitObj($this->revendication, $this->identifiant,$this->row);
        $this->code_douane = $volumeProduitObj->produit->key;
        $this->produit_hash = substr($volumeProduitObj->produit->produit_hash, 1, strlen($volumeProduitObj->produit->produit_hash));
        $this->volume = sprintf("%01.02f", round($volumeProduitObj->volume->volume, 2));
        $this->num_ligne = $volumeProduitObj->volume->num_ligne;
        $defaults['produit_hash'] = $this->produit_hash;
        $defaults['volume'] = $this->volume;
        parent::__construct($revendication, $defaults, $options, $CSRFSecret);
   }
    
    public function configure() {
        parent::configure();
        $this->setWidget('produit_hash', new sfWidgetFormChoice(array('choices' => $this->getProduits()), array('class' => 'autocomplete')));
        $this->setWidget('volume', new sfWidgetFormInput());
        $this->setValidator('produit_hash', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits()))));
        $this->setValidator('volume', new sfValidatorNumber(array('required' => true)));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('revendication_edition_row[%s]');
    }
    
    public function getVolumeProduitObj($revendication,$identifiant,$row) {
        $result = new stdClass();
        $result->produit = $revendication->getProduitNode($identifiant,$row);
        $result->volume = $result->produit->volumes->get($row);
        return $result;
    }
    
    public function getProduits() {
        if (is_null($this->_choices_produits)) {
            $this->_choices_produits = array_merge(array("" => ""),
            $this->getConfig()->formatProduits());
        }
        return $this->_choices_produits;
    }
    
    protected function getConfig() {

    	return ConfigurationClient::getCurrent();
    }
    
    public function doUpdate() {        
        $this->revendication->updateProduit($this->identifiant,$this->code_douane, $this->getConfig()->get($this->values['produit_hash'])->getCodeDouane()); 
        $this->revendication->updateVolume($this->identifiant,$this->getConfig()->get($this->values['produit_hash'])->getCodeDouane() , $this->row, $this->num_ligne,  $this->values['volume']);
    }
}