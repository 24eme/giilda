<?php
use_helper("Date");
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
            <a href="/data/guide_drm.pdf" id="liens_notices" class="lien_telechargement">Télécharger la notice</a>
            <br/>
            <br/>
            <p class="lien_lecteur_pdf">
                Ce document est au format PDF. Pour la visualiser, veuillez utiliser un <a target="_blank" href="<?php echo sfConfig::get('app_pdf_reader_link') ?>">lecteur PDF</a>.
            </p>
            
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