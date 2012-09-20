<form id="generation_operateur_form" action="<?php echo url_for('ds_generation_operateur',$operateur); ?>" method="post">
<h2>Génération des formulaires de déclaration</h2>
<div class="generation_facture_options">
    <ul>
        <li>
        <span>1. <?php  echo $generationOperateurForm['campagne']->renderlabel(); ?></span>
              <?php echo $generationOperateurForm['campagne']->renderError() ?>        
              <?php  echo $generationOperateurForm['campagne']->render(); ?> 
        </li>
        <li>
            <div class="ligne_form champ_datepicker">
                <?php  echo '2. '.$generationOperateurForm['date_declaration']->renderlabel(); ?>       
                <?php echo $generationOperateurForm['date_declaration']->renderError() ?>  
                <?php  echo $generationOperateurForm['date_declaration']->render(); ?>
            </div>
        </li>
    </ul>    
</div>
</form>

<div class="generation_ds_operateur">
    <span>Cliquer sur "Générer" pour lancer la création des formulaire</span>
        <a href="#" id="generation_operateur" class="btn_majeur btn_vert">Générer</a>
</div>

<script type="text/javascript">
    
    $(document).ready( function()
	{
            $('#generation_operateur').bind('click', function()
            {
                $('form#generation_operateur_form').submit();
            });
        });
    
</script>

