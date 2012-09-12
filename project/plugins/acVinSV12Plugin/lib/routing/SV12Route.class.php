<?php

class SV12Route extends sfObjectRoute {

    protected $sv12 = null;
    
    protected function getObjectForParameters($parameters) {

        $this->sv12 = SV12Client::getInstance()->find('SV12-'.$parameters['identifiant'].'-'.$parameters['periode_version']);

        if (!$this->sv12) {
            throw new sfError404Exception(sprintf('No SV12 found for this periode-version "%s".',  $parameters['periode_version']));
        }

        return $this->sv12;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("identifiant" => $object->getIdentifiant(), "periode_version" => $object->getPeriodeAndVersion());
        return $parameters;
    }
    
    public function getSV12() {
        if (!$this->sv12) {
            $this->getObject();
        }

        return $this->sv12;
    }

}