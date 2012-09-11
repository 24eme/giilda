<?php

class SV12Route extends sfObjectRoute {

	protected $sv12 = null;


    protected function getSV12ForParameters($parameters) {
        $id = 'SV12-'.$parameters['identifiant'].'-'.$parameters['periode'];
        
        $sv12 = SV12Client::getInstance()->find($id);

        if (!$sv12) {
            throw new sfError404Exception(sprintf("The document '%s' not found", $id));
        }
        return $sv12;
    }
    
   public function getSV12Configuration() {
        
        return ConfigurationClient::getCurrent();
    }
    protected function doConvertObjectToArray($object) {  
        $parameters = array("identifiant" => $object->identifiant, "periode" => $object->periode);
        
        return $parameters;
    }

    public function getSV12() {
        if (is_null($this->sv12)) {
            $this->sv12 = $this->getSV12ForParameters($this->parameters);
        }

        return $this->sv12;
    }

}