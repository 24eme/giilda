<?php

class CompteTeledeclarantCreationForm extends CompteTeledeclarantForm {
    private $typeCompte;

    public function __construct($doc, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->typeCompte = $doc->getSociete()->type_societe;

        $defaults['cvi'] = $doc->getSociete()->getEtablissementPrincipal()->cvi;
        $defaults['ppm'] = $doc->getSociete()->getEtablissementPrincipal()->ppm;
        $defaults['siret'] = $doc->getSociete()->siret;
        parent::__construct($doc, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->getValidator('mdp1')->setOption('required', true);
        $this->getValidator('mdp2')->setOption('required', true);
        if ($this->typeCompte == SocieteClient::TYPE_COURTIER) {
            $this->setWidget('carte_pro', new sfWidgetFormInputText());
            $this->getWidget('carte_pro')->setLabel("Numéro de carte professionnelle :");
            $this->setValidator('carte_pro', new sfValidatorString(array('required' => false)));
        }

        if ($this->typeCompte == SocieteClient::TYPE_OPERATEUR) {
            $this->setWidget('siret', new sfWidgetFormInputText());
            $this->getWidget('siret')->setLabel("Numéro de SIRET :");
            $this->setValidator('siret', new sfValidatorRegex(array('required' => false,
                'pattern' => "/^[0-9]{14}$/",
                'min_length' => 14,
                'max_length' => 14), array('required' => 'Le numéro de SIRET est obligatoire',
                'invalid' => 'Le numéro de SIRET doit être constitué de 14 chiffres',
                'min_length' => 'Le numéro de SIRET doit être constitué de 14 chiffres',
                'max_length' => 'Le numéro de SIRET doit être constitué de 14 chiffres')));

            $this->setWidget('cvi', new sfWidgetFormInputText());
            $this->getWidget('cvi')->setLabel("Numéro CVI/EVV :");
            $this->setValidator('cvi', new sfValidatorRegex(array('required' => false,
                'pattern' => "/^[0-9A-Za-z]{10}$/",
                'min_length' => 10,
                'max_length' => 10), array('required' => "Le numéro de CVI/EVV est obligatoire",
                'invalid' => "Le numéro CVI/EVV doit être constitué de 10 caractères alphanumériques",
                'min_length' => "Le numéro CVI/EVV doit être constitué de 10 caractères alphanumériques",
                'max_length' => "Le numéro CVI/EVV doit être constitué de 10 caractères alphanumériques")));
            $this->setWidget('ppm', new sfWidgetFormInputText());
            $this->getWidget('ppm')->setLabel("Numéro PPM :");
            $this->setValidator('ppm', new sfValidatorRegex(array('required' => false,
                'pattern' => "/^[A-Za-z][0-9A-Za-z]{8}$/",
                'min_length' => 9,
                'max_length' => 9), array('required' => "Le numéro de CVI/EVV est obligatoire",
                'invalid' => "Le numéro PPM doit être constitué de 9 caractères alphanumériques",
                'min_length' => "Le numéro PPM doit être constitué de 9 caractères alphanumériques",
                'max_length' => "Le numéro PPM doit être constitué de 9 caractères alphanumériques")));
        }
    }

    public function save() {
        parent::save();
        $societe = SocieteClient::getInstance()->find($this->getDocument()->id_societe);

        $etbPrincipal = $societe->getEtablissementPrincipal();
        if (($this->typeCompte == SocieteClient::TYPE_COURTIER) && ($this->getValue('carte_pro'))) {
            if ($etbPrincipal->exist('carte_pro') && $this->getValue('carte_pro') != $etbPrincipal->carte_pro) {
                $this->updatedValues['carte_pro'] = array($etbPrincipal->carte_pro, $this->getValue('carte_pro'));
            }
            if (!$this->getOption('noSaveChangement', false)) {
                $etbPrincipal->carte_pro = $this->getValue('carte_pro');
                $etbPrincipal->save();
            }
        }
        if ($this->typeCompte == SocieteClient::TYPE_OPERATEUR && $this->getValue('num_accises')) {
            if ($etbPrincipal->exist('num_accises') && $this->getValue('num_accises') != $etbPrincipal->num_accises) {
                $this->updatedValues['num_accises'] = array($etbPrincipal->num_accises, $this->getValue('num_accises'));
            }
            if (!$this->getOption('noSaveChangement', false)) {
                $etbPrincipal->no_accises = strtoupper($this->getValue('num_accises'));
                $etbPrincipal->save();
            }
        }

        if (($this->typeCompte == SocieteClient::TYPE_OPERATEUR) && ($this->getValue('siret'))) {
            if ($societe->exist('siret') && $this->getValue('siret') != $societe->siret) {
                $this->updatedValues['siret'] = array($societe->siret, $this->getValue('siret'));
            }
            if (!$this->getOption('noSaveChangement', false)) {
                $societe->siret = $this->getValue('siret');
                $societe->save();
            }
        }
        if (($this->typeCompte == SocieteClient::TYPE_OPERATEUR) && ($this->getValue('cvi'))) {
            if ($etbPrincipal->exist('cvi') && $this->getValue('cvi') != $etbPrincipal->cvi) {
                $this->updatedValues['cvi'] = array($etbPrincipal->cvi, $this->getValue('cvi'));
            }
            if (!$this->getOption('noSaveChangement', false)) {
                $etbPrincipal->cvi = $this->getValue('cvi');
                $etbPrincipal->save();
            }
        }
        if (($this->typeCompte == SocieteClient::TYPE_OPERATEUR) && ($this->getValue('ppm'))) {
            if ($etbPrincipal->exist('ppm') && $this->getValue('ppm') != $etbPrincipal->ppm) {
                $this->updatedValues['ppm'] = array($etbPrincipal->ppm, $this->getValue('ppm'));
            }
            if (!$this->getOption('noSaveChangement', false)) {
                $etbPrincipal->ppm = $this->getValue('ppm');
                $etbPrincipal->save();
            }
        }
    }

    public function getTypeCompte() {

        return $this->typeCompte;
    }

}
