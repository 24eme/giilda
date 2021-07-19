<?php
/**
 * BaseDS
 *
 * Base model for DS
 *

 */

abstract class BaseDS extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'DS';
    }

}
