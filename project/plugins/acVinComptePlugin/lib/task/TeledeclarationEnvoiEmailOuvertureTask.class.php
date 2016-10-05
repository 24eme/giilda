<?php

class TeledeclarationEnvoiEmailOuvertureTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addArguments(array(
      new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, 'Societe identifiant'),
    ));
    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'teledeclaration';
    $this->name             = 'envoiEmailOuverture';
    $this->briefDescription = '';
    $this->detailedDescription = '';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    if(!isset($arguments['identifiant'])){
      throw new sfException("Cette tache doit être appelé avec un identifiant de société");
    }
    if(!preg_match("/^[0-9]{6}$/",$arguments['identifiant'])){
      throw new sfException("L'identifiant de la société ". $arguments['identifiant']." est mal formé");

    }

    $compte = CompteClient::getInstance()->findByIdentifiant($arguments['identifiant']."01");
    if(!$compte){
      throw new sfException("Le compte associé n'existe pas");
    }

    $compteSociete = $compte->getMasterCompte();
    if($compteSociete->_id != $compte->_id){
      echo $compte->_id." n'est pas le compte principal\n";
    }

    $resultSend = $this->sendEmail($compte);
    var_dump($resultSend);
  }

  protected function sendEmail($compte) {
      echo "envoi du mail \n";
      $mailer = $this->getMailer();

      $body = $this->getBodyMail($compte);
      $subject = "Ouverture de votre portail interprofessionnel www.ivbdpro.fr et dématérialisation des DRMS";
      $destMail = $compte->getSociete()->getEmail();
      $message = $this->getMailer()->compose(
                  array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $destMail ,$subject, $body);

      $resultSend = $mailer->send($message);
      echo "Mail envoyé à $destMail pour l'ouverture de son compte ($compte->identifiant) \n";
      return $resultSend;
  }

    protected function getBodyMail($compte){

    $identifiant = $compte->getSociete()->identifiant;
    if($compte->getStatutTeledeclarant() != CompteClient::STATUT_TELEDECLARANT_NOUVEAU){
        throw new sfException("Le compte $compte->_id a déjà été créé !");
    }

    $codeCreation = str_replace("{TEXT}","", $compte->mot_de_passe);

    $body = "Madame, Monsieur,


L’Interprofession des Vins de Bergerac et Duras ouvre son portail www.ivbdpro.fr réservé aux professionnels de la filière viticole Bergeracoise et Duraquoise. Ce portail est désormais un lien essentiel entre vous et votre interprofession. Il est entièrement sécurisé ; son accès nécessite un identifiant que nous déterminons et un mot de passe que vous devrez choisir.

Dans cet espace, vous trouverez notamment :

- votre IVBD News pour l’information,
- votre espace Contrat,
- votre espace DRMS.



Espace contrats :

Tous vos contrats de vente de vins en vrac enregistrés à l’IVBD sont désormais accessibles dans cet espace. De plus, les contrats pourront maintenant être totalement dématérialisés, c'est-à-dire que le papier n’est plus indispensable. En pratique, un courtier ou un négociant peuvent saisir un contrat vous concernant directement sur ce portail. Vous recevrez alors un mail automatique qui vous en préviendra et vous demandera de contrôler le contrat puis, si vous en êtes d’accord, de le signer. Il vous suffira pour cela de cliquer sur le lien indiqué.



Espace DRMS :

Vous pouvez désormais saisir vos DRMS directement sur le portail.
L’objectif de l’application que vous y trouverez est de dématérialiser l’ensemble du circuit DRMS et de vous permettre de remplir chaque mois vos obligations règlementaires auprès de l’interprofession comme auprès des douanes en une seule déclaration.

Pour cela, il est indispensable de vous inscrire auprès des douanes en signant la convention CIEL qui vous permettra d’avoir accès à l’application CIEL des douanes. Vous pouvez demander cette convention CIEL auprès de votre recette locale des douanes, ou bien la télécharger sur notre portail. Une fois cette convention imprimée, complétée, tamponnée et signée, vous devrez en déposer tous les feuillets physiquement auprès de votre recette locale. Les douanes activeront alors votre compte internet CIEL et nous transmettront le volet qui nous est réservé afin que nous puissions établir le lien entre notre portail et le portail CIEL des douanes.

Attention, vous devez stipuler votre interprofession de référence sur cette convention. La convention présente sur notre portail indique par défaut l’IVBD. Mais les viticulteurs qui sont ressortissant de plusieurs interprofessions (IVBD et CIVB par exemple) doivent choisir quelle est leur interprofession de référence, et donc sur quel portail ils souhaitent déclarer leurs DRMS. Ce choix est définitif.

L’ensemble de cette procédure nécessite quelques jours de traitement.
Par ailleurs, le lien général entre notre portail et le portail CIEL entre dans son ultime phase de tests. Les premières DRMS automatiquement transmises aux douanes devraient donc pouvoir être celles du mois d’octobre qui seront saisies début novembre.


Concernant la DRM de septembre, il reste indispensable de la déposer sous format papier auprès des douanes avant le 10 octobre. Vous pouvez, si vous le souhaitez, la saisir sur notre portail puis l’imprimer pour aller la déposer aux douanes.


Dans une évolution ultérieure, il sera également possible depuis notre portail de télécharger directement votre DRM issue de votre logiciel de gestion de cave. Nous vous tiendrons au courant dés que cela sera possible.

L’obligation de dématérialisation des DRMS n’interviendra au plus tard que le 1er janvier 2020. En attendant, vous avez donc le choix de continuer à utiliser le registre entrées-sorties papier ou bien de déclarer vos DRMS en ligne.



Formation :

L’application est conçue pour être simple d’utilisation. Toutefois, afin de vous faciliter sa prise en mains, nous vous proposons des séances de formations qui auront lieu les mercredi après-midi entre le 1er et le 10 de chaque mois. Vous pourrez alors venir pour saisir votre toute première DRMS dématérialisée avec notre aide. Les premières formations auront lieu les 2 et 9 novembre 2016. Le nombre de places étant strictement limité, nous vous remercions de vous inscrire aussi tôt que possible à l’adresse economie@vins-bergeracduras.fr. Nous vous préciserons les modalités lors de votre inscription.



Pour vous connecter :

En tant que ressortissant de l’IVBD, vous êtes automatiquement habilité à vous connecter sur notre portail. Vous trouverez pour cela vos identifiant et code de création de compte ci-dessous :


Votre identifiant : $identifiant

Votre code de création de compte : $codeCreation


L’identifiant est un numéro à 6 chiffres correspondant à votre numéro de client à l’IVBD précédé d’autant de 0 que nécessaire. Cet identifiant est non modifiable. Vous pourrez toujours retrouver ce numéro auprès de l’interprofession si nécessaire.

Au contraire, le code de création indiqué ci-dessus est à usage unique : il ne sera valable que pour votre toute première connexion. Lors de cette première connexion, vous devrez donc vous rendre sur votre espace personnel pour choisir un mot de passe et, si ce n’est déjà fait, indiquer votre adresse mail. Vous recevrez alors un courriel vous demandant de valider la création de votre compte en cliquant sur le lien proposé.
L’IVBD ne connaîtra jamais vos mots de passe, mais il existe évidemment une procédure de secours en cas d’oubli.



Nous vous souhaitons une bonne navigation sur le portail www.ivbdpro.fr !";

    return $body;
    }

}
