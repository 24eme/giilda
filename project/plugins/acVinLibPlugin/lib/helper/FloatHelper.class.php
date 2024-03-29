<?php

class FloatHelper
{
    protected $defaultDecimalFormat = 2;
    protected $maxDecimalAuthorized = 2;
    protected static $self = null;

    public function __construct() {
        $this->defaultDecimalFormat = sfConfig::get('app_float_default_decimal_format', $this->defaultDecimalFormat);
        $this->maxDecimalAuthorized = sfConfig::get('app_float_max_decimal_authorized', $this->maxDecimalAuthorized);
    }

    public static function getInstance()
    {
        if(is_null(self::$self)) {
            self::$self = new FloatHelper();
        }

        return self::$self;
    }

    public function getDefaultDecimalFormat() {

        return $this->defaultDecimalFormat;
    }

    public function getMaxDecimalAuthorized() {

        return $this->maxDecimalAuthorized;
    }

    public function format($number, $defaultDecimalFormat = null, $maxDecimalAuthorized = null, $format = null, $milliSeparate = false) {
        if ($number === "" || is_null($number)) {
            return null;
        }

        $int = $number;
        $float = null;

        if(count(explode(".", $number."")) >= 2) {
            list($int, $float) = explode(".", sprintf("%.10f", $number));
            $float = rtrim($float, '0');
        }

        $defaultDecimalFormat = (is_null($defaultDecimalFormat)) ? $this->getDefaultDecimalFormat() : $defaultDecimalFormat;
        $maxDecimalAuthorized = (is_null($maxDecimalAuthorized)) ? $this->getMaxDecimalAuthorized() : $maxDecimalAuthorized;

        if(strlen($float) <= $defaultDecimalFormat) {
            $nbDecimal = $defaultDecimalFormat;
        } elseif(strlen($float) > $defaultDecimalFormat && strlen($float) <= $maxDecimalAuthorized) {
            $nbDecimal = strlen($float);
        } else {
            $nbDecimal = $maxDecimalAuthorized;
        }
		$separate = ($milliSeparate)? ' ' : '';
        if ($number < 1 && $number > -1) {
            return sprintf('%.'.$nbDecimal.'f', $number);
        }
        return number_format($number, $nbDecimal, '.', $separate);
    }

    public function formatFr($number, $defaultDecimalFormat = null, $maxDecimalAuthorized = null, $format = null, $milliSeparate = false) {

        return str_replace(".", ",", $this->format($number, $defaultDecimalFormat, $maxDecimalAuthorized, $format, $milliSeparate));
    }

}
