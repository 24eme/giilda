<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracMarcheForm extends acCouchdbObjectForm {
   
    private $types_transaction = array(VracClient::TYPE_TRANSACTION_RAISINS => 'Raisins',
                                       VracClient::TYPE_TRANSACTION_MOUTS => 'Moûts',
                                       VracClient::TYPE_TRANSACTION_VIN_VRAC => 'Vin en vrac',
                                       VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE => 'Vin conditionné');
    
    private $contient_domaine = array('domaine' => 'Domaine', 'generique' =>'Générique');

    protected $_choices_produits;
     
    
    public function configure()
    {
        $originalArray = array('1' => 'Oui', '0' =>'Non');
        $this->setWidget('original', new sfWidgetFormChoice(array('choices' => $originalArray,'expanded' => true)));
        $types_transaction = $this->types_transaction;
        $this->setWidget('type_transaction', new sfWidgetFormChoice(array('choices' => $types_transaction,'expanded' => true)));
		
        $this->getDomaines();
        $this->setWidget('produit', new sfWidgetFormChoice(array('choices' => $this->getProduits()), array('class' => 'autocomplete')));      
        $this->setWidget('millesime', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));        
        $this->setWidget('contient_domaine', new sfWidgetFormChoice(array('choices' => $this->getContientDomaines(),'expanded' => true)));
        $this->setWidget('domaine', new sfWidgetFormChoice(array('choices' => $this->domaines), array('class' => 'autocomplete permissif')));
        $this->setWidget('label', new sfWidgetFormChoice(array('choices' => $this->getLabels(),'multiple' => true, 'expanded' => true)));
        $this->setWidget('raisin_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('jus_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_quantite', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $contenance = array();
        foreach (array_keys(VracClient::$contenance) as $c) {
            $contenance[$c] = $c;
        }
        $this->setWidget('bouteilles_contenance_libelle', new sfWidgetFormChoice(array('choices' => $contenance)));
        $this->setWidget('prix_unitaire', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        
        $this->widgetSchema->setLabels(array(
            'original' => "En attente de l'original ?",
            'type_transaction' => 'Type de transaction',
            'produit' => 'produit',
            'millesime' => 'Millésime',
            'contient_domaine' => 'Type',
            'domaine' => 'Nom du domaine',
            'label' => 'label',
            'bouteilles_quantite' => 'Quantité',
            'raisin_quantite' => 'Quantité de raisins',
            'jus_quantite' => 'Volume proposé',
            'bouteilles_contenance_libelle' => 'Contenance',
            'prix_unitaire' => 'Prix'
        ));
        
        $this->setValidators(array(
            'original' => new sfValidatorInteger(array('required' => true)),
            'type_transaction' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($types_transaction))),
            'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits()))),
            'millesime' => new sfValidatorInteger(array('required' => false, 'min' => 1980, 'max' => $this->getCurrentYear())),
            'contient_domaine' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getContientDomaines()))),
            'domaine' => new sfValidatorString(array('required' => false)),
            'label' => new sfValidatorChoice(array('required' => false,'multiple' => true, 'choices' => array_keys($this->getLabels()))),
            'bouteilles_quantite' =>  new sfValidatorInteger(array('required' => false)),
            'raisin_quantite' =>  new sfValidatorNumber(array('required' => false)),
            'jus_quantite' =>  new sfValidatorNumber(array('required' => false)), 
            'bouteilles_contenance_libelle' => new sfValidatorString(array('required' => true)),
            'prix_unitaire' => new sfValidatorNumber(array('required' => true))
             ));
                        
        $this->validatorSchema['produit']->setMessage('required', 'Le choix d\'un produit est obligatoire');        
        $this->validatorSchema['prix_unitaire']->setMessage('required', 'Le prix doit être renseigné');  
        $this->validatorSchema['millesime']->setMessage('min', 'Le millésime doit être supérieur à 1980');        
        $this->validatorSchema['millesime']->setMessage('max', 'Le millésime doit être inférieur à '.$this->getCurrentYear());
        
        $this->widgetSchema->setNameFormat('vrac[%s]');
        
    }

    protected function updateDefaultsFromObject() {
    	parent::updateDefaultsFromObject();
    	$this->setDefault('original', 0);
    }

    public function getProduits() {
        if (is_null($this->_choices_produits)) {
            $this->_choices_produits = array_merge(array("" => ""),
            $this->getConfig()->formatProduits());
        }

        return $this->_choices_produits;
    }
    
    public function doUpdateObject($values) 
    {
        parent::doUpdateObject($values);
        $this->getObject()->update();
    }
    
    public function getDomaines() {
        $domaines = VracDomainesView::getInstance()->findDomainesByVendeur($this->getObject()->vendeur_identifiant);
        $this->domaines = array(''=>'');
        foreach ($domaines->rows as $resultDomaine) {
            $d = $resultDomaine->key[VracDomainesView::KEY_DOMAINE];
            $this->domaines[$d] = $d;
	}
    }

    public function getContientDomaines() 
    {
        return $this->contient_domaine;
    }

    protected function getConfig() {

    	return ConfigurationClient::getCurrent();
    }
    
    protected function getLabels() {
      return $this->getConfig()->labels->toArray();
    }
    
    private function getCurrentYear() {
        $year = date('Y');
        return (int) $year+1;
        
    }
}

