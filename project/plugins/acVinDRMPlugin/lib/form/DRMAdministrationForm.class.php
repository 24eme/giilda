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

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object;
        $this->detailsSortiesVrac = $this->drm->getVracs();
        $this->detailsSortiesExport = $this->drm->getExports();
        parent::__construct($this->drm, $options, $CSRFSecret);
    }

    public function configure() {
        if (count($this->detailsSortiesVrac)) {
            $keyDebut = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA . '_debut';
            $keyFin = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA . '_fin';

            $this->setWidget($keyDebut, new sfWidgetFormInputText());
            $this->setWidget($keyFin, new sfWidgetFormInputText());
            $this->widgetSchema->setLabel($keyDebut, 'DSA/DAA début');
            $this->widgetSchema->setLabel($keyFin, 'DSA/DAA fin');
            $this->setValidator($keyDebut, new sfValidatorString(array('required' => false)));
            $this->setValidator($keyFin, new sfValidatorString(array('required' => false)));
        }

        if (count($this->detailsSortiesExport)) {
            $keyDebut = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE . '_debut';
            $keyFin = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE . '_fin';

            $this->setWidget($keyDebut, new sfWidgetFormInputText());
            $this->setWidget($keyFin, new sfWidgetFormInputText());
            $this->widgetSchema->setLabel($keyDebut, 'DSA/DAA début');
            $this->widgetSchema->setLabel($keyFin, 'DSA/DAA fin');
            $this->setValidator($keyDebut, new sfValidatorString(array('required' => false)));
            $this->setValidator($keyFin, new sfValidatorString(array('required' => false)));
        }
        $this->widgetSchema->setNameFormat('drmAddTypeForm[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (count($this->detailsSortiesVrac)) {
            $this->drm->getOrAdd('documents_administration')->getOrAdd(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA)->debut = $values[DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA . '_debut'];
            $this->drm->getOrAdd('documents_administration')->getOrAdd(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA)->fin = $values[DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA . '_fin'];
        }
        if (count($this->detailsSortiesExport)) {
            $this->drm->getOrAdd('documents_administration')->getOrAdd(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE)->debut = $values[DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE . '_debut'];
            $this->drm->getOrAdd('documents_administration')->getOrAdd(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE)->fin = $values[DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE . '_fin'];
        }

        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->save();
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($this->drm->exist('documents_administration') && $this->drm->documents_administration) {
            $administrationNode = $this->drm->documents_administration;
            if ($administrationNode->exist(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA) && $administrationNode->{DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA}) {
                $daadsaNode = $administrationNode->{DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA};
                $this->setDefault(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA . '_debut', $daadsaNode->debut);
                $this->setDefault(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADSA . '_fin', $daadsaNode->fin);
            }
            if ($administrationNode->exist(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE) && $administrationNode->{DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE}) {
                $daeNode = $administrationNode->{DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE};
                $this->setDefault(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE . '_debut', $daeNode->debut);
                $this->setDefault(DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE . '_fin', $daeNode->fin);
            }
        }
    }

}
