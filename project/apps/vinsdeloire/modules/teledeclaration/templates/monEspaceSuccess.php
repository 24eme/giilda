<!-- #principal -->
<section id="principal">
    <?php if($compte->hasDroit(CompteClient::DROITS_COMPTE_TELEDECLARATION)): ?>
        <?php if($compte->hasDroit(CompteClient::DROITS_COMPTE_TELEDECLARATION_VRAC)): ?>
            <?php include_partial('teledeclaration/contrats_etablissements', array('societe' => $societe,'contratsEtablissements' => $contratsEtablissements, 'etablissements' => $etablissements,'compte' => $compte)); ?>
        <?php endif; ?>    
        <?php if($compte->hasDroit(CompteClient::DROITS_COMPTE_OBSERVATOIRE_ECO)): ?>	
            <a href="#">Observatoire économique</a>
        <?php endif; ?>
    <?php endif; ?>      
</section>
