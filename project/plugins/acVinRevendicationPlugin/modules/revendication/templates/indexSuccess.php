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
			
            <div id="revendication_selectionner_etablissement">
                <?php include_component('revendication', 'chooseEtablissement'); ?>
            </div>

            <div id="revendication_import_fichier">
	            <h2>Importer un fichier de volumes revendiqués : </h2>
				<a href="<?php echo url_for('revendication_upload'); ?>" class="btn_majeur btn_vert">Démarrer</a>
            </div>

            <div id="revendication_historique_imports">
                <h2>Historique des Imports</h2>
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
            </div>
        </section>
    </section>
</div>
