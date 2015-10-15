
<div class="row row-margin">
    <div class="col-xs-6">
        <div class="row">
            <div class="col-xs-6">
                <?php echo $form['date_facturation']->renderlabel(); ?>
                <?php echo $form['date_facturation']->renderError() ?> 
                <?php echo $form['date_facturation']->render(); ?>
            </div>
            <div class="col-xs-6">
                <?php echo $form['date_mouvement']->renderlabel(); ?>
                <?php echo $form['date_mouvement']->renderError() ?> 
                <?php echo $form['date_mouvement']->render(); ?>
            </div>
        </div>
    </div>

    <div class="col-xs-6">
        <div class="row">
            <?php echo $form['message_communication']->renderlabel(); ?>
            <?php echo $form['message_communication']->renderError() ?> 

        </div>
        <div class="row">
            <?php echo $form['message_communication']->render(array('style' => 'width:100%')); ?>
        </div>
    </div>
</div>