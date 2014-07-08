<div id="etablissement_<?php echo $etablissement->identifiant; ?>" class="">
    <h3><?php echo $etablissement->nom; ?></h3>
    <ul id="liste_statuts_nb" class="">    
        
    </ul>
    <div id="num_etb">
        N° <?php echo $etablissement->identifiant; ?>
    </div>
    <div id="cp_etb">
        Code postal: <?php echo $etablissement->siege->code_postal; ?>
    </div>
    <div id="commune_etb">
        Commune: <?php echo $etablissement->siege->commune; ?>
    </div>
</div>
<div id="etablissements_vracs_button">    
        <?php if($compte->hasDroit(CompteClient::DROITS_COMPTE_TELEDECLARATION_VRAC_CREATION)): ?>
    <a href="<?php echo url_for('vrac_creation',array('identifiant' => $etablissement->identifiant)) ?>">Nouveau</a>
    <?php endif; ?>
</div>