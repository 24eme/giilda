<form id="generation_masse_form" action="<?php echo url_for('facture_generer_masse'); ?>" method="post">
<h2>Génération en masse</h2>
<div class="generation_facture_options">
    <ul>
        <li>
        <span>1. <?php  echo $generationForm['region']->renderlabel(); ?></span>
            <?php echo $generationForm['region']->renderError() ?>        
            <?php  echo $generationForm['region']->render(); ?> 
           
        </li>
        
        <li>
        <span>2. Définir les seuils de facturation et d'avoir : </span>
            <div>
                    <?php  echo $generationForm['seuil_facture']->renderlabel(); ?>
                    <?php echo $generationForm['seuil_facture']->renderError() ?> 
                    <?php  echo $generationForm['seuil_facture']->render(); ?>             
            </div>
            <div>
                  <?php  echo $generationForm['seuil_avoir']->renderlabel(); ?>
                  <?php echo $generationForm['seuil_avoir']->renderError() ?> 
                  <?php  echo $generationForm['seuil_avoir']->render(); ?>             
            </div>
        </li>
        
        <li>
        <span>3. Choisir la date de facturation :</span>
        <span>(Tous les mouvements antérieurs à la date saisie seront facturés. Cette date figurera sur la facture)</span>
                <?php  echo $generationForm['date_mouvement']->renderlabel(); ?>
                <?php echo $generationForm['date_mouvement']->renderError() ?> 
                <?php  echo $generationForm['date_mouvement']->render(); ?>
        </li>
    </ul>    
</div>
</form>
<div class="generation_facture_valid">
    <a href="#" id="generation_masse" class="btn_majeur btn_vert">Générer</a>
</div>

<script type="text/javascript">
    
    $(document).ready( function()
	{
            $('#generation_masse').bind('click', function()
            {
                $('form#generation_masse_form').submit();
            });
        });
    
</script>

