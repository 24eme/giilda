<?php
/**
 * BaseFactureLigne
 * 
 * Base model for FactureLigne

 * @property string $origine_type
 * @property string $origine_identifiant
 * @property string $origine_date
 * @property string $produit_type
 * @property string $produit_libelle
 * @property string $produit_hash
 * @property string $mouvement_type
 * @property string $contrat_identifiant
 * @property string $contrat_libelle
 * @property string $echeance_code
 * @property float $volume
 * @property float $cotisation_taux
 * @property float $montant_ht
 * @property string $cle_mouvement

 * @method string getOrigineType()
 * @method string setOrigineType()
 * @method string getOrigineIdentifiant()
 * @method string setOrigineIdentifiant()
 * @method string getOrigineDate()
 * @method string setOrigineDate()
 * @method string getProduitType()
 * @method string setProduitType()
 * @method string getProduitLibelle()
 * @method string setProduitLibelle()
 * @method string getProduitHash()
 * @method string setProduitHash()
 * @method string getMouvementType()
 * @method string setMouvementType()
 * @method string getContratIdentifiant()
 * @method string setContratIdentifiant()
 * @method string getContratLibelle()
 * @method string setContratLibelle()
 * @method string getEcheanceCode()
 * @method string setEcheanceCode()
 * @method float getVolume()
 * @method float setVolume()
 * @method float getCotisationTaux()
 * @method float setCotisationTaux()
 * @method float getMontantHt()
 * @method float setMontantHt()
 * @method string getCleMouvement()
 * @method string setCleMouvement()
 
 */

abstract class BaseFactureLigne extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Facture';
       $this->_tree_class_name = 'FactureLigne';
    }
                
}