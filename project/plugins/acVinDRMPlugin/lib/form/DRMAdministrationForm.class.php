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

        $this->embedForm('releve_non_appurement', new DRMReleveNonAppurementItemsForm($this->drm->getReleveNonAppurement()));
        $this->widgetSchema->setNameFormat('drmAdministrationForm[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->docTypesList as $docType) {
            $this->drm->getOrAdd('documents_administration')->getOrAdd($docType)->debut = $values[$docType . '_debut'];
            $this->drm->getOrAdd('documents_administration')->getOrAdd($docType)->fin = $values[$docType . '_fin'];
        }
        foreach ($this->getEmbeddedForms() as $key => $releveNonAppurementForm) {
            $releveNonAppurementForm->updateObject($values[$key]);
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

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if ($form instanceof DRMReleveNonAppurementItemsForm) {
                if (isset($taintedValues[$key])) {
                    $form->bind($taintedValues[$key], $taintedFiles[$key]);
                    $this->updateEmbedForm($key, $form);
                }
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }    
    
    public function getFormTemplate() {
        $drm = new DRM();
        $form_embed = new DRMReleveNonAppurementItemForm($drm->getOrAdd('releve_non_appurement')->add(), array('keyNonAppurement' => uniqid()));
        $form = new DRMCollectionTemplateForm($this, 'releve_non_appurement', $form_embed);
        return $form->getFormTemplate();
    }
   
    public function getDocTypes() {

        $this->detailsSortiesVrac = $this->drm->getDetailsVracs();
        $this->detailsSortiesExport = $this->drm->getDetailsExports();

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
