<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteModificationEtbForm
 * @author mathurin
 */
class CompteModificationEtbForm extends CompteModificationForm {

    private $etablissement = null;

    public function __construct(Compte $compte, $options = array(), $CSRFSecret = null) {
        parent::__construct($compte);
        $this->defaults['adresse_societe'] = $adresse_societe;
        $this->etablissement = $etb;
    }

    public function configure() {
        $this->setWidget('adresse_societe', new sfWidgetFormChoice(array('choices' => $this->getAdresseSociete(), 'expanded' => true, 'multiple' => false)));
        $this->widgetSchema->setLabel('adresse_societe', 'Même adresse que la société ?');
        $this->setValidator('adresse_societe', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getAdresseSociete()))));
        parent::configure();
    }

    public function getAdresseSociete() {
        return array(1 => 'oui', 0 => 'non');
    }

    public function processValues($values) {
        if (isset($values['adresse_societe']) && $values['adresse_societe']) {
            foreach ($values as $key => $value) {
                if ($key != 'adresse_societe')
                    unset($values[$key]);
            }
        }
    }

    public function doUpdateObject($values) {
        $compte = $this->getObject();
        $societe = SocieteClient::getInstance()->find($compte->id_societe);
        var_dump($values['adresse_societe']); exit;
        if ($values['adresse_societe']) {
            if ($this->etablissement && $this->etablissement->compte) {
                $compte = SocieteClient::getInstance()->find($this->etablissement->compte);
                $compte->delete();
                $this->etablissement->compte = null;
            }
        } else {
            if ($this->etablissement && !($this->etablissement->compte))
            {
                $new_compte = CompteClient::getInstance()->createCompte($compte);
                $this->etablissement->compte = $new_compte->identifiant;
                $this->etablissement->save();
                $new_compte->fromArray($values);
            }
        $this->getObject()->fromArray($values);
        }
    }
    
}