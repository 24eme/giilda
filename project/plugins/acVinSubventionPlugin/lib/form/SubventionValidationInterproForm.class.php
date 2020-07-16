<?php
class SubventionValidationInterproForm extends acCouchdbForm
{
	public function configure() {
        $this->setWidget('statut', new bsWidgetFormChoice(array('choices' => $this->getStatuts())));
        $this->setValidator('statut', new sfValidatorChoice(array('choices' => array_keys(SubventionClient::$statuts), 'required' => true)));

		$this->setWidget('commentaire', new bsWidgetFormTextarea());
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));

        $this->getWidget('statut')->setLabel('Statut');
        $this->getWidget('commentaire')->setLabel('Commentaire / Motif de refus');

        $this->widgetSchema->setNameFormat('validation_interpro[%s]');
    }

	public function getStatuts() {

		return array_merge(array("" => ""), SubventionClient::$statuts);
	}

	public function save() {
		$values = $this->getValues();

		$this->getDocument()->validateInterpro($values['statut']);
		$this->getDocument()->save();
	}

}
