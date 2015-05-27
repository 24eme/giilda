<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DRMAddCrdTypeForm extends acCouchdbObjectForm {

    private $drm = null;
    private $typeCouleurs = null;
    private $typeLitrages = null;
    private $typeCrds = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object;
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('couleur_crd', new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getTypeCouleurs())));
        $this->setWidget('litrage_crd', new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getTypeLitrages())));
        $this->setWidget('type_crd', new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getTypeCrds())));
        $this->setWidget('stock_debut', new sfWidgetFormInputText());

        
        $this->widgetSchema->setLabel('couleur_crd', 'Couleur CRD ');
        $this->widgetSchema->setLabel('litrage_crd', 'Litrage ');
                $this->widgetSchema->setLabel('type_crd', 'Type CRD ');
        $this->widgetSchema->setLabel('stock_debut', 'Stock début ');

        $this->setValidator('couleur_crd', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypeCouleurs())), array('required' => "Aucune couleur de CRD n'a été saisi !")));
        $this->setValidator('litrage_crd', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypeLitrages())), array('required' => "Aucun litrage n'a été saisi !")));
        $this->setValidator('type_crd', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypeCrds())), array('required' => "Aucun litrage n'a été saisi !")));
        
        $this->setValidator('stock_debut', new sfValidatorNumber(array('required' => false)));

        $this->widgetSchema->setNameFormat('drmAddTypeForm[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);     
        $couleur = $values['couleur_crd'];
        $litrage = $values['litrage_crd'] * 100000;
        $stock_debut = $values['stock_debut'];
        $type_crd = $values['type_crd'];
        $this->drm->addCrdType($couleur,$litrage,$type_crd,$stock_debut);
        $this->drm->save();
    }

    public function getTypeCouleurs() {
        if (is_null($this->typeCouleurs)) {
            $this->typeCouleurs = array_merge(array("" => ""), DRMClient::$drm_crds_couleurs);
        }
        return $this->typeCouleurs;
    }

    public function getTypeCrds() {
        if (is_null($this->typeCrds)) {
            $this->typeCrds = array_merge(array("" => ""), DRMClient::$drm_type_crds);
        }
        return $this->typeCrds;
    }
    
    public function getTypeLitrages() {
        if (is_null($this->typeLitrages)) {
            $contenances = sfConfig::get('app_vrac_contenances');
            if (!$contenances)
                throw new sfException("Les contenances n'ont pas été renseignée dans le fichier de configuration app.yml");
            $this->typeLitrages = array("" => "");
            foreach ($contenances as $key => $value) {
            $this->typeLitrages[''.$value] = $key;                
            }            
        }
        return $this->typeLitrages;
    }

}
