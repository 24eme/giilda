<?php
/**
 * BaseRevendicationProduits
 * 
 * Base model for RevendicationProduits

 * @property string $date_certification
 * @property string $libelle_produit_csv
 * @property string $produit_hash
 * @property string $produit_libelle
 * @property acCouchdbJson $volumes

 * @method string getDateCertification()
 * @method string setDateCertification()
 * @method string getLibelleProduitCsv()
 * @method string setLibelleProduitCsv()
 * @method string getProduitHash()
 * @method string setProduitHash()
 * @method string getProduitLibelle()
 * @method string setProduitLibelle()
 * @method acCouchdbJson getVolumes()
 * @method acCouchdbJson setVolumes()
 
 */

abstract class BaseRevendicationProduits extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Revendication';
       $this->_tree_class_name = 'RevendicationProduits';
    }
                
}