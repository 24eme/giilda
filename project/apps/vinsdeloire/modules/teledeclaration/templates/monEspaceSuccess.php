<!-- #principal -->
<section id="principal">
        <?php if($compte->hasDroit(CompteClient::DROITS_COMPTE_TELEDECLARATION_VRAC)): ?>
            <?php include_partial('teledeclaration/contrats_etablissements', array('societe' => $societe,'contratsEtablissements' => $contratsEtablissements)); ?>
        <?php endif; ?>    
        <?php if($compte->hasDroit(CompteClient::DROITS_COMPTE_OBSERVATOIRE_ECO)): ?>	
    <a href="#">Rendez vous sur l'observatoire eco</a>
        <?php endif; ?>
</section>
