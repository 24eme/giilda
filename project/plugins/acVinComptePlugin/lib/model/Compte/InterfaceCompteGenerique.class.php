<?php

interface InterfaceCompteGenerique
{
    public function setAdresse($s);
    public function setCommune($s);
    public function setCodePostal($s);
    public function setPays($s);
    public function setAdresseComplementaire($s);

    public function getAdresse();
    public function getCommune();
    public function getCodePostal();
    public function getPays();
    public function getAdresseComplementaire();

    public function setEmail($email);
    public function setTelephone($phone);
    public function setTelephonePerso($s);
    public function setTelephoneMobile($s);
    public function setTelephoneBureau($tel);
    public function setSiteInternet($s);
    public function setFax($fax);

    public function getEmail();
    public function getTelephone();
    public function getTelephoneBureau();
    public function getTelephonePerso();
    public function getTelephoneMobile();
    public function getSiteInternet();
    public function getFax();
}
