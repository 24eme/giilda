
<!-- #principal -->
<section id="principal">
    <p id="fil_ariane">Page d'accueil > <strong>Création d'une société</strong></p>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <h2>Création d'une société</h2>
        <form action="<?php echo url_for('societe_creation'); ?>" method="post">
            <div id="recherche_societe" class="section_label_maj">
                <h2>Sélectionner un type de société </h2>
                <div class="form_ligne">
                <div class="section_label_maj">
                <?php echo $form['raison_sociale']->renderError(); ?>
                    <?php echo $form['raison_sociale']->renderLabel(); ?>
                <?php echo $form['raison_sociale']->render(); ?>
                </div>
                <div class="section_label_maj">
                    <?php echo $form['type']->renderError(); ?>
                    <?php echo $form['type']->renderLabel(); ?>
                    <?php echo $form['type']->render(); ?>
                </div>
                <button id="btn_rechercher" type="submit" class="btn_majeur btn_acces">Créer</button>
            </div>
        </form>
    </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
