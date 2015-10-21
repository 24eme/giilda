<?php
/**
 * BaseFactureLigne
 * 
 * Base model for FactureLigne

 * @property string $libelle
 * @property string $produit_identifiant_analytique
 * @property float $montant_tva
 * @property float $montant_ht
 * @property acCouchdbJson $origine_mouvements
 * @property acCouchdbJson $details

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getProduitIdentifiantAnalytique()
 * @method string setProduitIdentifiantAnalytique()
 * @method float getMontantTva()
 * @method float setMontantTva()
 * @method float getMontantHt()
 * @method float setMontantHt()
 * @method acCouchdbJson getOrigineMouvements()
 * @method acCouchdbJson setOrigineMouvements()
 * @method acCouchdbJson getDetails()
 * @method acCouchdbJson setDetails()
 
 */

abstract class BaseFactureLigne extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Facture';
       $this->_tree_class_name = 'FactureLigne';
    }
                
}