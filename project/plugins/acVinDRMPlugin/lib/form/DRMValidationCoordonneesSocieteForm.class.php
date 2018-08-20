<?php

class DRMValidationCoordonneesSocieteForm extends acCouchdbObjectForm {

    protected $coordonneesSociete = null;
    protected $drm = null;

    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->drm = $drm;
        parent::__construct($drm, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();

        $this->setWidget('siret', new sfWidgetFormInput());
        $this->setValidator('siret', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setLabel('siret', 'SIRET :');

        $this->setWidget('adresse', new sfWidgetFormInput());
        $this->setValidator('adresse', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('adresse', 'Adresse :');

        $this->setWidget('code_postal', new sfWidgetFormInput());
        $this->setValidator('code_postal', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('code_postal', 'Code postal :');

        $this->setWidget('commune', new sfWidgetFormInput());
        $this->setValidator('commune', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('commune', 'Commune :');

        $this->setWidget('email', new sfWidgetFormInput());
        $this->setValidator('email', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('email', 'E-mail :');

        $this->setWidget('telephone', new sfWidgetFormInput());
        $this->setValidator('telephone', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setLabel('telephone', 'Téléphone :');

        $this->setWidget('fax', new sfWidgetFormInput());
        $this->setValidator('fax', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setLabel('fax', 'Fax :');

        $this->widgetSchema->setNameFormat('drm_validation_coordonnees_societe[%s]');
    }

    private function getCoordonneesSociete() {
        if (!$this->coordonneesSociete) {
            $this->coordonneesSociete = $this->drm->getCoordonneesSociete();
        }
        return $this->coordonneesSociete;
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $this->getCoordonneesSociete();
        $this->setDefault('siret', $this->coordonneesSociete->siret);
        $this->setDefault('adresse', $this->coordonneesSociete->adresse);
        $this->setDefault('code_postal', $this->coordonneesSociete->code_postal);
        $this->setDefault('commune', $this->coordonneesSociete->commune);
        $this->setDefault('email', $this->coordonneesSociete->email);
        $this->setDefault('telephone', $this->coordonneesSociete->telephone);
        $this->setDefault('fax', $this->coordonneesSociete->fax);
        $this->setDefault('paiement_douane_frequence', $this->coordonneesSociete->paiement_douane_frequence);
        $this->setDefault('paiement_douane_moyen', $this->coordonneesSociete->paiement_douane_moyen);
    }

    public function getDiff() {
        $diff = array();
        $this->getCoordonneesSociete();
        foreach ($this->getValues() as $key => $new_value) {
            if (!preg_match('/^_revision$/', $key)) {
                if ($this->coordonneesSociete->$key != $new_value) {
                    $diff[$key] = $new_value;
                }
            }
        }
        return $diff;
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->drm->societe->siret = $values['siret'];
        $this->drm->societe->adresse = $values['adresse'];
        $this->drm->societe->code_postal = $values['code_postal'];
        $this->drm->societe->commune = $values['commune'];
        $this->drm->societe->email = $values['email'];
        $this->drm->societe->telephone = $values['telephone'];
        $this->drm->societe->fax = $values['fax'];
        $this->drm->societe->paiement_douane_frequence = $values['paiement_douane_frequence'];
        $this->drm->societe->paiement_douane_moyen = $values['paiement_douane_moyen'];
        $societe = $this->drm->getEtablissement()->getSociete();
        $societe->add('paiement_douane_moyen', $values['paiement_douane_moyen']);
        $societe->add('paiement_douane_frequence', $values['paiement_douane_frequence']);
        $societe->save();
        $etb = $this->drm->getEtablissement();
        $etb->teledeclaration_email = $this->drm->societe->email;
        $etb->email = $this->drm->societe->email;
        $etb->save();
    }

    public function getPaiementDouaneFrequence() {
        return DRMPaiement::$frequence_paiement_libelles;
    }

    public function getPaiementDouaneMoyen() {
        return DRMPaiement::$moyens_paiement_libelles;
    }

}
