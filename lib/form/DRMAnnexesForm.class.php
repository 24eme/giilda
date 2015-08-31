<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMAnnexesForm
 *
 * @author mathurin
 */
class DRMAnnexesForm extends acCouchdbObjectForm {

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

        $this->setWidget('quantite_sucre', new sfWidgetFormInputText());
        $this->setValidator('quantite_sucre', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setLabel('quantite_sucre', 'Quantité de sucre');

        $this->setWidget('observations', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));
        $this->setValidator('observations', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setLabel('observations', 'Observations générales');

        $this->embedForm('releve_non_apurement', new DRMReleveNonApurementItemsForm($this->drm->getReleveNonApurement()));
        $this->widgetSchema->setNameFormat('drmAnnexesForm[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->docTypesList as $docType) {
            $this->drm->getOrAdd('documents_annexes')->getOrAdd($docType)->debut = $values[$docType . '_debut'];
            $this->drm->getOrAdd('documents_annexes')->getOrAdd($docType)->fin = $values[$docType . '_fin'];
        }
        foreach ($this->getEmbeddedForms() as $key => $releveNonApurementForm) {
            $releveNonApurementForm->updateObject($values[$key]);
        }
        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->save();
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

        if ($this->drm->exist('documents_annexes') && $this->drm->documents_annexes) {
            $annexesNode = $this->drm->documents_annexes;
            foreach ($this->docTypesList as $docType) {
                if ($annexesNode->exist($docType) && $annexesNode->{$docType}) {
                    $docNode = $annexesNode->{$docType};
                    $this->setDefault($docType . '_debut', $docNode->debut);
                    $this->setDefault($docType . '_fin', $docNode->fin);
                }
            }
        }
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if ($form instanceof DRMReleveNonApurementItemsForm) {
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
        $form_embed = new DRMReleveNonApurementItemForm($drm->getOrAdd('releve_non_apurement')->add(), array('keyNonApurement' => uniqid()));
        $form = new DRMCollectionTemplateForm($this, 'releve_non_apurement', $form_embed);
        return $form->getFormTemplate();
    }

    public function getDocTypes() {


        $this->docTypesList = array();
        $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADAC;

        $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DSADSAC;


        $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_EMPREINTE;

        return $this->docTypesList;
    }

}
