<div class="ligne_form" data-key="<?php echo $form->getName() ?>">
    <span><?php echo $form['date']->renderError() ?></span>
    <?php echo $form['date']->renderLabel() ?>
    <br />
    <?php echo $form['date']->render() ?>

    <span ><?php echo $form['taux']->renderError() ?></span>
    <?php echo $form['taux']->renderLabel() ?><br /><?php echo $form['taux']->render() ?>

</div>
