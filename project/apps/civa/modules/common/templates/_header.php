<?php if(sfConfig::get('app_url_header')): ?>
    <?php $isAdmin = ($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN) || $sf_user->isUsurpationCompte()); ?>
    <?php $compteOrigine = $sf_user->getCompteOrigin()->identifiant; ?>
    <?php $compte = null; ?>
    <?php $etablissement = null; ?>
    <?php if($sf_request->getAttribute('sf_route') && $sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceEtablissementRoute && $sf_request->getAttribute('sf_route')->getEtablissement()):
        $compte = $sf_request->getAttribute('sf_route')->getEtablissement()->getSociete()->getMasterCompte()->identifiant;
        $etablissement = $sf_request->getAttribute('sf_route')->getEtablissement()->_id;
    endif; ?>
    <?php if(!$compte && $sf_request->getAttribute('sf_route') && $sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceSocieteRoute): $compte = $sf_request->getAttribute('sf_route')->getSociete()->getMasterCompte()->identifiant; endif; ?>
    <?php if(!$compte && $sf_user->getCompte()): $compte = $sf_user->getCompte()->identifiant; endif; ?>
    <?php $lienHeader = sfConfig::get('app_url_header')."?compte=".$compte."&isAdmin=".$isAdmin."&compteOrigine=".$compteOrigine.'&isAuthenticated='.$sf_user->isAuthenticated(); ?>
    <?php echo file_get_contents($lienHeader); ?>
    <?php if(sfConfig::get('sf_debug')): ?>
        <a href="<?php echo $lienHeader ?>">[voir l'url du header]</a></pre>
    <?php endif; ?>

    <div id="main">
        <div style="position:relative;" id="nav">
            <?php if($compte): ?>
                <?php echo file_get_contents(sfConfig::get('app_url_nav')."?compte=".$compte."&isAdmin=".$sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)."&active=".$sf_request->getParameter('module').'&etablissement='.$etablissement); ?>
                <?php if(sfConfig::get('sf_debug')): ?>
                    <a style="position: absolute; right: 10px; top: -15px; font-size: 10px; color: #ff0000;" href="<?php echo sfConfig::get('app_url_nav')."?compte=".$compte."&isAdmin=".($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN))."&active=".$sf_request->getParameter('module') ?>&etablissement=<?php echo $etablissement ?>">[voir l'url de la nav]</a></pre>
                <?php endif; ?>
                <?php if ($isAdmin && preg_match('/(drm|facture)/', $sf_request->getParameter('module'))) : ?>
                    <?php if ($sf_user->isUsurpationCompte()): ?>
                         <a style="font-size: 20px; position: absolute; right: 15px; top: 10px;" tabindex="-1" href="<?php echo url_for('auth_deconnexion_usurpation') ?>"><span class="glyphicon glyphicon-cloud-download"></span></a>
                    <?php elseif($sf_request->getAttribute('sf_route') && $sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceEtablissementRoute && $sf_request->getAttribute('sf_route')->getEtablissement()): ?>
                        <a style="font-size: 20px; position: absolute; right: 15px; top: 10px;" tabindex="-1" href="<?php echo url_for('drm_debrayage', array('identifiant' => $sf_request->getAttribute('sf_route')->getEtablissement()->identifiant)) ?>"><span class="glyphicon glyphicon-cloud-upload"></span></a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                    <?php echo file_get_contents(sfConfig::get('app_url_nav')."?isAdmin=".$sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)."&active=".$sf_request->getParameter('module')); ?>
                    <?php if(sfConfig::get('sf_debug')): ?>
                        <a style="position: absolute; right: 10px; top: 10px; font-size: 10px; color: #ff0000;" href="<?php echo sfConfig::get('app_url_nav')."?isAdmin=".$sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)."&active=".$sf_request->getParameter('module') ?>">[voir l'url de la nav]</a></pre>
                    <?php endif; ?>
            <?php endif; ?>
        </div>
<?php else: ?>
<div id="main">
    <nav class="navbar navbar-default navbar-static-top">
          <div class="container">
            <?php include_component('common', 'nav'); ?>
        </div>
    </nav>
<?php endif; ?>
