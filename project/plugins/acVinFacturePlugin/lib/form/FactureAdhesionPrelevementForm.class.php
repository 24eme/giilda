<?php
/**
 * Description of class
 * @author mathurin
 */
class FactureAdhesionPrelevementForm extends sfForm {

  public function configure() {
      $this->setWidgets(array(
          'facture_adhesion_prelevement' => new sfWidgetFormInputCheckbox()
      ));
      $this->setValidators(array('facture_adhesion_prelevement' => new sfValidatorPass(array('required' => true))));

      $this->widgetSchema->setNameFormat('facture_adhesion_prelevement[%s]');
  }

}
