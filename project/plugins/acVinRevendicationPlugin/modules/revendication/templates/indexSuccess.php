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
                <?php                include_partial('historiqueRevendication', array('historiqueImport' => $historiqueImport)); ?>
            </div>
        </section>
    </section>
</div>
