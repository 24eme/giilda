<?php
/*
 * Inclusion du panel de progression d'édition du contrat
 */
if(!$contratNonSolde) include_partial('contrat_progression', array('vrac' => $vrac));

/*
 * Inclusion du panel pour les contrats similaires
 */
include_partial('contratsSimilaires', array('vrac' => $vrac));

/*
 * Inclusion des Contacts
 */
include_partial('contrat_infos_contact', array('vrac' => $vrac));

/*
 * Inclusion de l'aide
 */
include_partial('contrat_aide', array('vrac' => $vrac));

?>