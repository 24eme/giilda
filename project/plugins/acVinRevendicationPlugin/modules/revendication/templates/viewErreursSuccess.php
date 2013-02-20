    <!-- #principal -->
    <section id="principal" class="revendication">
        <?php include_partial('header', array('revendication' => $revendication,'actif' => 1)); ?>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <div class="btn_etape">
                <a class="btn_etape_prec" href="<?php echo url_for('revendication_upload', $revendication); ?>"><span>Réimporter le fichier</span></a>
                <a class="btn_etape_suiv" href="<?php echo url_for('revendication_edition', $revendication); ?>"><span>Editer les volumes revendiqués</span></a>
            </div>
            <div class="generation_facture_options" style="text-align: center; margin-top: 30px;">
                <a class="btn_majeur btn_refraichir" href="<?php echo url_for('revendication_update', $revendication); ?>">Revérifier les erreurs</a>
                <a class="btn_majeur btn_excel" href="<?php echo url_for('revendication_downloadCSV', $revendication); ?>">Télécharger le fichier originel</a>
            </div>
            <?php include_partial('viewErreurs', array('revendication' => $revendication)) ?>

        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('revendication'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>