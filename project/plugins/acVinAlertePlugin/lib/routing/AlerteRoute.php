<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class RevendicationRoute
 * @author mathurin
 */
class AlerteRoute extends sfObjectRoute  {

	protected $alerte = null;

	protected function getObjectForParameters($parameters) {
        if (in_array($parameters['type_alerte'], $this->getTypesAlerte())) {            
            $type_alerte = $parameters['type_alerte'];
            
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'type_alerte', $parameters['type_alerte']));
        }
        if (preg_match('/^[A-Z12]+[-]{1}[0-9-M]*$/',$parameters['id_document'])) {            
            $id_document = $parameters['id_document'];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'id_document', $parameters['id_document']));
        }       

        $this->alerte = AlerteClient::getInstance()->findByTypeAndIdDocument($type_alerte,$id_document);
        if (!$this->alerte) {
            throw new sfError404Exception(sprintf('No Alerte found with the type "%s" and the document "%s".',  $parameters['type_alerte'],$parameters['id_document']));
        }
        return $this->alerte;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("id_document" => $object->id_document, "type_alerte" => $object->type_alerte);
        return $parameters;
    }

    public function getAlerte() {
        if (!$this->alerte) {
            $this->alerte = $this->getObject();
        }

        return $this->alerte;
    }

    public function getTypesAlerte() {
        return array_keys(AlerteClient::$alertes_libelles);
    }
}