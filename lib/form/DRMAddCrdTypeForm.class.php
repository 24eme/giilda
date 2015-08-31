<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DRMAddCrdTypeForm extends acCouchdbObjectForm {

    private $drm = null;
    private $crdTypeCouleurs = null;
    private $crdTypeLitrages = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object;
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('couleur_crd', new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getCrdTypeCouleurs())));
        $this->setWidget('litrage_crd', new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getCrdTypeLitrages())));
        $this->setWidget('stock_debut', new sfWidgetFormInputText());

        
        $this->widgetSchema->setLabel('couleur_crd', 'Couleur CRD ');
        $this->widgetSchema->setLabel('litrage_crd', 'Litrage ');
        $this->widgetSchema->setLabel('stock_debut', 'Stock début ');

        $this->setValidator('couleur_crd', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCrdTypeCouleurs())), array('required' => "Aucune couleur de CRD n'a été saisi !")));
        $this->setValidator('litrage_crd', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCrdTypeLitrages())), array('required' => "Aucun litrage n'a été saisi !")));
        $this->setValidator('stock_debut', new sfValidatorNumber(array('required' => false)));

        $this->widgetSchema->setNameFormat('drmAddCrdTypeForm[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);     
        $couleur = $values['couleur_crd'];
        $litrage = $values['litrage_crd'] * 100000;
        $stock_debut = $values['stock_debut'];
        $this->drm->addCrdType($couleur,$litrage,$stock_debut);
        $this->drm->save();
    }

    public function getCrdTypeCouleurs() {
        if (is_null($this->crdTypeCouleurs)) {
            $this->crdTypeCouleurs = array_merge(array("" => ""), DRMClient::$drm_crds_couleurs);
        }
        return $this->crdTypeCouleurs;
    }

    public function getCrdTypeLitrages() {
        if (is_null($this->crdTypeLitrages)) {
            $contenances = sfConfig::get('app_vrac_contenances');
            if (!$contenances)
                throw new sfException("Les contenances n'ont pas été renseignée dans le fichier de configuration app.yml");
            $this->crdTypeLitrages = array("" => "");
            foreach ($contenances as $key => $value) {
            $this->crdTypeLitrages[''.$value] = $key;                
            }
            
        }
        return $this->crdTypeLitrages;
    }

}
