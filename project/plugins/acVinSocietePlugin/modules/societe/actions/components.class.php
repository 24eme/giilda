<?php

class societeComponents extends sfComponents {

    public function executeChooseSociete() {
        if (!$this->form) {
            $this->form = new SocieteChoiceForm('INTERPRO-declaration',
                            array('identifiant' => $this->identifiant));
        }
    }

    public function executeSidebar() {
        $this->etablissements = $this->societe->getEtablissementsObject();
        $this->interlocuteurs = array();
        $this->modifiable = !isset($this->modifiable) || $this->modifiable;

        foreach($this->societe->getInterlocuteurs() as $interlocuteur) {
            if(!$interlocuteur) {
                continue;
            }
            if ($interlocuteur->isSocieteContact() || $interlocuteur->isEtablissementContact()) {
                continue;
            }
            $this->interlocuteurs[$interlocuteur->_id] = $interlocuteur;
        }

        uasort($this->interlocuteurs, function($a, $b) {

            return $a->isSuspendu();
        });
    }
}
