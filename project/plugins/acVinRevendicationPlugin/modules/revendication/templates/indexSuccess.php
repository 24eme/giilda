<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        <!-- #contenu_etape -->
        <section id="contenu_etape">

            <h2>Rechercher un opérateur :</h2>
            <fieldset id="revendication_volume_revendiques_edition">
                <?php include_component('revendication', 'chooseEtablissement'); ?>
            </fieldset>
            
            <h2>Importer un fichier de volumes revendiqués : </h2>
            <fieldset id="revendication_volume_revendiques_edition">
                <a href="<?php echo url_for('revendication_upload'); ?>" class="btn_majeur btn_vert">Démarrer</a>
            </fieldset>
            
            <h2>Historique des Imports</h2>
            <fieldset id="revendication_volume_revendiques_edition">
                
            </fieldset>
        </section>
    </section>
</div>
