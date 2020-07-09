<?php
/**
 * Model for SubventionInfosCategorieCollection
 *
 */

class SubventionInfosCategorieCollection extends BaseSubventionInfosCategorieCollection {
    public function add($key = null, $item = null) {
        $item = $this->_add($key, $item);
        foreach($this->getInfosSchema() as $key => $schema) {
            $item->add($key);
        }

        return $item;
    }

    public function getInfosSchema() {
        $schema = $this->getParent()->getInfosSchema();

        return $schema[$this->getKey()];
    }

    public function getInfosSchemaItem($key, $config) {
        $schema = $this->getInfosSchema();

        return isset($schema[$key][$config]) ? $schema[$key][$config] : null;
    }
}
