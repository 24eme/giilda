<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracSoussigneForm extends acCouchdbObjectForm {

   private $vendeurs = null;
   private $acheteurs = null;
   private $mandataires = null;
   
    public function configure()
    {
        
        $this->setWidget('vendeur_identifiant', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-inter-loire', 'familles' => EtablissementFamilles::FAMILLE_PRODUCTEUR)));
                 
        $this->setWidget('acheteur_identifiant', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-inter-loire','familles' =>  EtablissementFamilles::FAMILLE_NEGOCIANT)));
        
        $this->setWidget('interne', new sfWidgetFormInputCheckbox());
        
        $this->setWidget('mandataire_exist', new sfWidgetFormInputCheckbox());        
        
        $mandatant_identifiantChoice = array('vendeur' => 'vendeur','acheteur' => 'acheteur');
        
        $this->setWidget('mandatant', new sfWidgetFormChoice(array('expanded' => true, 'multiple'=> true , 'choices' => $mandatant_identifiantChoice)));
                
        $this->setWidget('mandataire_identifiant', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-inter-loire', 'familles' =>  EtablissementFamilles::FAMILLE_COURTIER)));
        
        $this->widgetSchema->setLabels(array(
            'vendeur_famille' => '',
            'vendeur_identifiant' => 'Sélectionner un vendeur :',
            'acheteur_famille' => '',
            'acheteur_identifiant' => 'Sélectionner un acheteur :',
            'interne' => 'Cocher si le contrat est interne',
            'mandataire_identifiant' => 'Sélectionner un mandataire :',
            'mandataire_exist' => "Décocher s'il n'y a pas de mandataire",
            'mandatant' => 'Mandaté par : '
        ));
        
        $this->setValidators(array(
            'vendeur_identifiant' => new ValidatorEtablissement(array('required' => true, 'familles' => EtablissementFamilles::FAMILLE_PRODUCTEUR)),
            'acheteur_identifiant' => new ValidatorEtablissement(array('required' => true, 'familles' => EtablissementFamilles::FAMILLE_NEGOCIANT)),
            'interne' => new sfValidatorBoolean(array('required' => false)),
            'mandataire_identifiant' => new ValidatorEtablissement(array('required' => false, 'familles' => EtablissementFamilles::FAMILLE_COURTIER)),
            'mandataire_exist' => new sfValidatorBoolean(array('required' => false)),
            'mandatant' => new sfValidatorChoice(array('required' => false,'multiple'=> true, 'choices' => array_keys($mandatant_identifiantChoice)))
            ));
        
        
        $this->validatorSchema['vendeur_identifiant']->setMessage('required', 'Le choix d\'un vendeur est obligatoire');        
        $this->validatorSchema['acheteur_identifiant']->setMessage('required', 'Le choix d\'un acheteur est obligatoire');             
        
        $this->widgetSchema->setNameFormat('vrac[%s]');
    }
    
    public function doUpdateObject($values) {
        if(isset($values['mandataire_exist']) && !$values['mandataire_exist'])
        {
            $values['mandataire_identifiant'] = null;
        }
        if(!isset($values['mandataire_identifiant']) || !$values['mandataire_identifiant'])
        {
            $values['mandatant'] = null;
            $values['mandataire_exist'] = false;
        }
        parent::doUpdateObject($values);
        $this->getObject()->setInformations();
    }


    public function getUrlAutocomplete($famille) {

        return sfContext::getInstance()->getRouting()->generate('etablissement_autocomplete_byfamilles', array('familles' => $famille));
    }
}

