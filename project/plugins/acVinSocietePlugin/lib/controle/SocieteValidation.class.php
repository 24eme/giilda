<?php
class SocieteValidation extends DocumentValidation
{
    public function configure() {
        $this->addControle('erreur', 'siret_unique', "Ce numéro de siret existe déjà");
    }

    public function controle()
    {
        if($this->document->siret) {
            $societe = SocieteClient::getInstance()->findBySiret($this->document->siret);
            if($societe) {
                $this->addPoint('erreur', 'siret_unique', sprintf("Societe %s", $societe->raison_sociale), $this->generateUrl('societe_visualisation', $societe));
            }
        }

    }
}