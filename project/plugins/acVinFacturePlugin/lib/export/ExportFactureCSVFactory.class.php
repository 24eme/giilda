<?php

class ExportFactureCSVFactory
{

    public static function getObject($application, $ht = false) {
        $class = "ExportFactureCSV_".$application;

        return new $class($ht);
    }
}
