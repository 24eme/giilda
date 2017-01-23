<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactureTemplateForm
 *
 * @author mathurin
 */
class FacturationTemplateForm extends BaseForm {

    protected $templatesFactures;

    public function __construct($templatesFactures, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->templatesFactures = $templatesFactures;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $choices = $this->getChoices();

        $this->setWidgets(array(
            'modele' => new sfWidgetFormChoice(array('choices' => $choices)),
            'date_facturation' => new sfWidgetFormInput(array('default' => date('d/m/Y'))),
            'message_communication' => new sfWidgetFormTextarea()
        ));

        $this->widgetSchema->setLabels(array(
            'modele' => 'Template de facture',
            'date_facturation' => 'Date de facturation',
            'message_communication' => 'Cadre de communication',
        ));

        $this->setValidators(array(
            'modele' => new sfValidatorChoice(array('choices' => array_keys($choices), 'multiple' => false, 'required' => true)),
            'date_facturation' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)),
            'message_communication' => new sfValidatorString(array('required' => false))
        ));

        $this->widgetSchema->setNameFormat('facture_declarant[%s]');
    }

    public function getChoices() {
        $choices = array_merge(array("" => ""), FactureClient::getInstance()->getTypeFactureMouvement());
        foreach ($this->templatesFactures as $templateFacture) {
            $choices[$templateFacture->_id] = $templateFacture->libelle;
        }
        return $choices;
    }

}
