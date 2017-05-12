
<?php $isAdmin = $sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN); ?>
<?php $compteOrigine = $sf_user->getCompte()->login; ?>
<?php $compte = null; ?>
<?php if($sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceEtablissementRoute): $compte = $sf_request->getAttribute('sf_route')->getEtablissement()->getMasterCompte()->identifiant;  endif; ?>
<?php if(!$compte && $sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceSocieteRoute): $compte = $sf_request->getAttribute('sf_route')->getSociete()->getMasterCompte()->identifiant; endif; ?>

<?php echo file_get_contents(sfConfig::get('app_url_header')."?compte=".$compte."&isAdmin=".$isAdmin."&compteOrigine=".$compteOrigine); ?>
