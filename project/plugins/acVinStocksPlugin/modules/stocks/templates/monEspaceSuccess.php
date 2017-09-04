   <!-- #principal -->
    <section id="principal"  class="drm">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('stocks', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>

            <form method="post">
                <?php echo $formCampagne->renderGlobalErrors() ?>
                <?php echo $formCampagne->renderHiddenFields() ?>
                <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
            </form>

            <?php include_partial('stocks/recap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>            
            <?php include_component('stocks', 'mouvements', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
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
            <a href="<?php echo url_for('stocks'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
