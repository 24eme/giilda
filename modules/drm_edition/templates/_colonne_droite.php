<?php
slot('colCompte');
?>
<div class="bloc_col" id="contrat_compte">
    <h2><?php echo $etablissementPrincipal->famille; ?> (<?php echo $societe->identifiant; ?>) </h2>

    <div class="contenu">
        <div class="text-center" style="text-align: center;">
            <p><strong><?php echo $societe->raison_sociale; ?></strong></p>

            <p> (<?php echo $societe->siege->commune; ?>) </p>

            <?php if ($sf_user->isUsurpationCompte()): ?>
                <div class="ligne_btn txt_centre">
                    <a class="deconnexion btn_majeur btn_orange" href="<?php echo url_for('drm_dedebrayage') ?>">Revenir sur VINSI</a>
                </div>
            <?php endif; ?>

            <div class="ligne_btn txt_centre">
                <?php if (isset($retour) && $retour): ?>
                    <a href="<?php echo url_for('vrac_societe', array('identifiant' => str_replace('COMPTE-', '', $societe->compte_societe))); ?>" class="btn_majeur btn_acces">Mes Contrats</a>
                <?php endif; ?>
            </div>
            <div class="ligne_btn txt_centre">
                <?php if ($etablissementPrincipal->isCourtier() || $etablissementPrincipal->isNegociant()): ?>
                    <a href="<?php echo url_for('annuaire', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn_majeur btn_annuaire">Annuaire</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php end_slot(); ?>
