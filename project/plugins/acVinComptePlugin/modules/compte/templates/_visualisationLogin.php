<?php $route = ($sf_request->getAttribute('sf_route')) ? $sf_request->getAttribute('sf_route')->getRawValue() : null; ?>
<div class="row">
<div style="margin-bottom: 5px;" class="col-xs-1  text-muted">Login&nbsp;:</div>
<div style="margin-bottom: 5px;" class="col-xs-2">
<?php echo $compte->getLogin(); ?>
<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN) && $compte && !$sf_user->isUsurpationCompte() && $compte->getLogin()) : ?>
            <a style="text-decoration: none; color:gray;" href="<?php echo url_for('auth_usurpation', array('identifiant' => $compte->identifiant)) ?>" title="Connexion mode déclarant"><span class="glyphicon glyphicon-cloud-upload"></span></a>
<?php endif; ?>
</div>
<?php $compte_login = CompteClient::getInstance()->findByLogin($compte->getLogin()); ?>
<?php if (preg_match('/{TEXT}(.*)/', $compte_login->mot_de_passe, $m)) : ?>
<div class="col-xs-2"> &nbsp; </div>
<div style="margin-bottom: 5px;" class="col-xs-3 text-muted">Code de création&nbsp;:</div>
<div style="margin-bottom: 5px;" class="col-xs-4"><?php echo $m[1]; ?></div>
<?php elseif (preg_match('/{OUBLIE}(.*)/',  $compte_login->mot_de_passe, $m)) : ?>
    <div onclick="navigator.clipboard.writeText(document.getElementById('input-share-link').value); const icon_message = this.getElementsByTagName('i')[0]; icon_message.classList.remove('glyphicon-duplicate');icon_message.classList.add('glyphicon-ok');setTimeout(function() { icon_message.classList.remove('glyphicon-ok');icon_message.classList.add('glyphicon-duplicate');}, 750);return false;" style="margin-bottom: 5px;" class="col-xs-8">
      <span class="text-muted">Mot de passe oublié&nbsp;:</span>
      <input id="input-share-link" type="text" size=35 value="<?php echo "https://".$_SERVER['HTTP_HOST'].url_for("compte_teledeclarant_mot_de_passe_oublie_login", array("login" =>  $compte_login->identifiant, "mdp" => $m[1])); ?>"/>
      <a><i class="glyphicon glyphicon-duplicate"></i></a>
    </div>
<?php else: ?>
<div class="col-xs-2"> &nbsp; </div>
<div style="margin-bottom: 5px;" class="col-xs-7 text-muted">Mot de passe déjà créé</div>
<?php endif; ?>
</div>
<?php if ($compte->getTeledeclarationEmail()) : ?>
    <div class="row">
        <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
            Email&nbsp;Télédecl.&nbsp;:
        </div>
            <div style="margin-bottom: 5px" class="col-xs-9">
                    <small><a href="mailto:<?php echo $compte->getTeledeclarationEmail(); ?>"><?php echo $compte->getTeledeclarationEmail(); ?></a></small><br/>
            </div>
    </div>
<?php endif; ?>
<div class="row">
<?php if ($compte->hasAlternativeLogins()) : ?>
<div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Logins Interpro&nbsp;:</div>
<div style="margin-bottom: 5px;" class="col-xs-9"><?php echo implode(', ', $compte->alternative_logins->getRawValue()->toArray()); ?></div>
</div>
<div class="row">
<?php endif; ?>
<?php if($compte->exist('droits') && count($compte->getRawValue()->droits->toArray(true, false)) > 0): ?>
<div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Droits&nbsp;:</div>
<div style="margin-bottom: 5px;" class="col-xs-9">
        <?php echo implode(", ", $compte->getRawValue()->droits->toArray(true, false)); ?>
</div>
</div>
<div class="row">
<?php endif; ?>
<?php if($compte->exist('region') && $compte->region): ?>
<div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Region&nbsp;:</div>
<div style="margin-bottom: 5px;" class="col-xs-9"><?php echo $compte->region; ?></div>
<?php endif; ?>
</div>
