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
                <a class="deconnexion btn_majeur btn_orange" href="<?php echo url_for('vrac_dedebrayage') ?>">Revenir sur VINSI</a>
            </div>
            <?php endif; ?>
                
            <div class="ligne_btn txt_centre">
                <?php if (isset($retour) && $retour): ?>
                    <a href="<?php echo url_for('vrac_societe', array('identifiant' => str_replace('COMPTE-', '', $societe->compte_societe))); ?>" class="btn_majeur btn_acces">Mes Déclarations</a>
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


<?php slot('colReglementation'); ?>
<?php include_partial('compte_teledeclarant/colReglementation'); ?>
<?php end_slot(); ?>


<?php slot('colLegende'); ?>
<div id="legende" class="bloc_col">
    <h2>Légende</h2>

    <div class="contenu">
        <h3>Types de marchés :</h3>

        <div class="contenu legende">    
            <div>
                <span class="type_raisins">type_raisins</span>
                <span class="legende_type_texte">Raisins</span>
            </div>
            <div>
                <span class="type_mouts">type_mouts</span><span class="legende_type_texte">Mouts</span>
            </div>
            <div>
                <span class="type_vin_vrac">type_vin_vrac</span><span class="legende_type_texte">Vrac</span>
            </div>
            <div>
                <span class="type_vin_bouteille">type_vin_bouteille</span><span class="legende_type_texte">Conditionné</span>
            </div>
        </div>


        <h3>Statuts de contrats :</h3>

        <ul class="status_contrats">
            <li><img src="/images/pictos/pi_ok.png" alt="" /> Signé par moi</li>
            <li><img src="/images/pictos/pi_attente.png" alt="" /> En attente de signature</li>
            <li><img src="/images/pictos/pi_ok_gris.png" alt="" /> Signé par d'autres soussignés</li>
        </ul>
    </div>
</div>
<?php end_slot(); ?>

<?php
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

        <a href="#" class="lien_telechargement">Télécharger la notice</a>


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