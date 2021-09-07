<?php
/**
 * BaseFacturePaiement
 * 
 * Base model for FacturePaiement

 * @property string $date
 * @property string $montant
 * @property string $commentaire

 * @method string getDate()
 * @method string setDate()
 * @method string getMontant()
 * @method string setMontant()
 * @method string getCommentaire()
 * @method string setCommentaire()
 
 */

abstract class BaseFacturePaiement extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Facture';
       $this->_tree_class_name = 'FacturePaiement';
    }
                
}