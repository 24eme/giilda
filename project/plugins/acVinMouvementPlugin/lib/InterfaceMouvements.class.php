<?php

interface InterfaceMouvements 
{
    public function clearMouvements();
    public function generateMouvements();
    public function findMouvements($cle);
    public function getMouvements();
}