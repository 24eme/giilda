<!-- #header -->
<header id="header">
    <div class="contenu">
        <h1 id="logo">
            <a title="Vins de Loire - Retour à l'accueil" href="<?php echo url_for('homepage') ?>">
                <img src="/images/visuels/logo_vinsdeloire_new2.png" alt="" />
            </a>
        </h1>

        <div class="conteneur_nav">
            <span class="baseline">Espace des professionnels du Vignoble du Val de Loire</span>
            <?php include_component('global', 'nav'); ?>
        </div>

        <?php if ($sf_user->hasCredential('transactions')): ?>
            <button class="btn_menu" type="button">Menu</button>
        <?php endif; ?>

        <h1 class="logo_civdl">
            <a title="CIVDL - Retour à l'accueil" href="<?php echo url_for('homepage') ?>">
                <img src="/data/logo_vrac_pdf.jpg" alt="" />
            </a>
        </h1>
        <div id="actions_utilsateur">
            <?php if ($sf_user->hasCredential('admin')) : ?>
                <a class="admin" href="<?php echo url_for('produits') ?>">Admin</a>
            <?php endif; ?>
            <?php if ($sf_user->hasCredential(Roles::TELEDECLARATION)): ?>

                <?php //
                //LIEN OBSERVATOIRE
                //
                //if ($sf_user->hasCredential(Roles::OBSERVATOIRE)) : ?>
                <!--<a href="<?php // echo sfConfig::get('app_observatoire_url'); ?>">Observatoire</a>-->
                <?php // endif; ?>


                <a href="<?php echo url_for("compte_teledeclarant_modification") ?>">Mon compte</a>
            <?php endif; ?>
            <?php if ($sf_user->isAuthenticated()): ?>
                <?php if ($sf_user->isUsurpationCompte()): ?>
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
