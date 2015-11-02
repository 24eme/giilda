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
        parent::__construct($object,  $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->getObject() as $mvt) {           
            $this->embedForm($mvt->getKey(), new FactureMouvementEtablissementEditionLigneForm($mvt,array('interpro_id' => $this->interpro_id)));
        }

        $this->widgetSchema->setNameFormat('facture_mouvement_edition_lignes[%s]');
    }

}
