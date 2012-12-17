<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <?php include_partial('header', array('revendication' => $revendication,'actif' => 1)); ?>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <div class="btn_etape">
                <a class="btn_etape_prec" href="<?php echo url_for('revendication_upload', $revendication); ?>"><span>Réimporter le fichier</span></a>
                <a class="btn_etape_suiv" href="<?php echo url_for('revendication_edition', $revendication); ?>"><span>Editer les volumes revendiqués</span></a>
            </div>
            <div class="generation_facture_options" style="text-align: center; margin-top: 30px;">
                <a class="btn_majeur btn_refraichir" href="<?php echo url_for('revendication_update', $revendication); ?>">Revérifier les erreurs</a>
            </div>
            <?php include_partial('viewErreurs', array('revendication' => $revendication)) ?>

        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->

    <!-- #colonne -->
    <aside id="colonne">
        <div class="bloc_col" id="contrat_aide">
            <h2>Aide</h2>

            <div class="contenu">
                <ul>
                    <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                    <li class="assistance"><a href="#">Assistance</a></li>
                    <li class="contact"><a href="#">Contacter le support</a></li>
                </ul>
            </div>
        </div>
    </aside>
    <!-- fin #colonne -->
</div>
