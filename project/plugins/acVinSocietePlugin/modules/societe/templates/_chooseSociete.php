<form action="<?php echo url_for('societe'); ?>" method="post">
    <div class="section_label_maj" id="recherche_societe">
        <?php echo $form['identifiant']->renderError(); ?>
        <?php echo $form['identifiant']->renderLabel(); ?>
        <?php echo $form['identifiant']->render(); ?>
    </div>
    <div class="section_label_maj" id="recherche_societe">
        <?php echo $form['societeType']->renderError(); ?>
        <?php echo $form['societeType']->renderLabel(); ?>
        <?php echo $form['societeType']->render(); ?>
    </div>
    <button id="btn_rechercher" type="submit">Créer</button>
</form>