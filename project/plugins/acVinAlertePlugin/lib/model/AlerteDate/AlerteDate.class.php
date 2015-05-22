<?php
/**
 * Model for AlerteDate
 *
 */

class AlerteDate extends BaseAlerteDate {

    public function __construct() {
        parent::__construct();
    }
    
    protected function constructId() {
        $this->_id = AlerteDateClient::getInstance()->buildId();
    }
}