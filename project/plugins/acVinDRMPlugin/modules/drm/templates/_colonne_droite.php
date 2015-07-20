<?php
use_helper("Date");
use_helper('DRM');
if (!isset($isMonEspace)) {
    $societe = $drm->getEtablissement()->getSociete();
    $etablissementPrincipal = $societe->getEtablissementPrincipal();
}
?> 
<?php
if ($isTeledeclarationMode):
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
    <?php
    end_slot();
endif;
?>

<!-- COLONNE ACTION -->
<?php
slot('colButtons');
$url_retour_espace = null;
$text_retour_espace = 'Actions';
if (isset($drm)) {
    $url_retour_espace = ($isTeledeclarationMode) ?
            url_for('drm_societe', array('identifiant' => $etablissementPrincipal->identifiant)) : url_for('drm_etablissement', array('identifiant' => $drm->identifiant));
        $text_retour_espace = ($isTeledeclarationMode) ? 'Retour à mes DRM' : "Retour au calendrier";
}
?>
<div class="bloc_col" >
    <h2><?php echo ($isTeledeclarationMode) ? 'Actions' : $text_retour_espace ?></h2>
    <div class="contenu">
        <?php if ($isTeledeclarationMode): ?>
            <div class="ligne_btn txt_centre">
                <span style="font-weight: bold;"><?php echo (isset($isMonEspace)) ? 'Mon Espace DRM' : getDrmTitle($drm); ?></span>
            </div>
        <?php endif; ?>
        <div class="ligne_btn txt_centre">
            <?php if (isset($drm) || $isTeledeclarationMode): ?>
                <?php if (!isset($isMonEspace)): ?>
                    <a href="<?php echo $url_retour_espace; ?>" class="btn_majeur btn_acces"><?php echo $text_retour_espace; ?> </a>
                <?php elseif ($isTeledeclarationMode): ?>
                    <a href="<?php echo url_for('compte_teledeclarant_mon_espace', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn_majeur btn_acces">Retour à mon espace</a>
                <?php endif; ?>
            <?php endif; ?>
        </div> 
        <?php if (!$isTeledeclarationMode): ?>
            <div class="ligne_btn txt_centre">
                <div class="btnConnexion">
                    <a href="<?php echo url_for('drm_debrayage', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn_majeur lien_connexion"><span>Connexion à la télédecl.</span></a>
                </div>       
            </div>
            <div class="text-center" style="text-align: center;">
                <p><strong><?php echo $etablissementPrincipal->nom; ?></strong></p>
                <p><strong><?php echo $etablissementPrincipal->identifiant; ?></strong></p>
                <p> (<?php echo $etablissementPrincipal->getMasterCompte()->commune; ?>) </p>            
            </div>
        <?php endif;?>
    </div>
</div>
<?php end_slot();
?>

<?php if ($isTeledeclarationMode): ?>
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
        $(document).ready(function ()
        {
            initNoticePopup();
        });
    </script>    
    <?php
    end_slot();
    ?>
<?php endif; ?>