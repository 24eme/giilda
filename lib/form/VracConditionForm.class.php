<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracConditionForm extends acCouchdbObjectForm {
   
     private $types_contrat = array(VracClient::TYPE_CONTRAT_SPOT => 'Spot',
                                    VracClient::TYPE_CONTRAT_PLURIANNUEL => 'Pluriannuel');
    
     private $prix_variable = array('1' => 'Oui',
                                    '0' => 'Non');

     private $cvo_nature = array(VracClient::CVO_NATURE_MARCHE_DEFINITIF => 'Marché définitif',
                                 VracClient::CVO_NATURE_COMPENSATION => 'Compensation',
                                 VracClient::CVO_NATURE_NON_FINANCIERE => 'Non financière',
                                 VracClient::CVO_NATURE_VINAIGRERIE => 'Vinaigrerie');

     private $cvo_repartition = array('50' => '50/50',
                                      '100' => '100% viticulteur',
                                      '0' => 'Vinaigrerie');
     
    public function configure()
    {
        $this->setWidget('type_contrat', new sfWidgetFormChoice(array('choices' => $this->getTypesContrat(),'expanded' => true)));
        $this->setWidget('prix_variable', new sfWidgetFormChoice(array('choices' => $this->getPrixVariable(),'expanded' => true)));
        $this->setWidget('part_variable', new sfWidgetFormInput());
        $this->setWidget('cvo_nature',  new sfWidgetFormChoice(array('choices' => $this->getCvoNature())));
        $this->setWidget('cvo_repartition',  new sfWidgetFormChoice(array('choices' => $this->getCvoRepartition())));
        $this->setWidget('date_signature', new sfWidgetFormInput());
        $this->setWidget('date_campagne', new sfWidgetFormInput());
        $this->setWidget('commentaires', new sfWidgetFormTextarea(array(),array('style' => 'width: 100%;resize:none;')));
        
        $this->widgetSchema->setLabels(array(
            'type_contrat' => 'Type de contrat',
            'prix_variable' => 'Partie de prix variable ?',
            'part_variable' => 'Part du prix variable sur la quantité',
            'cvo_nature' => 'Nature de la transaction',
            'cvo_repartition' => 'Répartition de la CVO',
            'date_signature' => 'Date de signature',
            'date_campagne' => 'Date de campagne',
            'commentaires' => 'Commentaires :'
        ));
        
        $dateRegexpOptions = array('required' => true,
                'pattern' => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/",
                'min_length' => 10,
                'max_length' => 10);
        $dateRegexpErrors = array('required' => 'Champ obligatoire',
                'invalid' => 'Date invalide (le format doit être jj/mm/aaaa)',
                'min_length' => 'Date invalide (le format doit être jj/mm/aaaa)',
                'max_length' => 'Date invalide (le format doit être jj/mm/aaaa)');
        
        $this->setValidators(array(
            'type_contrat' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypesContrat()))),
            'prix_variable' => new sfValidatorInteger(array('required' => true)),
            'part_variable' => new sfValidatorNumber(array('required' => false, 'max' => 50, 'min' => 0),
                                                     array('max' => 'Part variable %max% max.',
                                                           'min' => 'Part variable %min% min.')),
            'cvo_nature' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCvoNature()))),
            'cvo_repartition' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCvoRepartition()))),
            'date_signature' => new sfValidatorRegex($dateRegexpOptions,$dateRegexpErrors),
            'date_campagne' => new sfValidatorRegex($dateRegexpOptions,$dateRegexpErrors),
            'commentaires' => new sfValidatorString(array('required' => false))
            ));
        
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
