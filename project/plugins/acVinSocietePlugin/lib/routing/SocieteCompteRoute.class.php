<?php
class SocieteCompteRoute extends SocieteRoute implements InterfaceCompteRoute {

    public function getCompte() {

        return $this->getSociete()->getMasterCompte();
    }
}
