<?php

class ExportFactureCSVFactory
{

    public static function getObject($environment, $ht = false) {

        return new ExportFactureCSV_sancerre($ht);
    }
}
