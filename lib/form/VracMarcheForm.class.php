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
    
     private $label = array('grains_nobles' => 'Grains nobles',
                            'primeur' => 'Primeur',
                            'Agriculture_biologique' => 'Agriculture Biologique');
     private $contenance = array('75 cl' => 0.0075,
                                       '1 L' => 0.01,
                                       '1.5 L'=> 0.015,
                                       '3 L' => 0.03,
                                       'BIB 3 L' => 0.03,
                                       '6 L' => 0.06);

    
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
        
        //$millesimes = ConfigurationClient::getMillesimes();
        
        $this->setWidget('millesime', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));      
        $this->setWidget('domaine', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('label', new sfWidgetFormChoice(array('choices' => $this->label,'multiple' => true, 'expanded' => true)));
        $this->setWidget('raisin_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('jus_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_quantite', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_contenance', new sfWidgetFormChoice(array('choices' => array_keys($this->contenance))));
        $this->setWidget('prix_unitaire', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        
        $this->widgetSchema->setLabels(array(
            'original' => "En attente de l'original ?",
            'type_transaction' => 'Type de transaction',
            'produit' => 'produit',
            'millesime' => 'Millésime',
            'domaine' => 'Nom du domaine',
            'label' => 'label',
            'bouteilles_quantite' => 'Quantité',
            'raisin_quantite' => 'Nombre de raisins',
            'jus_quantite' => 'Volume proposé',
            'bouteilles_contenance' => 'Contenance',
            'prix_unitaire' => 'Prix'
        ));
        
        $this->setValidators(array(
            'original' => new sfValidatorInteger(array('required' => true)),
            'type_transaction' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($types_transaction))),
            'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->produits))),
            'millesime' => new sfValidatorInteger(array('required' => false)),
            'domaine' => new sfValidatorString(array('required' => false)),
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
