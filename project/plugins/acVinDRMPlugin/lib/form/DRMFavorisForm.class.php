<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMFavorisForm
 *
 * @author mathurin
 */
class DRMFavorisForm extends acCouchdbObjectForm {

    private $drm = null;
    private $favoris = null;
    private $types_mvt = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object;
        $this->favoris = $this->getFavoris();
        $this->types_mvt = ConfigurationClient::getCurrent()->libelle_detail_ligne->get($this->drm->getDetailsConfigKey());
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        $favoris = $this->getFavoris();
        foreach ($this->types_mvt as $keyTypeMvts => $typeMvts) {
            foreach ($typeMvts as $keyMvt => $mvtLibelle) {
                if (($keyTypeMvts != DRMClient::DRM_TYPE_MVT_ENTREES) && ($keyTypeMvts != DRMClient::DRM_TYPE_MVT_SORTIES)) {
                    continue;
                };

                $keyField = $this->buildFieldId($keyTypeMvts, $keyMvt);
                if ((count($favoris[$keyTypeMvts]) < DRMClient::$drm_max_favoris_by_types_mvt[$keyTypeMvts]) || ($favoris->exist($keyTypeMvts) && $favoris->$keyTypeMvts->exist($keyMvt))) {
                    $this->setWidget($keyField, new sfWidgetFormInputHidden(array('default' => false)));
                    $this->widgetSchema->setLabel($keyField, $mvtLibelle);
                    $this->setValidator($keyField, new sfValidatorString(array('required' => false)));
                }
            }
        }
        $this->widgetSchema->setNameFormat('drmFavoris[%s]');
    }

    protected function doUpdateObject($values) {
        $this->drm->remove('favoris');
        foreach ($values as $key => $value) {
            $matches = array();
            if (preg_match('/^favoris_(.*)_(.*)/', $key, $matches)) {
                $type_mvt = $matches[1];
                $mvt = $matches[2];
                $this->drm->getOrAdd('favoris');
                if ((!is_null($value)) && ($value) && ($value == 1)) {
                    $this->drm->getOrAdd('favoris')->getOrAdd($type_mvt)->add($mvt, $this->types_mvt->$type_mvt->$mvt->libelle);
                }
            }
        }
        $this->drm->save();
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        foreach ($this->getFavoris() as $favorisTypeKey => $favorisType) {
            foreach ($favorisType as $favoriKey => $favoris) {
                $this->setDefault($this->buildFieldId($favorisTypeKey, $favoriKey), true);
            }
        }
    }

    private function buildFieldId($keyTypeMvts, $keyMvt) {
        return 'favoris_' . $keyTypeMvts . '_' . $keyMvt;
    }

    private function getFavoris() {
        if (!$this->favoris) {
            $this->favoris = $this->drm->getAllFavoris();
        }
        return $this->favoris;
    }

}
