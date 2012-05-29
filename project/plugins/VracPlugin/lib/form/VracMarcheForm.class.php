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
   
    private $types_transaction = array('raisins' => 'raisins',
                                       'mouts' => 'mouts',
                                       'vin_vrac'=> 'vin en vrac',
                                       'vin_bouteille' => 'Vin en bouteilles');
    
     private $produits = array('prod0' => 'Gros raisins','prod1' => 'petits raisins','prod3' => 'Vin bon','prod4' => 'Vin pas bon');

     private $label = array('grains_nobles' => 'Grains nobles',
                                       'primeur' => 'Primeur',
                                       'vin_vrac'=> 'vin en vrac',
                                       'vin_bouteille' => 'Vin en bouteilles');
     private $contenance = array('75' => '75 cl',
                                       '100' => '1 L',
                                       '150'=> '1.5 L',
                                       '300' => '3 L',
                                       '600' => '6 L');

    
    public function configure()
    {
        $types_transaction = $this->types_transaction;
        $this->setWidget('type_transaction', new sfWidgetFormChoice(array('choices' => $types_transaction,'expanded' => true)));
        $this->setWidget('produit', new sfWidgetFormChoice(array('choices' => $this->produits)));
        $this->setWidget('label', new sfWidgetFormChoice(array('choices' => $this->label,'multiple' => true, 'expanded' => true)));
        $this->setWidget('raisin_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('jus_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_quantite', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_contenance', new sfWidgetFormChoice(array('choices' => $this->contenance)));
        $this->setWidget('prix_unitaire', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        
        $this->widgetSchema->setLabels(array(
            'type_transaction' => 'Type de transaction',
            'produit' => 'produit',
            'label' => 'label',
            'bouteilles_quantite' => 'Nombre de bouteilles',
            'raisin_quantite' => 'Nombre de raisins',
            'jus_quantite' => 'Volume des moûts',
            'bouteilles_contenance' => 'Contenance',
            'prix_unitaire' => 'Prix'
        ));
        
        $this->setValidators(array(
            'type_transaction' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($types_transaction))),
            'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->produits))),
            'label' => new sfValidatorChoice(array('multiple' => true, 'choices' => array_keys($this->label))),
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
    
}
?>
