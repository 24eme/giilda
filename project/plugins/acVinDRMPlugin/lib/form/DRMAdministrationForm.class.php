<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMAdministrationForm
 *
 * @author mathurin
 */
class DRMAdministrationForm extends acCouchdbObjectForm {

    private $drm = null;
    private $detailsSortiesVrac = null;
    private $detailsSortiesExport = null;
    private $docTypesList = array();

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object;
        $this->getDocTypes();
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->docTypesList as $docType) {
            $keyDebut = $docType . '_debut';
            $keyFin = $docType . '_fin';
            $this->setWidget($keyDebut, new sfWidgetFormInputText());
            $this->setWidget($keyFin, new sfWidgetFormInputText());

            $this->setValidator($keyDebut, new sfValidatorString(array('required' => false)));
            $this->setValidator($keyFin, new sfValidatorString(array('required' => false)));

            $this->widgetSchema->setLabel($keyDebut, DRMClient::$drm_documents_daccompagnement[$docType] . ' début');
            $this->widgetSchema->setLabel($keyFin, DRMClient::$drm_documents_daccompagnement[$docType] . ' fin');
        }
        foreach ($this->drm->getReleveNonAppurement() as $key => $object) {                
            $this->embedForm($key, new DRMReleveNonAppurementItemForm($object));
        }
        $this->widgetSchema->setNameFormat('drmAdministrationForm[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->docTypesList as $docType) {
            $this->drm->getOrAdd('documents_administration')->getOrAdd($docType)->debut = $values[$docType . '_debut'];
            $this->drm->getOrAdd('documents_administration')->getOrAdd($docType)->fin = $values[$docType . '_fin'];
        }
        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->save();
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

        if ($this->drm->exist('documents_administration') && $this->drm->documents_administration) {
            $administrationNode = $this->drm->documents_administration;
            foreach ($this->docTypesList as $docType) {
                if ($administrationNode->exist($docType) && $administrationNode->{$docType}) {
                    $docNode = $administrationNode->{$docType};
                    $this->setDefault($docType . '_debut', $docNode->debut);
                    $this->setDefault($docType . '_fin', $docNode->fin);
                }
            }
        }
    }

    public function getDocTypes() {
        $this->detailsSortiesVrac = $this->drm->getVracs();
        $this->detailsSortiesExport = $this->drm->getExports();
        $this->docTypesList = array();
        if (count($this->detailsSortiesVrac)) {
            $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA;
        }
        if (count($this->detailsSortiesExport)) {
            $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE;
        }
        $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_EMPREINTE;
        return $this->docTypesList;
    }

}
