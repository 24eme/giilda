<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class RevendicationRoute
 * @author mathurin
 */
class RelanceRoute extends sfObjectRoute implements InterfaceEtablissementRoute {

	protected $relance = null;

	protected function getObjectForParameters($parameters) {
        if (preg_match('/^([0-9]{8})-([A-Z]*)-([0-9]{10})$/',$parameters['idrelance'],$idr)) {            
            $identifiant = $idr[1];
            $type = $idr[2];
            $ref = $idr[3];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'idrelance', $parameters['idrelance']));
        }
        $this->relance = RelanceClient::getInstance()->findByIdentifiantTypeAndRef($identifiant,$type,$ref);
        if (!$this->relance) {
            throw new sfError404Exception(sprintf('No Relance found with the idrelance "%s".',  $parameters['idrelance']));
        }
        return $this->relance;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("idrelance" => $object->idrelance);
        return $parameters;
    }

    public function getRelance() {
        if (!$this->relance) {
            $this->getObject();
        }

        return $this->relance;
    }

    public function getEtablissement() {
        return $this->getRelance()->getEtablissement();
    }

}