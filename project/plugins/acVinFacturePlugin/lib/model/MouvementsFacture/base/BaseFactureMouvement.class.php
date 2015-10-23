<?php
/**
 * BaseFactureMouvement
 * 
 * Base model for FactureMouvement

 * @property string $identifiant_analytique
 * @property string $identifiant_analytique_libelle
 * @property string $identifiant_analytique_libelle_compta
 * @property string $identifiant
 * @property string $libelle
 * @property float $quantite
 * @property float $prix_unitaire
 * @property integer $facture
 * @property integer $facturable

 * @method string getIdentifiantAnalytique()
 * @method string setIdentifiantAnalytique()
 * @method string getIdentifiantAnalytiqueLibelle()
 * @method string setIdentifiantAnalytiqueLibelle()
 * @method string getIdentifiantAnalytiqueLibelleCompta()
 * @method string setIdentifiantAnalytiqueLibelleCompta()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method float getQuantite()
 * @method float setQuantite()
 * @method float getPrixUnitaire()
 * @method float setPrixUnitaire()
 * @method integer getFacture()
 * @method integer setFacture()
 * @method integer getFacturable()
 * @method integer setFacturable()
 
 */

abstract class BaseFactureMouvement extends Mouvement {
                
    public function configureTree() {
       $this->_root_class_name = 'MouvementsFacture';
       $this->_tree_class_name = 'FactureMouvement';
    }
                
}