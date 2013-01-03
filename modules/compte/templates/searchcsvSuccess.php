<?php
echo "#nom complet ; type ; civilité ; nom ; prénom ; adresse ; adresse complémentaire ; code postal ; commune ; pays ; téléphone bureau ; téléphone mobile ; téléphone perso ; fax ; email; id societe\n";
foreach ($results as $res) {
  $data = $res->getData();
  echo '"'.$data['nom_a_afficher']. '";';
  echo '"'.CompteClient::getInstance()->createTypeFromOrigines($data['origines']).'";';
  echo '"'.$data['civilite']. '";';
  echo '"'.$data['nom']. '";';
  echo '"'.$data['prenom']. '";';
  echo '"'.$data['adresse']. '";';
  echo '"'.$data['adresse_complementaire']. '";';
  echo '"'.$data['code_postal']. '";';
  echo '"'.$data['commune']. '";';
  echo '"'.$data['pays']. '";';
  echo '"'.$data['telephone_bureau']. '";';
  echo '"'.$data['telephone_mobile']. '";';
  echo '"'.$data['telephone_perso']. '";';
  echo '"'.$data['fax']. '";';
  echo '"'.$data['email']. '";';
  echo '"'.preg_replace('/SOCIETE-/', '', $data['id_societe']). '";';
  echo "\n";
}
