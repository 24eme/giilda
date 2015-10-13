<?php

class FactureEditionForm extends acCouchdbObjectForm {

    public function configure()
    {   
        $this->getObject()->lignes->add("nouvelle");
        $this->embedForm('lignes', new FactureEditionLignesForm($this->getObject()->lignes));
        
        $this->widgetSchema->setNameFormat('facture_edition[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);

        if($this->getObject()->lignes->exist("nouvelle")) {
            $newLine = $this->getObject()->lignes->get("nouvelle")->toArray(true, false);
            $this->getObject()->lignes->remove("nouvelle");
            $this->getObject()->lignes->add(uniqid(), $newLine);
        }
        
        $this->getObject()->lignes->cleanLignes();
        $this->getObject()->updateTotaux();
    }

    /*public function processValues($values) {
        parent::processValues($values);
        foreach($values['lignes'] as $key_ligne => $ligne) {
            foreach($ligne['details'] as $key_detail => $detail) {
                if(empty($detail['quantite']) && empty($detail['libelle']) && empty($detail['prix_unitaire'])) {
                    unset($values['lignes'][$key_ligne]['details'][$key_detail]);
                }
            }            
        } 
        return $values;
    }*/

}
