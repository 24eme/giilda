<?php
class MandatSepaRoute extends sfObjectRoute
{
    protected $mandatSepa = null;

    protected function getObjectForParameters($parameters) {
        $this->mandatSepa = MandatSepaClient::getInstance()->find($parameters['id']);
        if (!$this->mandatSepa) {
            throw new sfError404Exception(sprintf("Pas de MandatSepa trouvé avec l'id \"%s\"", $parameters['id']));
        }
        return $this->mandatSepa;
    }

    protected function doConvertObjectToArray($object) {
        $parameters = array("id" => $object->_id);
        return $parameters;
    }

    public function getMandatSepa() {
        if (!$this->mandatSepa) {
            $this->getObject();
        }
        return $this->mandatSepa;
    }
}
