<!-- #header -->
<header id="header">
    <div class="contenu">
        <h1 id="logo">
            <a title="Vins de Loire - Retour à l'accueil" href="<?php echo url_for('homepage') ?>">
                <img src="https://teledeclaration.vinsvaldeloire.pro/images/visuels/logo_vinsdeloire_new2.png" alt="" />
            </a>
        </h1>
        <div class="conteneur_nav">
            <span class="baseline">Espace des professionnels du Vignoble du Val de Loire</span>
            <?php include_component('global', 'nav', array('droits' => isset($droits) ? $droits : null, 'isAuthenticated' => isset($isAuthenticated), 'etablissement' => isset($etablissement) ? $etablissement : null, 'societe' => isset($societe) ? $societe : null, 'actif' => isset($actif) ? $actif : null)); ?>
        </div>

        <?php if ($sf_user->hasCredential('transactions') || in_array('transactions', isset($droits) ? $droits->getRawValue() : array())): ?>
            <button class="btn_menu" type="button">Menu</button>
        <?php endif; ?>

        <h1 class="logo_civdl">
            <a title="CIVDL - Retour à l'accueil" href="<?php echo url_for('homepage') ?>">
                <img src="https://teledeclaration.vinsvaldeloire.pro/data/logo_vrac_pdf.jpg" alt="" />
            </a>
        </h1>
        <div id="actions_utilsateur">
            <?php if ($sf_user->hasCredential('admin') || in_array('admin', isset($droits) ? $droits->getRawValue() : array())) : ?>
                <a class="admin" href="<?php echo url_for('produits') ?>">Admin</a>
            <?php endif; ?>
            <?php if ($sf_user->hasCredential(Roles::TELEDECLARATION) || in_array(Roles::TELEDECLARATION, isset($droits) ? $droits->getRawValue() : array())): ?>
                <a href="<?php echo url_for("compte_teledeclarant_modification") ?>">Mon compte</a>
            <?php endif; ?>
            <?php if ($sf_user->isAuthenticated() || isset($isAuthenticated)): ?>
                <?php if ($sf_user->isUsurpationCompte() || isset($isUsurpation)): ?>
                    <a class="deconnexion" href="<?php echo url_for('vrac_dedebrayage') ?>">Quitter</a>
                <?php else: ?>
                    <a class="deconnexion" href="<?php echo url_for('auth_logout') ?>">Déconnexion</a>
                <?php endif; ?>
            <?php else: ?>
                <a class="deconnexion" href="<?php echo url_for('homepage') ?>">Connexion</a>
            <?php endif; ?>

        </div>
    </div>
</header>
<!-- fin #header -->
