<?php $route = ($sf_request->getAttribute('sf_route')) ? $sf_request->getAttribute('sf_route')->getRawValue() : null; ?>
<div class="row">
<div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Login&nbsp;:</div>
<div style="margin-bottom: 5px;" class="col-xs-3">
<?php echo $compte->getLogin(); ?>
<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN) && $compte && !$sf_user->isUsurpationCompte() && $compte->getLogin()) : ?>
            <a style="text-decoration: none; color:gray;" href="<?php echo url_for('auth_usurpation', array('identifiant' => $compte->identifiant)) ?>" title="Connexion mode déclarant"><span class="glyphicon glyphicon-cloud-upload"></span></a>
<?php endif; ?>
</div>
<?php if (preg_match('/{TEXT}(.*)/', $compte->mot_de_passe, $m)) : ?>
<div style="margin-bottom: 5px;" class="col-xs-3 text-muted">Code de création&nbsp;:</div>
<div style="margin-bottom: 5px;" class="col-xs-3"><?php echo $m[1]; ?></div>
<?php elseif (preg_match('/{OUBLIE}(.*)/', $compte->mot_de_passe, $m)) : ?>
<div style="margin-bottom: 5px;" class="col-xs-6"><span class="text-muted">Mot de passe oublié&nbsp;:</span><br/>
<?php echo "https://".$_SERVER['HTTP_HOST'].url_for("compte_teledeclarant_mot_de_passe_oublie_login", array("login" => $compte->getSociete()->identifiant, "mdp" => $m[1])); ?></div>
<?php else: ?>
<div style="margin-bottom: 5px;" class="col-xs-6 text-muted">Mot de passe déjà créé</div>
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
