<?php

class SV12LightRoute extends sfRequestRoute {

    protected $sv12 = null;

    protected function getSV12ForParameters($parameters) {
        $id = 'SV12-' . $parameters['identifiant'] . '-' . $parameters['periode'];

        $sv12 = SV12Client::getInstance()->find($id);

        if (!$sv12) {
            throw new sfError404Exception(sprintf("The document '%s' not found", $id));
        }

        if (isset($this->options['must_be_valid']) && $this->options['must_be_valid'] === true && !$sv12->isValidee()) {
            throw new sfError404Exception('DRM must be validated');
        }
        if (isset($this->options['must_be_not_valid']) && $this->options['must_be_not_valid'] === true && $sv12->isValidee()) {
            throw new sfError404Exception('DRM must not be validated');
        }
        return $sv12;
    }

    public function getSV12() {
        if (is_null($this->sv12)) {
            $this->sv12 = $this->getSV12ForParameters($this->parameters);
        }

        return $this->sv12;
    }

}