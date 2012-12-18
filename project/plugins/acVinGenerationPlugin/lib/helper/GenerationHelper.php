<?php

function getLabelForKeyArgument($key)
{
   switch($key) {
       case 'regions' : return 'Régions :';
       case 'operateur_types' : return "Types d'opérateur :";
       case 'date_declaration' : return 'Date de déclaration :';
   }
}
