<?php
/**
 * BaseVrac
 * 
 * Base model for Vrac
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $numero_contrat
 * @property integer $etape
 * @property string $vendeur_identifiant
 * @property acCouchdbJson $vendeur
 * @property string $acheteur_identifiant
 * @property acCouchdbJson $acheteur
 * @property string $mandataire_exist
 * @property acCouchdbJson $mandatant
 * @property string $mandataire_identifiant
 * @property acCouchdbJson $mandataire
 * @property integer $original
 * @property string $type_transaction
 * @property string $produit
 * @property integer $millesime
 * @property string $domaine
 * @property acCouchdbJson $label
 * @property float $raisin_quantite
 * @property float $jus_quantite
 * @property integer $bouteilles_quantite
 * @property integer $bouteilles_contenance
 * @property float $prix_unitaire
 * @property float $prix_total
 * @property string $type_contrat
 * @property integer $prix_variable
 * @property float $part_variable
 * @property float $taux_variation
 * @property string $cvo_nature
 * @property string $cvo_repartition
 * @property string $date_stats
 * @property string $date_signature
 * @property acCouchdbJson $valide

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getNumeroContrat()
 * @method string setNumeroContrat()
 * @method integer getEtape()
 * @method integer setEtape()
 * @method string getVendeurIdentifiant()
 * @method string setVendeurIdentifiant()
 * @method acCouchdbJson getVendeur()
 * @method acCouchdbJson setVendeur()
 * @method string getAcheteurIdentifiant()
 * @method string setAcheteurIdentifiant()
 * @method acCouchdbJson getAcheteur()
 * @method acCouchdbJson setAcheteur()
 * @method string getMandataireExist()
 * @method string setMandataireExist()
 * @method acCouchdbJson getMandatant()
 * @method acCouchdbJson setMandatant()
 * @method string getMandataireIdentifiant()
 * @method string setMandataireIdentifiant()
 * @method acCouchdbJson getMandataire()
 * @method acCouchdbJson setMandataire()
 * @method integer getOriginal()
 * @method integer setOriginal()
 * @method string getTypeTransaction()
 * @method string setTypeTransaction()
 * @method string getProduit()
 * @method string setProduit()
 * @method integer getMillesime()
 * @method integer setMillesime()
 * @method string getDomaine()
 * @method string setDomaine()
 * @method acCouchdbJson getLabel()
 * @method acCouchdbJson setLabel()
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
 * @method float getTauxVariation()
 * @method float setTauxVariation()
 * @method string getCvoNature()
 * @method string setCvoNature()
 * @method string getCvoRepartition()
 * @method string setCvoRepartition()
 * @method string getDateStats()
 * @method string setDateStats()
 * @method string getDateSignature()
 * @method string setDateSignature()
 * @method acCouchdbJson getValide()
 * @method acCouchdbJson setValide()
 
 */
 
abstract class BaseVrac extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Vrac';
    }
    
}