<?php

interface InterfaceMouvementDocument
{
    public function getMouvements();
    public function getMouvementsCalcule();
    public function getMouvementsCalculeByIdentifiant($identifiant);
    public function generateMouvements();
    public function findMouvement($cle);
    public function clearMouvements();
}