<?php

abstract class DRMESDetailsForm extends acCouchdbForm {

    protected $details = null;
    protected $isTeledeclarationMode = null;

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $options['isTeledeclarationMode'];
        $this->details = $details;
        parent::__construct($this->details->getDocument(), $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        if (!count($this->details)) {
            $this->details->addDetail();
        }

        foreach ($this->details as $key => $item) {
            $form_item_class = $this->getFormItemClass();
            if (!$key) {
                $key = uniqid();
            }
            $form = $this->embedForm($key, new $form_item_class($item, array('isTeledeclarationMode' => $this->isTeledeclarationMode)));
        }
        $this->widgetSchema->setNameFormat(sprintf("%s[%%s]", $this->getFormName()));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if (!array_key_exists($key, $taintedValues)) {
                $this->unembedForm($key);
            }
        }

        foreach ($taintedValues as $key => $values) {
            if (!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
                continue;
            }

            $form_item_class = $this->getFormItemClass();
            $this->embedForm($key, new $form_item_class($this->details->addDetail($key), array('isTeledeclarationMode' => $this->isTeledeclarationMode)));
        }
        parent::bind($taintedValues, $taintedFiles);
    }

    public function update() {
        $details = array();

        foreach ($this->getEmbeddedForms() as $key => $form) {
            $form->updateObject($this->values[$key]);
            $details[] = clone $form->getObject();
        }
//         $parent = $this->getDetails()->getParent();
//         $key = $this->getDetails()->getKey();
//         if(preg_match('/^export_details/', $key)){
//           // $flag = false;
//           // foreach ($details as $keyExport => $detail) {
//           //   if(preg_match('/^-/',$detail->getKey())){ $flag = true; }
//           // }
//           // if($flag){
//             // $parent->remove($key);
//             // $this->details = $parent->add($key);
//           // }
//
//         }
//         $removeHash = array();
// foreach ($this->getDetails() as $detail) {
//   if(preg_match('/^-/',$detail->getKey())){
//     $removeHash[] = $detail->getHash();
//   }
// }
// foreach ($removeHash as $hash) {
//   $this->getDocument()->remove($hash);
// }

        // foreach ($this->getDetails() as $detail) {
        //      if (!preg_match('/^creationvrac_details$/', $this->getDetails()->getKey()) && !preg_match('/^creationvractirebouche_details/', $this->getDetails()->getKey())) {
        //          $this->getDetails()->remove($identifiant);
        //   }
        // }
        foreach ($details as $key => $detail) {

            if(preg_match('/^creationvrac_details$/', $this->getDetails()->getKey())){
              if(preg_match('/^'.$this->getDocument()->_id.'$/',$detail->getKey()) || preg_match('/^[0-9a-z-]+$/',$detail->getKey())){
                $newDetail = $this->getDetails()->addDetailCreationVrac($detail->identifiant, $detail->volume, $detail->date_enlevement, $detail->prixhl, $detail->acheteur, VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE);
                if(preg_match('/^[0-9a-z-]+$/',$detail->getKey())){
                  $this->getDetails()->remove($detail->getKey());
                }
              }
            }elseif(preg_match('/^creationvractirebouche_details/', $this->getDetails()->getKey())){
              if(preg_match('/^'.$this->getDocument()->_id.'$/',$detail->getKey()) || preg_match('/^[0-9a-z-]+$/',$detail->getKey())){
                $this->getDetails()->addDetailCreationVrac($detail->identifiant, $detail->volume, $detail->date_enlevement, $detail->prixhl, $detail->acheteur,     VracClient::TYPE_TRANSACTION_VIN_VRAC);
                if(preg_match('/^[0-9a-z-]+$/',$detail->getKey())){
                  $this->getDetails()->remove($detail->getKey());
                }
              }
            }elseif(preg_match('/^(VRAC-|BOUTEILLE-)?([0-9]+|[A-Z]+|inconnu)$/', $detail->identifiant)) {
              if(preg_match('/^[0-9a-z-]+$/',$detail->getKey())){
                $this->getDetails()->remove($detail->getKey());
              }
                $this->getDetails()->addDetail($detail->identifiant, $detail->volume, $detail->date_enlevement, $detail->numero_document, $detail->type_document,$detail->getKey());
            }
        }
    }

    public function getDetails() {

        return $this->details;
    }

    public function getFormTemplate() {
        $form_template_class = $this->getFormTemplateClass();
        $form = new $form_template_class($this->details, array(), array('isTeledeclarationMode' => $this->isTeledeclarationMode));
        return $form->getFormTemplate();
    }

    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->details->remove($key);
    }

    public function isTypeDocShow() {
        foreach ($this->details as $detail) {
            if ($detail->numero_document) {
                return true;
            }
        }
        return false;
    }

    public abstract function getFormName();

    public abstract function getFormItemClass();

    public abstract function getFormTemplateClass();
}
