<?php
/**
 * BaseFactureDetail
 * 
 * Base model for FactureDetail

 * @property string $libelle
 * @property string $identifiant_analytique
 * @property float $quantite
 * @property float $taux_tva
 * @property float $prix_unitaire
 * @property float $montant_tva
 * @property float $montant_ht
 * @property string $origine_type
 * @property acCouchdbJson $origine_mouvements

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getIdentifiantAnalytique()
 * @method string setIdentifiantAnalytique()
 * @method float getQuantite()
 * @method float setQuantite()
 * @method float getTauxTva()
 * @method float setTauxTva()
 * @method float getPrixUnitaire()
 * @method float setPrixUnitaire()
 * @method float getMontantTva()
 * @method float setMontantTva()
 * @method float getMontantHt()
 * @method float setMontantHt()
 * @method string getOrigineType()
 * @method string setOrigineType()
 * @method acCouchdbJson getOrigineMouvements()
 * @method acCouchdbJson setOrigineMouvements()
 
 */

abstract class BaseFactureDetail extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Facture';
       $this->_tree_class_name = 'FactureDetail';
    }
                
}