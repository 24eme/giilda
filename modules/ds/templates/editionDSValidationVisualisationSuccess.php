<?php
use_helper('Float');
?>    
    <!-- #principal -->
    <section id="principal" class="ds">
        <p id="fil_ariane"><a href="<?php echo url_for('ds') ?>">Page d'accueil</a> &gt; <strong>Stocks : consultation & déclaration</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Consulter les stocks d'un opérateur :</h2>
          <?php include_component('ds', 'chooseEtablissement', array('identifiant' => $ds->identifiant)); ?>
        
			<h2>Détail opérateur</h2>
			<?php 
			   include_partial('operateurInformations', array('operateur' => $ds->declarant));
			?>
			
			<?php
			   include_partial('dsInformations', array('ds' => $ds));
			?>
			
			<?php 
			   include_partial('dsRecapitulatif', array('ds' => $ds, 'declarations' => $ds->declarations));
			?>
		</section>
    </section>
    <!-- fin #principal -->


<script type="text/javascript">
    
    $(document).ready( function()
	{
            $('#generation_ds').bind('click', function()
            {
                $('form#generation_form').submit();
            });
        });
    
</script>

