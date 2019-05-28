<?php

class DRMValidationCommentaireForm extends acCouchdbObjectForm {

    protected static $transmission_ciel = array("1" => "Transmission");

    public function configure() {
      parent::configure();
      $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));
      $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
      $this->widgetSchema->setLabel('commentaire', 'Commentaires :');

      $this->setWidget('email_transmission', new sfWidgetFormInputHidden());
      $this->setValidator('email_transmission', new sfValidatorString(array('required' => false)));
      $this->widgetSchema->setLabel('email_transmission', 'Email de transmission :');

      $myUser = sfContext::getInstance()->getUser();
      $user_account = $myUser->getCompte();
      if ($myUser->hasCredential('teledeclaration_drm')){
          if($user_account->hasDroit("teledeclaration_douane")){
              $this->setWidget('transmission_ciel', new sfWidgetFormInputHidden());
              $this->setValidator('transmission_ciel', new sfValidatorString(array('required' => false)));
              $this->widgetSchema->setLabel('transmission_ciel', 'Transmission pour préremplissage de votre DRM électronique sur le portail pro.douane.gouv.fr :');
          }

          if ($user_account->exist('email') && $user_account->hasDroit('teledeclaration_facture')) {
              $this->setWidget('email_facture', new sfWidgetFormInputCheckbox(['default' => true]));
              $this->setValidator('email_facture', new sfValidatorBoolean(['required' => false]));
              $this->widgetSchema->setLabel('email_facture', 'Je souhaite recevoir ma facture par e-mail');
          }
      }

      $this->widgetSchema->setNameFormat('drm[%s]');
    }
}
