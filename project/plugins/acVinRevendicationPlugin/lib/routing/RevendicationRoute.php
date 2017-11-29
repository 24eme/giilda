<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class RevendicationRoute
 * @author mathurin
 */
class RevendicationRoute extends sfObjectRoute  {

	protected $revendication = null;

	protected function getObjectForParameters($parameters) {

        if (in_array($parameters['odg'], $this->getOdgs())) {
            $odg = $parameters['odg'];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'odg', $parameters['odg']));
        }

        if (preg_match('/^[0-9]{4}-[0-9]{4}$/',$parameters['campagne'])) {
            $campagne = $parameters['campagne'];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'campagne', $parameters['campagne']));
        }

        $this->revendication = RevendicationClient::getInstance()->findByOdgAndCampagne($odg,$campagne);
        if (!$this->revendication) {
            throw new sfError404Exception(sprintf('No Revendication found with the id "%s" and the campagne "%s".',  $parameters['identifiant'],$parameters['campagne']));
        }
        return $this->revendication;
    }

    protected function doConvertObjectToArray($object) {
        $parameters = array("odg" => $object->odg, "campagne" => $object->campagne);
        return $parameters;
    }

    public function getRevendication() {
        if (!$this->revendication) {
            $this->getObject();
        }

        return $this->revendication;
    }

    public function getOdgs() {
        return EtablissementClient::getRegionsWithoutHorsInterLoire(true);
    }
}
