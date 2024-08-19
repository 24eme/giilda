<?php
class CompteRechercheAvanceeForm extends BaseForm
{
	public function configure() {
		$this->setWidgets(array(
				"identifiants" => new sfWidgetFormTextarea(),
		));

		$this->setValidators(array(
				"identifiants" => new sfValidatorString(array("required" => false)),
		));

		$this->widgetSchema->setNameFormat('recherche_avancee[%s]');
	}
}