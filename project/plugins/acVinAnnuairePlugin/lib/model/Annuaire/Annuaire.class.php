<?php
/**
 * Model for Annuaire
 *
 */

class Annuaire extends BaseAnnuaire
{
    public function constructId()
    {
        $this->set('_id', AnnuaireClient::getInstance()->buildId($this->identifiant));
    }

    /**
     * Ajoute une entrée dans un annuaire
     *
     * @param Etablissement $etablissement L'objet Etablissement du tier à ajouter
     * @param string $type Le type de tier (récoltant ou négociant)
     *
     * @throws Exception Si le type n'existe pas
     */
    public function addTier(Etablissement $etablissement, $type = AnnuaireClient::ANNUAIRE_RECOLTANTS_KEY)
    {
        $types = AnnuaireClient::getAnnuaireTypes();

        $partielibelle = [
            ($etablissement->nom) ?: $etablissement->raison_sociale,
            ($etablissement->cvi) ?: '',
            ($etablissement->no_accises) ?: ''
        ];
        $libelle = implode(' - ', array_filter($partielibelle));

        if (! array_key_exists($type, $types)) {
            throw new Exception("Type $type inexistant");
        }

        $this->get($type)->add($etablissement->_id, $libelle);
    }
}
