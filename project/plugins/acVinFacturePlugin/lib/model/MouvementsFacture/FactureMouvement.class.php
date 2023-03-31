<?php

/**
 * Model for FactureMouvement
 *
 */
class FactureMouvement extends BaseFactureMouvement {

    public function updateIdentifiantAnalytique($identifiant_analytique) {
        $interpro = ($this->exist('interpro'))? $this->get('interpro') : null;
        $comptabiliteDoc = ComptabiliteClient::getInstance()->findCompta($interpro);
        $node_analytique = null;
        $identifiant_analytique_Keys = explode('_',$identifiant_analytique);
        foreach ($comptabiliteDoc->get('identifiants_analytiques') as $analytiquesCompta) {
          if(($analytiquesCompta->identifiant_analytique == $identifiant_analytique_Keys[1])
           && ($analytiquesCompta->identifiant_analytique_numero_compte == $identifiant_analytique_Keys[0])){
             $node_analytique = $analytiquesCompta;
             break;
           }
        }
        $comptabiliteDoc->get('identifiants_analytiques')->get($node_analytique->getKey());
        if (!$node_analytique) {
            throw new sfException("L'identifiant analytique $identifiant_analytique n'existe pas dans le document de COMPTABILITE");
        }
        $this->setIdentifiantAnalytique($identifiant_analytique);
        $this->setIdentifiantAnalytiqueLibelleCompta($node_analytique->identifiant_analytique_libelle_compta);
    }

    public function getIndexForSaisieForm() {
      $numOrdre = (!$this->vrac_numero)? 0 : $this->vrac_numero*1; // utilisation du champ vrac_numero pour ordonner la facturation libre
      if ($this->getKey() == 'nouveau') {
        $numOrdre = '999';
      }
      return sprintf('%03d', $numOrdre).'_'.$this->getParent()->getKey().'_'.$this->getKey();
    }

}
