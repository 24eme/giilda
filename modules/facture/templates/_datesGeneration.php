<span>Choisir la date de facturation :</span>
<span>(Tous les mouvements antérieurs à la date saisie seront facturés. Cette date figurera sur la facture)</span>
<div class="ligne_form champ_datepicker">
    <?php echo $form['date_facturation']->renderlabel(); ?>
    <?php echo $form['date_facturation']->renderError() ?> 
    <?php echo $form['date_facturation']->render(); ?>
</div>
<div class="ligne_form champ_datepicker">
    <?php echo $form['date_mouvement']->renderlabel(); ?>
    <?php echo $form['date_mouvement']->renderError() ?> 
    <?php echo $form['date_mouvement']->render(); ?>
</div>