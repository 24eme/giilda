<?php

class PointsAidesConfiguration {

    private static $_instance = null;
    protected $pointsAides;

    const ALL_KEY = "_ALL";

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new PointsAidesConfiguration();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->pointsAides = sfConfig::get('configuration_points_aides', array());
    }

    public function getPointAide($categorie,$ptAideId) {
        return $this->pointsAides[$categorie][$ptAideId];
    }

}
