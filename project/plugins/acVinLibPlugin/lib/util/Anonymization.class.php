<?php

class Anonymization {

    public static function hideIfNeeded($s) {
        if (getenv('ANONYMIZATION_HIDE')) {
            return preg_replace('/[^ @\.]/', 'X', $s);
        }else{
            return $s;
        }
    }

}
