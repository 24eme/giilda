<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMCollectionTemplateForm
 *
 * @author mathurin
 */
class DRMCollectionTemplateForm extends BaseForm {

    protected $form = null;
    protected $field = null;
    protected $form_embed = null;
    protected $unique_var = null;

    public function __construct($form, $field, $form_embed, $unique_var = 'var---nbItem---',$defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->form = $form;
        $this->field = $field;
        $this->form_embed = $form_embed;
        $this->unique_var = $unique_var;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
       
        $this->embedForm($this->unique_var, $this->form_embed);
        $name = sprintf($this->form->widgetSchema->getNameFormat(), $this->field);
        $this->widgetSchema->setNameFormat($name.'[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    }
   
    public function getFormTemplate() {
       
        return $this[$this->unique_var];
    }
   
}
