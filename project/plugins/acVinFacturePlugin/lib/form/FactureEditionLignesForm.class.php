<?php

class FactureEditionLignesForm extends acCouchdbObjectForm {

    public function configure()
    {
        foreach($this->getObject() as $ligne) {

            $this->embedForm($ligne->getKey(), new FactureEditionLigneForm($ligne));
        }

        $this->widgetSchema->setNameFormat('facture_edition_lignes[%s]');
    }     

}
