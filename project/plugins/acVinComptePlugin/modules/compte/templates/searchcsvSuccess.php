<?php
printf("\xef\xbb\xbf");//UTF8 BOM (pour windows)
echo "#nom complet ; type ; civilité ; nom ; prénom ; adresse ; adresse complémentaire ; code postal ; commune ; pays ; téléphone bureau ; téléphone mobile ; téléphone perso ; fax ; email ; commentaire ; id société ; type société ; société raison sociale ; société adresse ; société adresse complémentaire ; société code postal ; société commune ; société téléphone ; société fax ; société email\n";
foreach ($results as $res) {
  $data = $res->getData();
  
  $societe_informations = $data['doc']['societe_informations'];
                                                
  echo '"'.$data['doc']['nom_a_afficher']. '";';
  echo '"'.CompteClient::getInstance()->createTypeFromOrigines($data['doc']['origines']).'";';
  echo '"'.$data['doc']['civilite']. '";';
  echo '"'.$data['doc']['prenom']. '";';
  echo '"'.$data['doc']['nom']. '";';
  echo '"'.$data['doc']['adresse']. '";';
  echo '"'.$data['doc']['adresse_complementaire']. '";';
  echo '"'.$data['doc']['code_postal']. '";';
  echo '"'.$data['doc']['commune']. '";';
  echo '"'.$data['doc']['pays']. '";';
  echo '"'.$data['doc']['telephone_bureau']. '";';
  echo '"'.$data['doc']['telephone_mobile']. '";';
  echo '"'.$data['doc']['telephone_perso']. '";';
  echo '"'.$data['doc']['fax']. '";';
  echo '"'.$data['doc']['email']. '";';  
  echo '"'.$data['doc']['commentaire']. '";';
  echo '"'.preg_replace('/SOCIETE-/', '', $data['doc']['id_societe']). '";';
  echo '"'.$societe_informations['type']. '";';
  echo '"'.$societe_informations['raison_sociale']. '";';
  echo '"'.$societe_informations['adresse']. '";';
  echo '"'.$societe_informations['adresse_complementaire']. '";';
  echo '"'.$societe_informations['code_postal']. '";';
  echo '"'.$societe_informations['commune']. '";';
  echo '"'.$societe_informations['telephone']. '";';
  echo '"'.$societe_informations['fax']. '";';
  echo '"'.$societe_informations['email']. '"';
  echo "\n";
}
