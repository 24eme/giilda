<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class DRMEtablissementForm extends baseForm {

  private $drm_etablissements = null;

    public function configure()
    {
        $this->setWidget('etablissement_identifiant', new sfWidgetFormChoice(array('choices' =>  $this->getDRMEtablissements()), array('class' => 'autocomplete', 'data-ajax' => $this->getUrlAutocomplete())));

        $this->widgetSchema->setLabels(array(
            'vendeur_identifiant' => 'Sélectionner un etablissement&nbsp;:',
        ));
        
        $this->setValidators(array(
				   'etablissement_identifiant' => new sfValidatorString(array('required' => true)),
				   ));
        
        
        $this->validatorSchema['etablissement_identifiant']->setMessage('required', 'Le choix d\'un etablissement est obligatoire');        
        
        $this->widgetSchema->setNameFormat('etablissement[%s]');
    }
    
    public function getDRMEtablissements()
    {
        $liste = array();

        $etablissement = $this->getEtablissement();

        if ($etablissement) {
            $liste = array($etablissement->identifiant => $etablissement->nom);
        }

        return $liste;
    }

    public function getEtablissement() {
        $identifiant = isset($this->defaults['etablissement_identifiant']) ? $this->defaults['etablissement_identifiant'] : null;

        if ($this->isValid()) {
            $identifiant = $this->values['etablissement_identifiant'];
        }

        if (!$identifiant) {
            return null;
        }

        return EtablissementClient::getInstance()->findByIdentifiant($identifiant);
    }

    public function getUrlAutocomplete() {

        return sfContext::getInstance()->getRouting()->generate('etablissement_autocomplete_byfamilles', array('familles' => EtablissementFamilles::FAMILLE_PRODUCTEUR));
    }
    
}

