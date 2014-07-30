<div class="ligne_btn txt_droite">
    <?php if($etablissementPrincipal->isCourtier() || $etablissementPrincipal->isNegociant()): ?>      
    <a class="btn_orange btn_majeur" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissementPrincipal->identifiant)); ?>">
        Nouveau contrat
    </a>
    <?php endif; ?>

    <a class="btn_majeur" href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'all')); ?>">
        Voir tout l'historique
    </a>

    <a href="<?php echo url_for('annuaire', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn_majeur btn_excel">Exporter les contrats en CSV</a>
</div>
