<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactureMouvementEditionLignesForm
 *
 * @author mathurin
 */
class FactureMouvementEditionLignesForm extends acCouchdbObjectForm {

    protected $interpro_id;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->interpro_id = $options['interpro_id'];
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->getObject() as $identifiantKey => $mvts) {
            foreach ($mvts as $uniqKey => $mvt) {
                $mvtId = $identifiantKey . '_' . $uniqKey;
                $this->embedForm($mvtId, new FactureMouvementEtablissementEditionLigneForm($mvt, array('interpro_id' => $this->interpro_id, 'keyMvt' => $mvtId)));
            }
        }
        $this->widgetSchema->setNameFormat('facture_mouvement_edition_ligne[%s]');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if (!array_key_exists($key, $taintedValues)) {
                $this->unEmbedForm($key);
            }
        }
        foreach ($taintedValues as $key => $values) {

            if (!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
                continue;
            }
            
            if (preg_match('/^nouveau_/', $key)) {
                $identifiant = false;
                foreach ($values as $keyValue => $value) {
                    if ($keyValue == 'identifiant') {
                        if ($value && SocieteClient::getInstance()->find($value)) {                        
                            $identifiant = str_replace("SOCIETE-", "", $values['identifiant']) . '01';
                        }
                    }
                }
                
                    $keyMvt = str_replace("nouveau_", "", $key);
                    $mouvement = $this->getObject()->getOrAdd($identifiant)->getOrAdd($keyMvt);
                    $this->embedForm($key, new FactureMouvementEtablissementEditionLigneForm($mouvement, array('interpro_id' => $this->interpro_id, 'keyMvt' => $key)));
                }
            
        }
    }

    public function unEmbedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->getObject()->remove($key);
    }

}
