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

        $this->setWidget('paiement_douane_frequence', new sfWidgetFormChoice(array('expanded' => true, 'multiple' => false, 'choices' => $this->getPaiementDouaneFrequence())));
        $this->setValidator('paiement_douane_frequence', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getPaiementDouaneFrequence())), array('required' => "Aucune fréquence de paiement des droits douane n'a été choisie")));
        $this->widgetSchema->setLabel('paiement_douane_frequence', 'Fréquence de paiement');

        foreach ($this->drm->getProduits() as $produit) {
            $genre = $produit->getConfig()->getGenre();
            $droit = $genre->getDroitDouane($this->drm->getFirstDayOfPeriode());
            $genreKey = DRMDroits::$correspondanceGenreKey[$genre->getKey()];
            $this->setWidget('cumul_' . $genreKey, new sfWidgetFormInputFloat(array('float_format' => "%d")));
            $this->setValidator('cumul_' . $genreKey, new sfValidatorNumber(array('required' => false)));

            $this->widgetSchema->setLabel('cumul_' . $genreKey, $droit->libelle . ' (' . $droit->code . ') :');
        }

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

        $paiement_douane_frequence = $values['paiement_douane_frequence'];

        $this->drm->getSociete()->add('paiement_douane_frequence', $paiement_douane_frequence);
        if ($paiement_douane_frequence == DRMPaiement::FREQUENCE_ANNUELLE) {
            foreach ($this->drm->getProduits() as $produit) {
                $genre = $produit->getConfig()->getGenre();
                $genreKey = DRMDroits::$correspondanceGenreKey[$genre->getKey()];
                $localCumul = $values['cumul_' . $genreKey];
                
                if ($localCumul && $localCumul > 0) {
                    $this->drm->getOrAdd('droits')->getOrAdd('douane')->getOrAdd($genreKey)->set('report', $localCumul);
                }
            }
        }
        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->save();

        $societe = $this->drm->getEtablissement()->getSociete();
        $societe->add('paiement_douane_frequence', $paiement_douane_frequence);
        $societe->save();
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
        $societe = $this->drm->getEtablissement()->getSociete();
        if ($societe->exist('paiement_douane_frequence') && $societe->paiement_douane_frequence) {
            $this->setDefault('paiement_douane_frequence', $societe->paiement_douane_frequence);
            if ($societe->paiement_douane_frequence == DRMPaiement::FREQUENCE_ANNUELLE) {
                $droitsDouane = $this->drm->getOrAdd('droits')->getOrAdd('douane');
                foreach ($this->drm->getProduits() as $produit) {
                    $genre = $produit->getConfig()->getGenre();
                    $genreKey = DRMDroits::$correspondanceGenreKey[$genre->getKey()];
                    $this->setDefault('cumul_' . $genreKey, $droitsDouane->getOrAdd($genreKey)->get('report'));
                }
            }
        } else {
            $this->setDefault('paiement_douane_frequence', null);
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
        $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAE;
        $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DSADSAC;


        $this->docTypesList[] = DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_EMPREINTE;

        return $this->docTypesList;
    }

    public function getPaiementDouaneFrequence() {
        return DRMPaiement::$frequence_paiement_libelles;
    }

    public function getPaiementDouaneMoyen() {
        return DRMPaiement::$moyens_paiement_libelles;
    }

}
