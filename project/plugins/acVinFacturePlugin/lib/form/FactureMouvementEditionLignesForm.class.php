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



    public function configure() {
        foreach ($this->getObject() as $mvt) {

            $this->embedForm($mvt->getKey(), new FactureMouvementEditionLigneForm($mvt));
        }

        $this->widgetSchema->setNameFormat('facture_mouvement_edition_lignes[%s]');
    }

}
