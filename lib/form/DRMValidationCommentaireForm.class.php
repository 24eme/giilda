<?php

class DRMValidationCommentaireForm extends acCouchdbObjectForm {

    public function configure() {
      parent::configure();
      $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));
      $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
      $this->widgetSchema->setLabel('commentaire', 'Commentaires :');
      
      $this->setWidget('email_transmission', new sfWidgetFormInputHidden());
      $this->setValidator('email_transmission', new sfValidatorString(array('required' => false)));
      $this->widgetSchema->setLabel('email_transmission', 'Email de transmission :');
      
      $this->widgetSchema->setNameFormat('drm[%s]');
    }    
  
}
