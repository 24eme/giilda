<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SocieteEtablissementChoiceForm extends baseForm {

    protected $societe;
    protected $etablissement;

    public function __construct(Etablissement $etablissement, $defaults = array(), $options = array(), $CSRFSecret = null)
    {
        $this->etablissement = $etablissement;
        $this->societe = $etablissement->getSociete();
        $this->etablissements = $this->societe->getEtablissementsObj(false);
        $defaults['etablissementChoice'] = $this->etablissement->identifiant;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure()
    {
            $this->setWidget('etablissementChoice', new sfWidgetFormChoice(array('choices' => $this->getEtablissements(true))));
            $this->setValidator('etablissementChoice', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getEtablissements()))));
            $this->widgetSchema->setLabel('etablissementChoice', 'Choisir un établissement :');
            $this->validatorSchema['etablissementChoice']->setMessage('invalid', 'Le choix d\'un établissement est obligatoire.');
            $this->widgetSchema->setNameFormat('societe_etablissement[%s]');
    }

    public function getEtablissements($include_libelle = false) {
        $etablissements = array();
        if($include_libelle){
            $etablissements['0'] = 'Choisir un établissement';
        }
        foreach ($this->etablissements as $key => $etablissementObj) {
          if($etablissementObj->etablissement){
            $etb = $etablissementObj->etablissement;
            if($etb->famille == 'NEGOCIANT_PUR'){ continue; }
            $etbsView = EtablissementAllView::getInstance()->findByEtablissement($etb->identifiant);
            foreach ($etbsView as $key => $etbView) {
              $etablissements[$etb->identifiant] = EtablissementAllView::getInstance()->makeLibelle($etbView);
            }
          }
        }
        return $etablissements;
    }

}
