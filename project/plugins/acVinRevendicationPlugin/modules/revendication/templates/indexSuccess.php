<!-- #principal -->
<section id="principal">
        <p id="fil_ariane"><strong><?php echo link_to("Page d'accueil",'revendication'); ?></strong></p>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('revendication', 'chooseEtablissement', array('form' => $formEtablissement)); ?>

            <h2>Créer une revendication</h2>
            <div id="revendication_create_revendication">
                <?php include_partial('formOdgAndCampagne', array('form' => $form)); ?>
            </div>

            <div id="revendication_historique_imports">
                <?php include_partial('historiqueRevendication', array('historiqueImport' => $historiqueImport)); ?>
            </div>
        </section>
 </section>
