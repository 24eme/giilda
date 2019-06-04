<?php

/**
 * Génère un formulaire pour enregistrer le droit d'envoyer
 * les factures par mails
 *
 */
class DRMFactureEmailForm extends acCouchdbObjectForm
{
    private $drm;

    public function __construct(DRM $drm, $options = [], $CSRFSecret = null)
    {
        $this->drm = $drm;
        parent::__construct($drm, $options, $CSRFSecret);
    }

    public function configure()
    {
        $this->setWidget('facture_mail', new sfWidgetFormInputCheckbox());
        $this->setValidator('facture_mail', new sfValidatorBoolean(['required' => false]));
        $this->widgetSchema->setLabel('facture_mail', 'Je souhaite recevoir ma facture par e-mail');

        $this->widgetSchema->setNameFormat('facture[%s]');
    }

    public function doUpdateObject($values)
    {
        if ($values['facture_mail']) {
            $compte = CompteClient::getInstance()->findByIdentifiant($this->drm->identifiant);
            $compte->getOrAdd('droits')->add(Roles::TELEDECLARATION_FACTURE_EMAIL, Roles::TELEDECLARATION_FACTURE_EMAIL);
            $compte->save();
        }
    }
}
