<?php

class InterproClient extends acCouchdbClient {

    /**
     *
     * @return _ContratClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Interpro");
    }

    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Interpro
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->getInterpro();
    }

    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Interpro
     */
    public function getById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->getInterpro();
    }

    public function find($id, $hydrate = self::HYDRATE_DOCUMENT, $force_return_ls = false) {

        return $this->getInterpro();
    }

    protected function getInterpro() {
        $interpro = new Interpro();
        $interpro->identifiant = 'interpro';
        $interpro->nom = "InterPro";
        $interpro->set('_id', 'INTERPRO-'.$interpro->identifiant);

        return $interpro;
    }

    /**
     *
     * @param integer $hydrate
     * @return acCouchdbDocumentCollection
     * @todo remplacer la fonction par une vue
     */
    public function getAll($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->keys(array('INTERPRO-CIVP', 'INTERPRO-IR', 'INTERPRO-IVSE'))->execute($hydrate);
    }


}
