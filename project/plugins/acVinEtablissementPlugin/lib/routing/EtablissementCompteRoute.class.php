<?php

class EtablissementCompteRoute extends EtablissementRoute implements InterfaceCompteRoute {

    public function getCompte() {

        return $this->getEtablissement()->getMasterCompte();
    }
}
