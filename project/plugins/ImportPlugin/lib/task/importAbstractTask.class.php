<?php

abstract class importAbstractTask extends sfBaseTask
{
    protected static $months_fr = array(
    "août" => "08",
    "avr" => "04",
    "déc" => "12",
    "févr" => "02",
    "janv" => "01",
    "juil" => "07",
    "juin" => "06",
    "mai" => "05",
    "mars" => "03",
    "nov" => "11",
    "oct" => "10",
    "sept" => "09",
    );

    protected $produits_hash = null;

    protected function convertToFloat($number) {

        return str_replace(",", ".", $number) * 1;
    }

    protected function convertToDateObject($date) {
        if (preg_match('/^([0-9]{2})-([a-zûé]+)-([0-9]{2})$/', $date, $matches)) {
      
            return new DateTime(sprintf('%d-%d-%d', $matches[3], self::$months_fr[$matches[2]], $matches[1]));
        }

        if (preg_match('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $date, $matches)) {

            return new DateTime(sprintf('%d-%d-%d', $matches[3], $matches[2], $matches[1]));
        }

        $this->logSection('Date format error', "'".$date."'", null, 'ERROR');

        return new DateTime();
    }

    protected function convertOuiNon($indicateur) {

        return (int) ($indicateur == 'O');
    }

    protected function getKey($key, $withDefault = false) 
    {
        if ($withDefault) {
            
            return ($key)? $key : Configuration::DEFAULT_KEY;
        } 
        if (!$key) {
            
            throw new Exception('La clé "'.$key.'" n\'est pas valide');
        }
        
        return $key;
    }

    protected function getConfigurationHash($code) 
    {

        return ConfigurationClient::getCurrent()->get($this->getHash($code));
    }

    protected function getHash($code) 
    {
        $produits_hash = $this->getProduitsHash();

        if (!array_key_exists($code*1, $produits_hash)) {
      
            throw new sfException(sprintf("Le produit avec le code %s n'existe pas", $code));
        }

        return $produits_hash[$code*1];
    }

    protected function getProduitsHash() {
        if (is_null($this->produits_hash)) {
            $this->produits_hash =  ConfigurationClient::getCurrent()->declaration->getProduitsHashByCodeProduit('INTERPRO-inter-loire');
        }

        return $this->produits_hash;
    }
}