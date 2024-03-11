<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SocieteChoiceForm extends baseForm {

    protected $interpro_id;
    protected $type_societe;

    public function __construct($interpro_id, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->interpro_id = $interpro_id;
        $this->type_societe = array(SocieteClient::TYPE_OPERATEUR);
        if(is_array($options) && isset($options['type_societe'])){
            $this->type_societe[] = $options['type_societe'];
        }
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('identifiant', new WidgetSociete(array('interpro_id' => $this->interpro_id,'type_societe' => $this->type_societe)));

        $this->widgetSchema->setLabel('identifiant', 'Sélectionner une societe&nbsp;:');
        $this->setValidator('identifiant', new ValidatorSociete(array('required' => true)));

        $this->validatorSchema['identifiant']->setMessage('required', 'Le choix d\'une societe est obligatoire');

        $this->widgetSchema->setNameFormat('societe[%s]');
    }

    public function configureTypeSociete($types) {
        $this->getWidget('identifiant')->setOption('type_societe', $types);
        $this->getValidator('identifiant')->setOption('type_societe', $types);
    }

    public function getSociete() {
        return $this->getValidator('identifiant')->getDocument();
    }
    
}
