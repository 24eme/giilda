<?php

abstract class DRMESDetailsForm extends acCouchdbForm {

    protected $details = null;
    protected $isTeledeclarationMode = null;

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $options['isTeledeclarationMode'];
        $this->details = $details;
        parent::__construct($this->details->getDocument(),$defaults, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->details as $key => $item) {
            $form_item_class = $this->getFormItemClass();
            $form = $this->embedForm($key, new $form_item_class($item,array('isTeledeclarationMode' => $this->isTeledeclarationMode, 'details' => $this->details)));
        }

        $newDetailNode = call_user_func(array($this->getModelNode(), 'freeInstance'), $this->details->getDocument());
        $newDetailNode->setKey(uniqid());
        $form_item_class = $this->getFormItemClass();

        $this->embedForm($newDetailNode->getKey(), new $form_item_class($newDetailNode,array('isTeledeclarationMode' => $this->isTeledeclarationMode, 'details' => $this->details)));

        $this->widgetSchema->setNameFormat(sprintf("%s[%%s]", $this->getFormName()));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }


    public function bind(array $taintedValues = null, array $taintedFiles = null) {
      foreach ($this->embeddedForms as $key => $form) {
          if (!array_key_exists($key, $taintedValues)) {
              $this->unEmbedForm($key);
              continue;
          }
      }
      foreach ($taintedValues as $key => $values) {
          if (!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
              continue;
          }
          $detailNode = call_user_func(array($this->getModelNode(), 'freeInstance'), $this->details->getDocument());
          $detailNode->fromArray($values);
          $form_item_class = $this->getFormItemClass();
          $this->embedForm($key, new $form_item_class($detailNode,array('isTeledeclarationMode' => $this->isTeledeclarationMode,'details' => $this->details)));
      }
        parent::bind($taintedValues, $taintedFiles);
    }

  public function update(){

    foreach ($this->getEmbeddedForms() as $key => $form) {
        $form->updateObject($this->values[$key]);
    }

    //suppression
    $keysToRemove = array();
    foreach ($this->details->toArray(true,false) as $key => $value) {
      if(!array_key_exists($key,$this->values)){
        $keysToRemove[] = $key;
      }
    }
    foreach ($keysToRemove as $keyToRemove) {
      $this->details->remove($keyToRemove);
    }

    //CHANGEMENT d'indentifiant
    foreach ($this->values as $key => $value) {
      if(!is_string($value) && is_array($value)){
        if(array_key_exists($key,$this->details->toArray(true,false)) && $value['identifiant'] != $this->details->get($key)->getIdentifiant()){
          $this->details->remove($key);
        }
      }
    }

    //AJOUT CLASSIQUE
    foreach ($this->values as $key => $value) {
      if(!is_string($value) && is_array($value)){
        if(!array_key_exists($key,$this->details->toArray(true,false))){
          $detailNode = call_user_func(array($this->getModelNode(), 'freeInstance'), $this->details->getDocument());
          $detailNode->fromArray($value);
          if($detailNode->identifiant && $detailNode->volume){
            $this->details->addDetail($detailNode);
          }
        }
      }
    }

    //PURGE type document si pas de Numero document
    foreach ($this->details as $key => $detail) {
      if($detail->exist('type_document') && $detail->type_document && !$detail->numero_document){
        $this->details->get($key)->type_document = null;
      }
    }
  }

    public function getDetails() {

        return $this->details;
    }

    public function getFormTemplate() {
        $form_template_class = $this->getFormTemplateClass();
        $form = new $form_template_class($this->details,array(),array('isTeledeclarationMode' => $this->isTeledeclarationMode));
        return $form->getFormTemplate();
    }

    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->details->remove($key);
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    public abstract function getFormName();

    public abstract function getFormItemClass();

    public abstract function getFormTemplateClass();
}
