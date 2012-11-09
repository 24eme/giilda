<?php
use_helper('Date');
?>
<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong><?php echo link_to("Page d'accueil",'revendication'); ?> > Import Volumes Revendiqués</strong></p>
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
                <table class="table_recap">
                    <thead>
                        <tr>
                            <th>N° import</th>
                            <th>Date</th>
                            <th>Campagne</th>
                            <th>Odg</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($historiqueImport as $import) : ?>
                        <tr>
                            <td><?php echo $import->id; ?></td>
                            <td><?php echo format_date($import->key[RevendicationHistoryView::KEYS_DATE],'dd/MM/yyyy'); ?></td>
                            <td><?php echo $import->key[RevendicationHistoryView::KEYS_CAMPAGNE]; ?></td>
                            <td><?php echo $import->key[RevendicationHistoryView::KEYS_ODG]; ?></td>
                        </tr>

                    <?php endforeach; ?>
                    </tbody>
                </table>
            </fieldset>
        </section>
    </section>
</div>
