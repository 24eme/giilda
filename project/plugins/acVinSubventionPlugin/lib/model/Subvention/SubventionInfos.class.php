<?php
/**
 * Model for SubventionInfos
 *
 */

class SubventionInfos extends BaseSubventionInfos {

    public function add($key = null, $item = null) {
        $item = parent::add($key, $item);

        foreach($item->getInfosSchema() as $key => $schema) {
            if(!is_array($schema)) {
                continue;
            }
            $item->add($key);
        }

        return $item;
    }
}
