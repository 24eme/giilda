<?php
    slot('colCompte');
    ?>
    <div class="bloc_col" id="contrat_compte">
        <h2><?php echo $etablissementPrincipal->famille; ?></h2>

        <div class="contenu">
            <p>
                Vous êtes connecté en tant que
            </p>
            <h3><?php echo $societe->raison_sociale; ?></h3>
            <ul class="compte"> 
                &gt; &nbsp;<a href="<?php echo url_for('vrac_societe', array('identifiant' => str_replace('COMPTE-', '', $societe->compte_societe))); ?>" class="lien_declaration">Mes déclarations</a>
                &gt; &nbsp;<a href="<?php echo url_for('vrac_societe', array('identifiant' => str_replace('COMPTE-', '', $societe->compte_societe))).'/compte'; ?>" class="lien_compte">Mon compte</a>
            </ul>

            <div class="ligne_btn txt_centre">
                <a href="#" class="btn_majeur btn_annuaire">Annuaire</a>
            </div>
        </div>
    </div>
    <?php
    end_slot();

    slot('colAide');
    $contacts = sfConfig::get('app_teledeclaration_contact_contrat');
    $region = $etablissementPrincipal->region;
    ?>
    <div class="bloc_col" id="contrat_aide">
        <h2>Aide</h2>

        <div class="contenu">
            <p>
                En cas de besoin n'hésitez pas à consulter la notice d'aide complète au format pdf.
            </p>

            <a href="#" class="lien_notice">Télécharger la notice</a>


            <h3>Contact Hotline</h3>
            <ul class="contact"> 
                <li class="telephone"><?php echo $contacts[$region]['telephone']; ?></li>
            </ul>  
            <h3>Votre contact - mise en marche</h3>

            <ul class="contact"> 
                <li class="nom"><?php echo $contacts[$region]['nom']; ?></li>
                <li class="email"><a href="mailto:<?php echo $contacts[$region]['email']; ?>"><?php echo $contacts[$region]['email']; ?></a></li>
                <li class="telephone"><?php echo $contacts[$region]['telephone']; ?></li>
            </ul>
        </div>
    </div>
    <?php
    end_slot();
?>