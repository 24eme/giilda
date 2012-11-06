<h2>Consultation des alertes</h2>
<fieldset>
    <form action="<?php echo url_for('alerte'); ?>" method="POST">
        <?php
        echo $form->renderHiddenFields();
        echo $form->renderGlobalErrors();
        ?>

        <section>
            <div>
                <?php echo $form['declarant_alerte']->renderError(); ?>
                <?php echo $form['declarant_alerte']->renderLabel() ?>
                <?php echo $form['declarant_alerte']->render() ?> 
            </div>
            <div>
                <?php echo $form['region_alerte']->renderError(); ?>
                <?php echo $form['region_alerte']->renderLabel() ?>
                <?php echo $form['region_alerte']->render() ?> 
            </div>
            <div>
                <?php echo $form['type_alerte']->renderError(); ?>
                <?php echo $form['type_alerte']->renderLabel() ?>
                <?php echo $form['type_alerte']->render() ?> 
            </div>
            <div>
                <?php echo $form['statut_alerte']->renderError(); ?>
                <?php echo $form['statut_alerte']->renderLabel() ?>
                <?php echo $form['statut_alerte']->render() ?> 
            </div>
            <div>
                <?php echo $form['campagne_alerte']->renderError(); ?>
                <?php echo $form['campagne_alerte']->renderLabel() ?>
                <?php echo $form['campagne_alerte']->render() ?> 
            </div>
        </section>
        <div>
            <button type="submit" id="alerte_search_valid" class="btn_majeur btn_vert">Rechercher</button>
        </div>
        
        
    </form>
</fieldset>
