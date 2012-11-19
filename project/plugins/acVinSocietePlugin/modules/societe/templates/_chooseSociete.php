<form action="<?php echo url_for('societe');?>" method="post">
    <div class="section_label_maj" id="recherche_societe">
        <?php echo $form['identifiant']->renderError(); ?>
        <?php echo $form['identifiant']->renderLabel(); ?>
        <?php echo $form['identifiant']->render(); ?>

        <button id="btn_rechercher" type="submit">Rechercher</button>
    </div>
</form>