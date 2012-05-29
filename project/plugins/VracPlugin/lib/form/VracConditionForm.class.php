<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracConditionForm extends acCouchdbFormDocumentJson {
   
     private $types_contrat = array('standard' => 'standard',
                                   'pluriannuel' => 'pluriannuel');
    
     private $prix_variable = array('1' => 'Oui','0' => 'Non');

     private $cvo_nature = array('marche_definitif' => 'marché définitif');

     private $cvo_repartition = array('50' => '50/50',
                                      '100' => 'vendeur 0, acheteur 100',
                                      '75' => 'vendeur 25, acheteur 75',
                                      '25' => 'vendeur 75, acheteur 25',
                                      '0' => 'vendeur 100, acheteur 0');
     
    public function configure()
    {
        $this->setWidget('type_contrat', new sfWidgetFormChoice(array('choices' => $this->getTypesContrat(),'expanded' => true)));
        $this->setWidget('prix_variable', new sfWidgetFormChoice(array('choices' => $this->getPrixVariable(),'expanded' => true)));
        $this->setWidget('part_variable', new sfWidgetFormInput());
        $this->setWidget('taux_variation', new sfWidgetFormInput());
        $this->setWidget('cvo_nature',  new sfWidgetFormChoice(array('choices' => $this->getCvoNature())));
        $this->setWidget('cvo_repartition',  new sfWidgetFormChoice(array('choices' => $this->getCvoRepartition())));
        $this->setWidget('date_signature', new sfWidgetFormDate(array('format' => '%day% - %month% - %year%')));
        $this->setWidget('date_stats', new sfWidgetFormDate(array('format' => '%day% - %month% - %year%')));
        
        $this->widgetSchema->setLabels(array(
            'type_contrat' => 'Type de contrat',
            'prix_variable' => 'Partie de prix variable ?',
            'part_variable' => 'Part du prix variable sur la quantité',
            'taux_variation' => 'Taux de variation potentiel du prix définitif',
            'cvo_nature' => 'Nature de la transaction',
            'cvo_repartition' => 'Répartition de la CVO',
            'date_signature' => 'Date de signature',
            'date_stats' => 'Date de statistique'
        ));
        
        $this->setValidators(array(
            'type_contrat' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypesContrat()))),
            'prix_variable' => new sfValidatorInteger(array('required' => true)),
            'part_variable' => new sfValidatorNumber(array('required' => false)),
            'taux_variation' =>  new sfValidatorNumber(array('required' => false)),
            'cvo_nature' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCvoNature()))),
            'cvo_repartition' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCvoRepartition()))),
            'date_signature' => new sfValidatorDate(array('required' => true)),
            'date_stats' => new sfValidatorDate(array('required' => true))        ));   
               
        $this->widgetSchema->setNameFormat('vrac[%s]');
        
    }
    
    public function getTypesContrat()
    {
        return $this->types_contrat;
    }
    
    public function getPrixVariable() 
    {
        return $this->prix_variable;    
    }

    public function getCvoNature()
    {
        return $this->cvo_nature;    
    }

    public function getCvoRepartition() 
    {
        return $this->cvo_repartition;    
    }
    
    public function doUpdateObject($values) 
    {
        parent::doUpdateObject($values);
    }



    
}
?>
