<?php

class EtablissementRelationForm extends BaseForm {

    public function configure() {
        parent::configure();

        $this->setWidget('type_liaison', new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getLiaisonsChoice())));
        $this->widgetSchema->setLabel('type_liaison', 'Relation :');
        $this->setValidator('type_liaison', new sfValidatorChoice(array('required' => true, 'multiple' => false, 'choices' => array_keys($this->getLiaisonsChoice()))));

        $this->setWidget('id_etablissement', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-declaration')));
        $this->widgetSchema->setLabel('id_etablissement', "Avec l'établissement :");
        $this->setValidator('id_etablissement', new ValidatorEtablissement(array('required' => true)));
        $this->validatorSchema['id_etablissement']->setMessage('required', 'Le choix d\'un etablissement est obligatoire');

        $this->widgetSchema->setNameFormat('etablissement_relation[%s]');
    }

    public function getLiaisonsChoice() {

        return array_merge(array("" => ""), EtablissementClient::getTypesLiaisons());
    }

}
