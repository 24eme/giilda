<form id="relance_generation" action="<?php echo url_for('relance_generation'); ?>" method="post">
<h2>Génération en masse</h2>
<div class="generation_relance_options">
    <ul>
        <span>1. Définir les seuils de facturation et d'avoir : </span>
            <div>
                    <?php  echo $generationForm['types_relance']->renderlabel(); ?>
                    <?php echo $generationForm['types_relance']->renderError() ?> 
                    <?php  echo $generationForm['types_relance']->render(); ?>             
            </div>
        </li>
        
        <li>
        <span>2. Choisir la date de relance :</span>
            <div class="ligne_form champ_datepicker">
                <?php  echo $generationForm['date_relance']->renderlabel(); ?>
                <?php echo $generationForm['date_relance']->renderError() ?> 
                <?php  echo $generationForm['date_relance']->render(); ?>
            </div>
        </li>
    </ul>    
</div>
</form>
<div class="generation_facture_valid">
       <span>Cliquer sur "Générer" pour lancer la génération des relances</span>
    <a href="#" id="relance_generation_btn" class="btn_majeur btn_refraichir">Générer</a>
</div>

<script type="text/javascript">
    
    $(document).ready( function()
	{
            $('#relance_generation_btn').bind('click', function()
            {
                $('form#relance_generation').submit();
		return false;
            });
        });
    
</script>

