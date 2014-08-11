<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracHistoryRechercheForm
 * @author mathurin
 */
class VracHistoryRechercheForm extends sfForm {     
    
    
    private $societe;
    private $campagne;
    private $etablissement;
    private $statut;
    
    public function __construct(Societe $societe, $etablissement, $campagne, $statut, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->societe = $societe;
        $this->campagne = $campagne;
        $this->etablissement = $etablissement;
        $this->statut = $statut;
        $defaults['campagne'] = $this->campagne;        
        $defaults['etablissement'] = $this->etablissement;
        $defaults['statut'] = $this->statut;
        
        parent::__construct($defaults, $options, $CSRFSecret);
    }
    
    public function configure()
    {
        $this->setWidget('campagne',new sfWidgetFormChoice(array('choices' => $this->getCampagnes(),'expanded' => false)));     
        $this->setWidget('etablissement', new sfWidgetFormChoice(array('choices' => $this->getEtablissements(),'expanded' => false)));      
        $this->setWidget('statut', new sfWidgetFormChoice(array('choices' => $this->getStatuts(),'expanded' => false)));      
        
        
        $this->widgetSchema->setLabels(array(
            'campagne' => 'Campagne',
            'etablissement' => 'Etablissement',
            'statut' => 'Statut'));
        
        $this->setValidators(array(
            'campagne' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCampagnes()))),
            'etablissement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getEtablissements()))),
            'statut' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getStatuts())))));
                
    }
    
    private function getCampagnes()
    {
        return array_merge(VracClient::getInstance()->listCampagneBySocieteId($this->societe->identifiant));
    }
    
    private function getEtablissements()
    {
        $etablissements = $this->societe->getEtablissementsObj();
        
        $etbArr = array();
        $etbArr['tous'] = 'Tous les établissements';
        foreach ($etablissements as $id => $etbObj) {
            $etbArr[$etbObj->etablissement->identifiant] = $etbObj->etablissement->getDenomination();
        }
        return $etbArr;
    }
    
    private function getStatuts() {
        $all_statuts = VracClient::$statuts_teledeclaration_sorted;
        
        $statuts = array();
        $statuts['tous'] = 'Tous les statuts';
        foreach ($all_statuts as $statut) {
            if($this->societe->isViticulteur() && $statut==VracClient::STATUS_CONTRAT_BROUILLON){
                continue;
            }            
            $statuts[$statut] = VracClient::$statuts_labels[$statut];
        }
        return $statuts;
    }
}

