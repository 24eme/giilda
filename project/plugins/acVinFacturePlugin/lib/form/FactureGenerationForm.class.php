<?php

class FactureGenerationForm extends BaseForm {

    const TYPE_DOCUMENT_TOUS = "TOUS";
    const TYPE_GENERATION_EXPORT = "EXPORT";
    const TYPE_GENERATION_RELANCES = "EXPORTRELANCE";

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        $defaults['date_facturation'] = date('d/m/Y');
        $defaults['date_mouvement'] = date('d/m/Y');
        $interpro = (isset($defaults['interpro']))? $defaults['interpro'] : null;
        $defaults['seuil'] = FactureConfiguration::getInstance($interpro)->getSeuilMinimum();
        $this->withExport = false;
        if (isset($options['export']) && $options['export']) {
		$this->withExport = true;
	}
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('modele', new bsWidgetFormChoice(array('choices' => $this->getChoices(), 'expanded' => true), array("required" => "required")));
        $this->setWidget('date_mouvement', new bsWidgetFormInputDate(array(), array("required" => "required")));
        $this->setWidget('date_facturation', new bsWidgetFormInputDate(array(), array("required" => "required")));
        $this->setWidget('seuil', new bsWidgetFormInputFloat());
        $this->setWidget('message_communication', new sfWidgetFormTextarea());

        $this->setValidator('modele', new sfValidatorChoice(array('choices' => array_keys($this->getChoices()), 'required' => true)));
        $this->setValidator('date_mouvement', new sfValidatorString(array('required' => false)));
        $this->setValidator('date_facturation', new sfValidatorString());
        $this->setValidator('seuil', new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));
        $this->setValidator('message_communication', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setLabels(array(
            'modele' => "Type de facturation :",
            'message_communication' => 'Cadre de communication :',
            'date_mouvement' => 'Dernière date de prise en compte des mouvements :',
            'date_facturation' => 'Date de facturation :'
        ));

        if ($this->getDefault('interpro')) {
            $this->setWidget('interpro', new bsWidgetFormInput());
            $this->setValidator('interpro', new sfValidatorString());
            $this->widgetSchema->setLabel('interpro', "Interpro facturée :");
        }

        $this->widgetSchema->setNameFormat('facture_generation[%s]');
    }

    public function getChoices() {
        $choices = array_merge(FactureClient::getInstance()->getTypeFactureMouvement());
      if (FactureConfiguration::getInstance($this->getDefault('interpro'))->getExportSV12()) {
        $choices = array_merge($choices, array(FactureClient::TYPE_FACTURE_MOUVEMENT_SV12 => 'Facturation SV12'));
        }
        if (FactureConfiguration::getInstance($this->getDefault('interpro'))->getExportShell()) {
          $choices = array_merge($choices, array(self::TYPE_GENERATION_EXPORT => 'Export comptable'));
	      }
        if (FactureConfiguration::getInstance($this->getDefault('interpro'))->getExportRelances()) {
          $choices = array_merge($choices, array(self::TYPE_GENERATION_RELANCES => 'Export relances'));
	      }
        if (sfConfig::get('statistique_configuration_vracssansprix')) {
          $choices[GenerationClient::TYPE_DOCUMENT_VRACSSANSPRIX] = 'Contrats sans prix';
        }
        if (!FactureConfiguration::getInstance($this->getDefault('interpro'))->getEmetteurLibre()) {
		      unset($choices[FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS]);
	      }
        return $choices;
    }

}
