<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DRMAddCrdTypeForm extends acCouchdbObjectForm {

    private $drm = null;
    private $regimeCrds = array();
    private $typesCouleurs = null;
    private $typesLitrages = null;
    private $defaultGenre = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object;
        $this->regimeCrds = $this->drm->getRegimesCrds();        
        $this->defaultGenre = $options['genre'];
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->regimeCrds as $regime) {

            $this->setWidget('couleur_crd_' . $regime, new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getTypeCouleurs())));
            $this->setWidget('litrage_crd_' . $regime, new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getTypeLitrages())));
            $this->setWidget('stock_debut_' . $regime, new sfWidgetFormInputText());
            $this->setWidget('genre_crd_' . $regime, new sfWidgetFormChoice(array('expanded' => true, 'multiple' => false, 'choices' => $this->getGenres())));


            $this->widgetSchema->setLabel('couleur_crd_' . $regime, 'Couleur CRD ');
            $this->widgetSchema->setLabel('litrage_crd_' . $regime, 'Litrage ');
            $this->widgetSchema->setLabel('stock_debut_' . $regime, 'Stock début ');
            $this->widgetSchema->setLabel('genre_crd_' . $regime, 'Type de produit ');

            $this->setValidator('couleur_crd_' . $regime, new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypeCouleurs())), array('required' => "Aucune couleur de CRD n'a été saisi !")));
            $this->setValidator('litrage_crd_' . $regime, new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypeLitrages())), array('required' => "Aucun litrage n'a été saisi !")));
            $this->setValidator('stock_debut_' . $regime, new sfValidatorNumber(array('required' => false)));
            $this->setValidator('genre_crd_' . $regime, new sfValidatorChoice(array('multiple' => false, 'required' => true, 'choices' => array_keys($this->getGenres())), array('required' => "Aucun genre n'a été saisi !")));
           
            $this->setDefault('genre_crd_' . $regime, $this->defaultGenre);
        }

        $this->widgetSchema->setNameFormat('drmAddTypeForm[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->regimeCrds as $regime) {
            $couleur = $values['couleur_crd_' . $regime];
            $litrage = $values['litrage_crd_' . $regime] * 100000;
            $genre = $values['genre_crd_' . $regime];
            $stock_debut = $values['stock_debut_' . $regime];
            if ($genre && $couleur && $litrage) {
                $this->drm->getOrAdd('crds')->getOrAdd($regime)->getOrAddCrdNode($genre, $couleur, $litrage, $stock_debut);
            }
        }
        $this->drm->save();
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
//        if (count($this->getGenres()) <= 1) {
//            $genreCrdKeys = array_keys($this->getGenres());
//            $genreCrd = $genreCrdKeys[0];           
//            foreach ($this->regimeCrds as $regime) {
//                $this->setDefault('genre_crd_' . $regime, $genreCrd);
//            }
//        }
    }

    public function getTypeCouleurs() {
        if (is_null($this->typesCouleurs)) {
            $this->typesCouleurs = array_merge(array("" => ""), DRMClient::$drm_crds_couleurs);
        }
        return $this->typesCouleurs;
    }

    public function getTypeLitrages() {
        if (is_null($this->typesLitrages)) {
            $contenances = sfConfig::get('app_vrac_contenances');
            if (!$contenances)
                throw new sfException("Les contenances n'ont pas été renseignée dans le fichier de configuration app.yml");
            $this->typesLitrages = array("" => "");
            foreach ($contenances as $key => $value) {
                $this->typesLitrages['' . $value] = $key;
            }
        }
        return $this->typesLitrages;
    }

    public function getGenres() {
        return array('TRANQ' => 'Vins tranquilles', 'MOUSSEUX' => 'Vins mousseux');
    }

    public function getRegimeCrds() {
        return $this->regimeCrds;
    }

}
