<nav id="navigation">
    <ul>
        <?php if ($sf_user->hasCredential('transactions')) : ?>

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
               if($sf_route instanceof InterfaceEtablissementRoute) {
                       $etablissement = $sf_route->getEtablissement();
               }
           ?>

           <?php if(sfConfig::get('app_odgloire', false)): ?>
           <li <?php if(isset($droits)): ?>class="actif"<?php endif; ?> ><a href="/odg/<?php echo (isset($etablissement) && !isset($droits)) ? "declarations/".$etablissement->identifiant : null ?>">DRev</a></li>
            <?php else: ?>
           <?php
               include_component('global', 'navItem', array(
                'libelle' => 'Import VR',
                'prefix' => 'revendication',
                'route' => 'revendication',
                'route_etablissement' => 'revendication_etablissement',
                'etablissement' => null,
                'target' => '_self'
               ))
            ?>
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

        <?php if ($sf_user->hasCredential('contacts')) : ?>
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
        <?php if ($sf_user->hasCredential('teledeclaration_vrac')) : ?>
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

        <?php if ($sf_user->hasCredential('teledeclaration_drm')) : ?>
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

        <?php if ($sf_user->hasCredential('teledeclaration_facture')) : ?>
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

        <!-- Actions utilisateur pour tablette et mobile -->

        <?php if ($sf_user->hasCredential('admin')) : ?>
            <li class="hidden_desk visible_tab"><a class="admin" href="<?php echo url_for('produits') ?>">Admin</a></li>
        <?php endif; ?>

        <?php if ($sf_user->hasCredential(Roles::TELEDECLARATION)): ?>
            <li class="hidden_desk visible_tab">
                <a href="<?php echo url_for("compte_teledeclarant_modification") ?>">Mon compte</a>
            </li>
        <?php endif; ?>

        <?php if ($sf_user->isAuthenticated()): ?>
            <?php if ($sf_user->isUsurpationCompte()): ?>
                <li class="hidden_desk visible_tab"><a class="deconnexion" href="<?php echo url_for('vrac_dedebrayage') ?>">Quitter</a></li>
            <?php else: ?>
                <li class="hidden_desk visible_tab"><a class="deconnexion" href="<?php echo url_for('auth_logout') ?>">Déconnexion</a></li>
            <?php endif; ?>
        <?php else: ?>
            <li class="hidden_desk visible_tab"><a class="deconnexion" href="<?php echo url_for('homepage') ?>">Connexion</a></li>
            <?php endif; ?>
    </ul>
</nav>
