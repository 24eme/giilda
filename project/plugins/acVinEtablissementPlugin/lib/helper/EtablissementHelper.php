<?php

function display_adresse_societe($etablissement){
    echo ($etablissement->isSameCompteThanSociete())? 'Oui' : 'Non';
}