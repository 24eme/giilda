<?php
/**
 * Model for SubventionInfos
 *
 */

class SubventionInfos extends BaseSubventionInfos {

    public function add($key = null, $item = null) {
        $item = parent::add($key, $item);

        foreach($item->getInfosSchema() as $key => $schema) {
            $item->add($key);
        }

        return $item;
    }
}
