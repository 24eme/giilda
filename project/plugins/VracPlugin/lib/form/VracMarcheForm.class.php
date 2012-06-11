<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracMarcheForm extends acCouchdbFormDocumentJson {
   
    private $types_transaction = array(VracClient::TYPE_TRANSACTION_RAISINS => 'Raisins',
                                       VracClient::TYPE_TRANSACTION_MOUTS => 'Moûts',
                                       VracClient::TYPE_TRANSACTION_VIN_VRAC => 'Vin en vrac',
                                       VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE => 'Vin en bouteille');
    
     private $label = array('grains_nobles' => 'Grains nobles',
                                       'primeur' => 'Primeur',
                                       'vin_vrac'=> 'vin en vrac',
                                       'vin_bouteille' => 'Vin en bouteilles',
                                       'Agriculture_biologique' => 'Agriculture Biologique');
     private $contenance = array('75' => '75 cl',
                                       '100' => '1 L',
                                       '150'=> '1.5 L',
                                       '300' => '3 L',
                                       '600' => '6 L');

    
    public function configure()
    {
        $originalArray = array('1' => 'Oui', '0' =>'Non');
        $this->setWidget('original', new sfWidgetFormChoice(array('choices' => $originalArray,'expanded' => true)));
        $types_transaction = $this->types_transaction;
        $this->setWidget('type_transaction', new sfWidgetFormChoice(array('choices' => $types_transaction,'expanded' => true)));
	$produits = ConfigurationClient::getCurrent()->getProduits();
	$this->produits = array(''=>'');
	foreach ($produits as $k => $v) {
	  array_shift($v);
	  $this->produits[$k] = implode(' ', array_filter($v));
	}
         
        
        $this->setWidget('produit', new sfWidgetFormChoice(array('choices' => $this->produits), array('class' => 'autocomplete')));
        $millesimes = ConfigurationClient::getMillesimes();
        $this->setWidget('millesime', new sfWidgetFormChoice(array('choices' => $millesimes,'multiple' => false, 'expanded' => false)));      
        $this->setWidget('domaine', new sfWidgetFormChoice(array('choices' => $this->getDomaines())));
        $this->setWidget('label', new sfWidgetFormChoice(array('choices' => $this->label,'multiple' => true, 'expanded' => true)));
        $this->setWidget('raisin_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('jus_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_quantite', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_contenance', new sfWidgetFormChoice(array('choices' => $this->contenance)));
        $this->setWidget('prix_unitaire', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        
        $this->widgetSchema->setLabels(array(
            'original' => "En attente de l'original ?",
            'type_transaction' => 'Type de transaction',
            'produit' => 'produit',
            'millesime' => 'Millésime',
            'domaine' => 'Nom du domaine',
            'label' => 'label',
            'bouteilles_quantite' => 'Nombre de bouteilles',
            'raisin_quantite' => 'Nombre de raisins',
            'jus_quantite' => 'Volume livré',
            'bouteilles_contenance' => 'Contenance',
            'prix_unitaire' => 'Prix'
        ));
        
        $this->setValidators(array(
            'original' => new sfValidatorInteger(array('required' => true)),
            'type_transaction' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($types_transaction))),
            'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->produits))),
            'millesime' => new sfValidatorInteger(array('required' => true)),
            'domaine' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getDomaines()))),
            'label' => new sfValidatorChoice(array('required' => false,'multiple' => true, 'choices' => array_keys($this->label))),
            'bouteilles_quantite' =>  new sfValidatorInteger(array('required' => false)),
            'raisin_quantite' =>  new sfValidatorNumber(array('required' => false)),
            'jus_quantite' =>  new sfValidatorNumber(array('required' => false)), 
            'bouteilles_contenance' => new sfValidatorInteger(array('required' => true)),
            'prix_unitaire' => new sfValidatorNumber(array('required' => true))
                ));
        $this->widgetSchema->setNameFormat('vrac[%s]');
        
    }
    
    public function doUpdateObject($values) 
    {
        parent::doUpdateObject($values);
        $this->getObject()->update();
    }
    
    public function getDomaines() {
        $domaine_arr = array();
        foreach ($this->getObject()->getVendeurObject()->domaines as $domaine) $domaine_arr[$domaine] = $domaine;
        return $domaine_arr;
    }
    
}
?>
