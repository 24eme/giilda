<nav id="navigation">
    <ul>
        <?php if ($sf_user->hasCredential('transactions') || in_array('transactions', isset($droits) ? $droits->getRawValue() : array())) : ?>

            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'DRM',
                'prefix' => 'drm',
                'route' => 'drm',
                'route_etablissement' => 'drm_etablissement',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>

            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'Contrats',
                'prefix' => 'vrac',
                'route' => 'vrac',
                'route_etablissement' => 'vrac_recherche',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>

            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'Facture',
                'prefix' => 'facture',
                'route' => 'facture',
                'route_etablissement' => 'facture_etablissement',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>

           <?php
               if(isset($sf_route) && $sf_route instanceof InterfaceEtablissementRoute) {
                       $etablissement = $sf_route->getEtablissement();
               }
           ?>


           <?php
               // include_component('global', 'navItem', array(
               //  'libelle' => 'Import VR',
               //  'prefix' => 'revendication',
               //  'route' => 'revendication',
               //  'route_etablissement' => 'revendication_etablissement',
               //  'etablissement' => null,
               //  'target' => '_self'
               // ))
            ?>

            <?php if (sfConfig::get('app_odgloire', false)) : ?>
              <li <?php if(isset($droits)): ?>class="actif"<?php endif; ?> ><a href="/odg/<?php echo (isset($etablissement) && !isset($droits)) ? "declarations/".$etablissement->identifiant : null ?>">DRev</a></li>
            <?php endif; ?>

            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'SV12',
                'prefix' => 'sv12',
                'route' => 'sv12',
                'route_etablissement' => 'sv12_etablissement',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>

            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'DS',
                'prefix' => 'ds',
                'route' => 'ds',
                'route_etablissement' => 'ds_etablissement',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>

            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'Stocks',
                'prefix' => 'stocks',
                'route' => 'stocks',
                'route_etablissement' => 'stocks_etablissement',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>

            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'Alertes',
                'prefix' => 'alerte',
                'route' => 'alerte',
                'route_etablissement' => 'alerte_etablissement',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>

            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'Relance',
                'prefix' => 'relance',
                'route' => 'relance',
                'route_etablissement' => 'relance_etablissement',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>
        <?php endif; ?>

        <?php if ($sf_user->hasCredential('contacts') || in_array('contacts', isset($droits) ? $droits->getRawValue() : array())) : ?>
            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'Contacts',
                'prefix' => 'etablissement',
                'route' => 'societe',
                'route_etablissement' => 'etablissement_visualisation',
                'etablissement' => $etablissement,
                'target' => '_self'
            ))
            ?>

        <?php endif; ?>
        <?php if ($sf_user->hasCredential('teledeclaration_vrac') || in_array('teledeclaration_vrac', isset($droits) ? $droits->getRawValue() : array())) : ?>
            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'Contrats',
                'prefix' => 'vrac',
                'route' => 'vrac_societe',
                'route_etablissement' => 'vrac_societe',
                'societe' => $societe,
                'target' => '_self'
            ))
            ?>

        <?php endif; ?>

        <?php if ($sf_user->hasCredential('teledeclaration_drm') || in_array('teledeclaration_drm', isset($droits) ? $droits->getRawValue() : array())) : ?>
            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'DRM',
                'prefix' => 'drm',
                'route' => 'drm_societe',
                'route_etablissement' => 'drm_societe',
                'societe' => $societe,
                'target' => '_self'
            ))
            ?>

        <?php endif; ?>

        <?php if ($sf_user->hasCredential('teledeclaration_facture') || in_array('teledeclaration_facture', isset($droits) ? $droits->getRawValue() : array())) : ?>
            <?php
            include_component('global', 'navItem', array(
                'libelle' => 'Factures',
                'prefix' => 'facture',
                'route' => 'facture_teledeclarant',
                'route_etablissement' => 'facture_teledeclarant',
                'societe' => $societe,
                'target' => '_self'
            ))
            ?>

        <?php endif; ?>

        <?php if (sfConfig::get('app_odgloire', false) && ($sf_user->hasCredential('teledeclaration_drev') || in_array('teledeclaration_drev', isset($droits) ? $droits->getRawValue() : array()))): ?>
          <?php
          $url = null;
          if($sf_user->getCompte()){
            $url="/odg/declarations/".$sf_user->getCompte()->getIdentifiant()."?usurpation=".intval($sf_user->isUsurpationCompte())."&login=".$sf_user->getCompte()->getSociete()->getMasterCompte()->identifiant;
          }else{
            $url="/odg/declarations/".$etablissement->identifiant."?usurpation=".intval($sf_user->isUsurpationCompte())."&login=".$etablissement->getSociete()->getMasterCompte()->identifiant;
          }
          ?>
          <li <?php if(isset($droits)): ?>class="actif"<?php endif; ?> ><a href="<?php echo $url; ?>">DRev</a></li>
        <?php endif; ?>

        <?php if (sfConfig::get('app_odgloire', false) && ($sf_user->hasCredential('teledeclaration_drev_admin') || in_array('teledeclaration_drev_admin', isset($droits) ? $droits->getRawValue() : array()))) : ?>
          <li <?php if(isset($droits)): ?>class="actif"<?php endif; ?> ><a href="/odg/<?php echo (isset($etablissement) && !isset($droits)) ? "declarations/".$etablissement->identifiant : null ?>">DRev</a></li>
        <?php endif; ?>

        <!-- Actions utilisateur pour tablette et mobile -->

        <?php if ($sf_user->hasCredential('admin') || in_array('admin', isset($droits) ? $droits->getRawValue() : array())) : ?>
            <li class="hidden_desk visible_tab"><a class="admin" href="<?php echo url_for('produits') ?>">Admin</a></li>
        <?php endif; ?>

        <?php if ($sf_user->hasCredential(Roles::TELEDECLARATION) || in_array(Roles::TELEDECLARATION, isset($droits) ? $droits->getRawValue() : array())): ?>
            <li class="hidden_desk visible_tab">
                <a href="<?php echo url_for("compte_teledeclarant_modification") ?>">Mon compte</a>
            </li>
        <?php endif; ?>

        <?php if ($sf_user->isAuthenticated() || isset($isAuthenticated)): ?>
            <?php if ($sf_user->isUsurpationCompte() || isset($isUsurpation)): ?>
                <li class="hidden_desk visible_tab"><a class="deconnexion" href="<?php echo url_for('vrac_dedebrayage') ?>">Quitter</a></li>
            <?php else: ?>
                <li class="hidden_desk visible_tab"><a class="deconnexion" href="<?php echo url_for('auth_logout') ?>">Déconnexion</a></li>
            <?php endif; ?>
        <?php else: ?>
            <li class="hidden_desk visible_tab"><a class="deconnexion" href="<?php echo url_for('homepage') ?>">Connexion</a></li>
            <?php endif; ?>
    </ul>
</nav>
