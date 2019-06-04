<?php
if (isset($retour_espace) && $retour_espace):
    slot('colButtons');
    ?>
    <div class="bloc_col" >
        <h2>Actions</h2>
        <div class="contenu">
            <div class="ligne_btn txt_centre">
                <span style="font-weight: bold;">Mon Espace Contrat</span>
            </div>
            <div class="ligne_btn txt_centre">
                <a href="<?php echo url_for('compte_teledeclarant_mon_espace', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn_majeur btn_acces">Retour à mon espace</a>

            </div>
        </div>
    </div>
    <?php end_slot(); ?>
<?php
endif;
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


        <h3>Étapes de validation :</h3>

        <ul class="status_contrats">
            <li><img src="/images/pictos/pi_ok.png" alt="Signé par moi" /> Signé par moi</li>
            <li><img src="/images/pictos/pi_attente_signature.png" alt="À signer" /> À signer</li>
            <li><img src="/images/pictos/pi_ok_gris.png" alt="Signé par d'autre" /> Signé par d'autres soussignés</li>
            <li><img src="/images/pictos/pi_attente_soussigne.png" alt="À signer par d'autre" /> À signer par d'autres soussignés</li>
        </ul>

        <div class="legende">
            <h3>État des contrats validés :</h3>
            <br/>
            <div>
                <span class="statut statut_solde"></span>
                <span class="legende_statut_texte">Soldé</span>
            </div>
            <div>
                <span class="statut statut_non-solde"></span>
                <span class="legende_statut_texte">Non soldé</span>
            </div>
        </div>
    </div>
</div>
<?php end_slot(); ?>

<?php slot('colReglementation'); ?>
<?php include_partial('vrac/colReglementation'); ?>
<?php end_slot(); ?>


<?php
slot('colAide');
$contact = EtablissementClient::getInstance()->buildInfosContact($etablissementPrincipal);
?>
<div class="bloc_col" id="contrat_aide">
    <h2>Aide</h2>

    <div class="contenu">
        <p>
            N'hésitez pas à consulter la notice en format pdf.
        </p>
        <a href="#" id="liens_notices" class="lien_telechargement">Télécharger la notice</a>
        <br/>
        <br/>
        <p class="lien_lecteur_pdf">
            Ce document est au format PDF. Pour la visualiser, veuillez utiliser un <a target="_blank" href="<?php echo sfConfig::get('app_pdf_reader_link') ?>">lecteur PDF</a>.
        </p>

        <h3>Votre contact - mise en marche</h3>

        <ul class="contact">
            <li class="nom"><?php echo $contact->nom; ?></li>
            <li class="email"><a href="mailto:<?php echo $contact->email; ?>"><?php echo $contact->email; ?></a></li>
            <li class="telephone"><?php echo $contact->telephone; ?></li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        initNoticePopup();
    });
</script>
<?php
end_slot();
?>
