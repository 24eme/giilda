<?php

function getLabelForKeyArgument($key)
{
   switch($key) {
       case 'regions' : return 'Régions :';
       case 'type_document' : return 'Type de document :';
       case 'operateur_types' : return "Types d'opérateur :";
       case 'date_declaration' : return 'Date de déclaration :';
       case 'date_facturation' : return 'Date de facturation :';
       case 'date_mouvement' : return 'Date de prise en compte des mouvements :';
       case 'seuil' : return 'Seuil :';
       default: return "$key :";
   }
}
