<?php

class SubventionClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("Subvention");
    }
}
