<h2>Modification de l'alerte</h2>
<fieldset>
    <form action="<?php echo url_for('alerte_modification', array('type_alerte' => $alerte->type_alerte, 'id_document' => $alerte->id_document)); ?>" method="POST">
        <?php
        echo $form->renderHiddenFields();
        echo $form->renderGlobalErrors();
        ?>

        <section>
            <div>
                <?php echo $form['statut']->renderError(); ?>
                <?php echo $form['statut']->renderLabel() ?>
                <?php echo $form['statut']->render() ?> 
            </div>
            <div>
                <?php echo $form['commentaire']->renderError(); ?>
                <?php echo $form['commentaire']->renderLabel() ?>
                <?php echo $form['commentaire']->render() ?> 
            </div>
        </section>
        <div>
            <button type="submit" id="alerte_valid" class="btn_majeur btn_modifier">Modifier</button>
        </div>

    </form>
</fieldset>
