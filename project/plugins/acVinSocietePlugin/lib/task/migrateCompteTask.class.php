<?php

class migrateCompteTask extends sfBaseTask
{
  protected static $list_fields_analysed = array("_id", "_rev", "type", "identifiant", "num_interne", "civilite","prenom", "nom", "nom_a_afficher", "fonction", "commentaire","mot_de_passe", "origines", "id_societe", "adresse_societe", "adresse","adresse_complementaire","code_postal", "commune", "insee", "compte_type", "cedex",
  "pays","email","telephone_perso", "telephone_bureau", "telephone_mobile", "fax", "site_internet", "lat", "lon","societe_informations","etablissement_informations", "interpro", "statut","groupes","tags","teledeclaration_active","date_modification","droits");

  protected function execute($arguments = array(), $options = array())
  {

  }


  protected function verifySocieteInformationNode($compte,$societe,$value,$verbose){
    if($societe->type_societe != $value->type){
      throw new sfException("Le type de societe du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value->type);
    }
    if($verbose){
      echo "$societe->_id : Le compte $compte->_id a pour societe->type : ".$value->type."\n";
    }
    if($societe->raison_sociale != $value->raison_sociale){
      throw new sfException("La raison sociale du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value->raison_sociale);
    }
    if($verbose){
      echo "$societe->_id : Le compte $compte->_id a pour societe->raison_sociale : ".$value->raison_sociale."\n";
    }
    if($societe->adresse != $value->adresse){
      throw new sfException("L'adresse du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value->adresse);
    }
    if($verbose){
      echo "$societe->_id : Le compte $compte->_id a pour societe->adresse : ".$value->adresse."\n";
    }
    if($societe->adresse_complementaire != $value->adresse_complementaire){
      throw new sfException("L'adresse complementaire du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value->adresse_complementaire);
    }
    if($verbose){
      echo "$societe->_id : Le compte $compte->_id a pour societe->adresse_complementaire : ".$value->adresse_complementaire."\n";
    }
    if($societe->code_postal != $value->code_postal){
      throw new sfException("Le code_postal du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value->code_postal);
    }
    if($verbose){
      echo "$societe->_id : Le compte $compte->_id a pour societe->code_postal : ".$value->code_postal."\n";
    }
    if($societe->email != $value->email){
      throw new sfException("L'email du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value->email);
    }
    if($verbose){
      echo "$societe->_id : Le compte $compte->_id a pour societe->email : ".$value->email."\n";
    }
    if($societe->telephone != $value->telephone){
      throw new sfException("Le telephone du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value->telephone);
    }
    if($verbose){
      echo "$societe->_id : Le compte $compte->_id a pour societe->telephone : ".$value->telephone."\n";
    }
    if($societe->fax != $value->fax){
      throw new sfException("Le fax du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value->fax);
    }
    if($verbose){
      echo "$societe->_id : Le compte $compte->_id a pour societe->fax : ".$value->fax."\n";
    }
  }

  protected function verifySocieteDuplicatedInfos($compte,$societe,$key,$value,$verbose){
    if($key == 'id_societe' && ($value != $societe->_id)){
      throw new sfException("L'id de la société $value n'est pas l'identifiant de la societe $societe->_id !");
    }

    if($key == 'adresse_societe'){
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour adresse de societe : ".$value."\n";
      }
    }
  }

  protected function verifyAdresseSociete($compte,$societe,$key,$value,$verbose){
    if($key == 'adresse'){
      if($societe->siege->adresse != $value){
        throw new sfException("L'adresse du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value);
      }
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour adresse : ".$value."\n";
      }
    }

    if($key == 'adresse_complementaire'){
      if($societe->siege->adresse_complementaire != $value){
        throw new sfException("L'adresse complementaire du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value);
      }
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour adresse complementaire : ".$value."\n";
      }
    }
    if($key == 'code_postal'){
      if($societe->siege->code_postal != $value){
        throw new sfException("Le code postal  du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value);
      }
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour code postal : ".$value."\n";
      }
    }
    if($key == 'commune'){
      if($societe->siege->commune != $value){
        throw new sfException("La commune du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value);
      }
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour commune : ".$value."\n";
      }
    }
    if($key == 'insee'){
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour insee : ".$value."\n";
      }
    }
    if($key == 'cedex'){
      if($value){
        throw new sfException("Le cedex du compte $compte->_id n'est pas null ! : ".$value);
      }
    }
    if($key == 'pays'){
      if($societe->siege->pays != $value){
        throw new sfException("Le pays du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value);
      }
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour pays : ".$value."\n";
      }
    }
  }

  protected function verifyContactSociete($compte,$societe,$key,$value,$verbose){
    if($key == 'email'){
      if($societe->email != $value){
        throw new sfException("L'email du compte $compte->_id n'est pas la même que celle dans la société $societe->_id : ".$value);
      }
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour email : ".$value."\n";
      }
    }
    if($key == 'telephone_perso'){
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour téléphone perso : ".$value."\n";
      }
    }
    if($key == 'telephone_bureau'){
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour telephone bureau : ".$value."\n";
      }
    }
    if($key == 'telephone_mobile'){
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour telephone mobile : ".$value."\n";
      }
    }
    if($key == 'fax'){
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour fax : ".$value."\n";
      }
    }
    if($key == 'site_internet'){
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour site internet : ".$value."\n";
      }
    }
  }
  protected function displayGroupesTagsAndDroits($compte,$societe,$key,$value,$verbose){
    if($key == 'groupes'){
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a un ensemble de groupes qui seront recopiés tel quels \n";
        echo "$societe->_id : Le compte $compte->_id a pour groupes : ";
        foreach ($value as $keygroupe => $valuegroupe) {
          echo "   [nom=".$valuegroupe->nom.",fonction=".$valuegroupe->fonction."]";
        }
        echo "\n";
      }
    }
    if($key == 'tags'){
      $fields = get_object_vars($value);
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a un ensemble de tags qui seront recopiés tel quels \n";
        echo "$societe->_id : Le compte $compte->_id a pour tags : ";
        foreach ($fields as $keytags => $valuetags) {
          echo "   [$keytags]=(".implode(",",$valuetags).")";
        }
        echo "\n";
      }
    }
    if($key == 'droits'){
      if(count($value)){
        throw new sfException("Le compte de la société $compte->_id possède des droits : ".implode(",",$value));
      }
      if($this->verbose){
        echo "$societe->_id : Le compte $compte->_id a pour droits : ".implode(",",$value)."\n";
      }
    }
  }
}
