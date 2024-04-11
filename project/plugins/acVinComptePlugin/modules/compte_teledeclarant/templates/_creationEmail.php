<?php echo use_helper('Orthographe'); ?>
Madame, Monsieur,

Votre compte a bien été créé.

Nous vous rappelons que votre identifiant restera toujours le : <?php echo $compte->getLogin(); ?>


Cordialement,

<?php echo elision('Le', sfConfig::get('app_teledeclaration_interpro')); ?>
