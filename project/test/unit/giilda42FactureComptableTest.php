<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$t = new lime_test(33);

$t->comment("Création d'un export de facturation à partir des facture pour une société");


$societeViti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getSociete();
$facture = null;
foreach (FactureSocieteView::getInstance()->findBySociete($societeViti) as $id => $facture) {
    $facture = FactureClient::getInstance()->find($id);
    break;
}

if($facture){
  $export = ExportFactureCSVFactory::getObject($application);
  ob_start();
  $export->printFacture($facture->_id);
  $exportCompta = ob_get_contents();
  ob_end_clean();
}


$arrayCompta = explode("\n",$exportCompta);
$t->is(count($arrayCompta), 5, "Le nombre de ligne pour la compta est bien ".count($arrayCompta));
foreach ($arrayCompta as $cpt => $row) {
    if(!$row){
      continue;
    }
    $fieldArray = explode(";",$row);

    $t->is(count($fieldArray), 23, "Le nombre de champs pour la ligne $cpt du fichier de compta est bien ".count($fieldArray));
    $t->is($fieldArray[0], "02", "Le préfix est bon");

    $dateF = preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$fieldArray[1]);
    $t->ok($dateF, "La date de facturation a le bon format");

    $dateF = preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$fieldArray[2]);
    $t->ok($dateF, "La date d'emission a le bon format");

    $t->is($facture->numero_piece_comptable, $fieldArray[3], "Le numéro pièce comptable est bien celui de la facture");

    $desc = $fieldArray[4];
    $t->ok($desc, "Il y a une description comptable $desc ");

    $codeCompte = $fieldArray[5];
    $prix = $fieldArray[10];

    $found = false;
    foreach ($facture->lignes as $k => $lignes) {
        foreach ($lignes->details as $detail) {
          if(($detail->code_compte == $codeCompte) && ($detail->montant_ht == $prix)){
            $found = $detail->getHash();
          }
        }
        if($lignes->montant_tva == $prix){
          $found = "LIGNE DE TVA";
        }
        if($lignes->montant_ht + $lignes->montant_tva == $prix){
          $found = "LIGNE DE TTC";
        }
      }

    $resMontantCodeCompte = boolval($found);
    $t->ok($resMontantCodeCompte, "Le code compte $codeCompte et le montant $prix est bien celui de la facture $facture->_id de la ligne $found");

    $debitOuCredit = preg_match('/^(DEBIT|CREDIT)$/',$fieldArray[9]);
    $t->ok($debitOuCredit, "La ligne est bien du CREDIT ou du DEBIT");

}
exit;
