<?php
class DRMEtablissementCampagneForm extends sfForm
{

  private $isTeledeclarationMode = false;

  public function configure() {
    $list = $this->getChoiceCampagnes();
    $this->setWidgets(array(
			    'campagne'   => new sfWidgetFormChoice(array('choices' => $list, 'default' => $this->default_campagne))
			    ));
    $this->setValidators(array(
			       'campagne'   => new sfValidatorChoice(array('required' => true, 'choices' => $list))
			       ));
    $this->widgetSchema->setLabels(array(
			    'campagne'   => "Consulter l'historique pour : "
			    ));
    $this->widgetSchema->setNameFormat('etablissementcampagne[%s]');
  }

  public function __construct($identifiantEtablissement, $defaultCampagne,$isTeledeclarationMode = false) {
    $this->etablissement_id = $identifiantEtablissement;
    $this->default_campagne = $defaultCampagne;
    $this->isTeledeclarationMode = $isTeledeclarationMode;
    return parent::__construct();
  }

  private function getChoiceCampagnes() {

      $campagnesDefaut = array();
      for($i = date("Y")*1; $i >= 2015; $i--) {
          $campagne = ($i-1)."-".$i;
          $campagnesDefaut[$campagne] = $campagne;
      }

      $campagnes = ($this->isTeledeclarationMode)? $campagnesDefaut : DRMClient::getInstance()->listCampagneByEtablissementId($this->etablissement_id);

      return array_merge(array('-1' => 'les derniers mois'),$campagnes);
  }

}
