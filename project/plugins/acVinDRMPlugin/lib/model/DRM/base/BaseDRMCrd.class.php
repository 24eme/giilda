<?php
/**
 * BaseDRMCrd
 * 
 * Base model for DRMCrd

 * @property string $genre
 * @property string $stock_debut
 * @property string $stock_fin
 * @property string $couleur
 * @property string $centilitrage
 * @property string $detail_libelle
 * @property string $entrees_achats
 * @property string $entrees_retours
 * @property string $entrees_excedents
 * @property string $sorties_utilisations
 * @property string $sorties_destructions
 * @property string $sorties_manquants

 * @method string getGenre()
 * @method string setGenre()
 * @method string getStockDebut()
 * @method string setStockDebut()
 * @method string getStockFin()
 * @method string setStockFin()
 * @method string getCouleur()
 * @method string setCouleur()
 * @method string getCentilitrage()
 * @method string setCentilitrage()
 * @method string getDetailLibelle()
 * @method string setDetailLibelle()
 * @method string getEntreesAchats()
 * @method string setEntreesAchats()
 * @method string getEntreesRetours()
 * @method string setEntreesRetours()
 * @method string getEntreesExcedents()
 * @method string setEntreesExcedents()
 * @method string getSortiesUtilisations()
 * @method string setSortiesUtilisations()
 * @method string getSortiesDestructions()
 * @method string setSortiesDestructions()
 * @method string getSortiesManquants()
 * @method string setSortiesManquants()
 
 */

abstract class BaseDRMCrd extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCrd';
    }
                
}