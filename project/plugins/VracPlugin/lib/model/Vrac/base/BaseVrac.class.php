<?php
/**
 * BaseVrac
 * 
 * Base model for Vrac
 *
 * @property string $_id
 * @property string $_rev
 * @property string $numero_contrat
 * @property string $etape
 * @property acCouchdbJson $vendeur
 * @property acCouchdbJson $acheteur
 * @property acCouchdbJson $mandataire
 * @property string $type_transaction
 * @property string $produit
 * @property string $label
 * @property float $raisin_quantite
 * @property float $jus_quantite
 * @property integer $bouteilles_quantite
 * @property integer $bouteilles_contenance
 * @property float $prix_unitaire
 * @property float $prix_total
 * @property string $type_contrat
 * @property integer $prix_variable
 * @property float $part_variable
 * @property string $cvo_nature
 * @property string $cvo_repartition
 * @property acCouchdbJson $valide

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getNumeroContrat()
 * @method string setNumeroContrat()
 * @method string getEtape()
 * @method string setEtape()
 * @method acCouchdbJson getVendeur()
 * @method acCouchdbJson setVendeur()
 * @method acCouchdbJson getAcheteur()
 * @method acCouchdbJson setAcheteur()
 * @method acCouchdbJson getMandataire()
 * @method acCouchdbJson setMandataire()
 * @method string getTypeTransaction()
 * @method string setTypeTransaction()
 * @method string getProduit()
 * @method string setProduit()
 * @method string getLabel()
 * @method string setLabel()
 * @method float getRaisinQuantite()
 * @method float setRaisinQuantite()
 * @method float getJusQuantite()
 * @method float setJusQuantite()
 * @method integer getBouteillesQuantite()
 * @method integer setBouteillesQuantite()
 * @method integer getBouteillesContenance()
 * @method integer setBouteillesContenance()
 * @method float getPrixUnitaire()
 * @method float setPrixUnitaire()
 * @method float getPrixTotal()
 * @method float setPrixTotal()
 * @method string getTypeContrat()
 * @method string setTypeContrat()
 * @method integer getPrixVariable()
 * @method integer setPrixVariable()
 * @method float getPartVariable()
 * @method float setPartVariable()
 * @method string getCvoNature()
 * @method string setCvoNature()
 * @method string getCvoRepartition()
 * @method string setCvoRepartition()
 * @method acCouchdbJson getValide()
 * @method acCouchdbJson setValide()
 
 */
 
abstract class BaseVrac extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Vrac';
    }
    
}