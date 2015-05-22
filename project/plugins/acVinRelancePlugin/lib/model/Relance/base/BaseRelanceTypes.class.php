<?php
/**
 * BaseRelanceTypes
 * 
 * Base model for RelanceTypes

 * @property string $titre
 * @property string $refarticle
 * @property string $multiple
 * @property string $liste_champs
 * @property string $description
 * @property string $description_fin
 * @property acCouchdbJson $lignes

 * @method string getTitre()
 * @method string setTitre()
 * @method string getRefarticle()
 * @method string setRefarticle()
 * @method string getMultiple()
 * @method string setMultiple()
 * @method string getListeChamps()
 * @method string setListeChamps()
 * @method string getDescription()
 * @method string setDescription()
 * @method string getDescriptionFin()
 * @method string setDescriptionFin()
 * @method acCouchdbJson getLignes()
 * @method acCouchdbJson setLignes()
 
 */

abstract class BaseRelanceTypes extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Relance';
       $this->_tree_class_name = 'RelanceTypes';
    }
                
}