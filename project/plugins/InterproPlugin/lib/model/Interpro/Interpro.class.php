<?php
class Interpro extends BaseInterpro {
	const INTER_RHONE_ID = 'IR';
	const CIVP_ID = 'CIVP';
	const INTERVINS_SUD_EST_ID = 'IVSE';
  public function __toString() {
    return $this->nom;
  }
  public function getKey() {
    return $this->_id;
  }
}