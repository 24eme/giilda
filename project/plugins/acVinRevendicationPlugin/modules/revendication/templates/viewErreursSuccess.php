<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
            <?php include_partial('headerRevendication', array('revendication' => $revendication,'actif' => 2)); ?>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Edition des volumes revendiqués :</h2>
            <div class="generation_facture_options">
                <a class="btn_majeur btn_valider" href="<?php echo url_for('revendication_edition', array('odg' => $revendication->odg, 'campagne' => $revendication->campagne)); ?>">Editer les volumes revendiqués</a>
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
