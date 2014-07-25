<div id="ligne_btn" class="txt_droite">
    <a class="btn_majeur" href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'all')); ?>">
        Voir tout l'historique
    </a>
    <?php if($etablissementPrincipal->isCourtier() || $etablissementPrincipal->isNegociant()): ?>
    <a class="btn_vert btn_majeur" href="<?php echo url_for('annuaire', array('identifiant' => $etablissementPrincipal->identifiant)); ?>">
        Annuaire
    </a>        
    <a class="btn_orange btn_majeur" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissementPrincipal->identifiant)); ?>">
        Nouveau contrat
    </a>
    <?php endif; ?>
</div>
