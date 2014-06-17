<!-- #principal -->
<section id="principal">
        <?php if($compte->hasDroit(CompteClient::DROITS_COMPTE_TELEDECLARATION_VRAC)): ?>
            <?php include_partial('marche_volumePrix', array('societe' => $societe)); ?>
        <?php endif; ?>    
        <?php if($compte->hasDroit(CompteClient::DROITS_COMPTE_OBSERVATOIRE_ECO)): ?>	
    <a href="#">Rendez vous sur l'observatoire eco</a>
        <?php endif; ?>
</section>
