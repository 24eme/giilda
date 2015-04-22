<!-- COLONNE ACTION -->
<?php
slot('colButtons');
?>
<div class="bloc_col" >
    <h2>Actions</h2>
    <div class="contenu">
        <div class="ligne_btn txt_centre">
            <a href="<?php echo url_for('drm_societe', array('identifiant' => str_replace('COMPTE-', '', $societe->compte_societe))); ?>" class="btn_majeur btn_acces">Retour à mes DRM</a>
        </div>
    </div>
</div>
<?php end_slot(); ?>

<!-- COLONNE COMPTE -->
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
        </div>
    </div>
</div>
<?php end_slot(); ?>


<!-- COLONNE D'AIDE -->
<?php
slot('colAide');
$contact = EtablissementClient::getInstance()->buildInfosContact($etablissementPrincipal);
?>
<div class="bloc_col" id="contrat_aide">
    <h2>Aide</h2>

    <div class="contenu">
        <p>
            En cas de besoin n'hésitez pas à consulter la notice en format pdf.
        </p>
        <a href="#" id="liens_notices" class="lien_telechargement">Télécharger la notice</a>
        <br/>
        <br/>
        <p class="lien_lecteur_pdf">
            Ce document est au format PDF. Pour la visualiser, veuillez utiliser un <a target="_blank" href="<?php echo sfConfig::get('app_pdf_reader_link') ?>">lecteur PDF</a>.
        </p>

        <h3>Contact hotline</h3>
        <p><?php echo $contact->telephone; ?></p>

        <h3>Votre contact - mise en marche</h3>

        <ul class="contact"> 
            <li class="nom"><?php echo $contact->nom; ?></li>
            <li class="email"><a href="mailto:<?php echo $contact->email; ?>"><?php echo $contact->email; ?></a></li>
            <li class="telephone"><?php echo $contact->telephone; ?></li>
        </ul>
    </div>
</div>   
<script type="text/javascript">
    $(document).ready(function()
    {
        initNoticePopup();
    });
</script>    
<?php
end_slot();
?>