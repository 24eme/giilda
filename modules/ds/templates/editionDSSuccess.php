<?php
use_helper('Float');
?>
<div id="contenu" class="ds">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('ds') ?>">Page d'accueil</a> &gt; <strong>Stocks : consultation & déclaration</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Consulter les stocks d'un opérateur :</h2>
          <?php include_component('ds', 'chooseEtablissement', array('identifiant' => $ds->identifiant));?>

			<h2>Détail opérateur</h2>
			<?php 
			   include_partial('operateurInformations', array('operateur' => $ds->declarant)); 
			?>
			
			<?php 
			   include_partial('dsInformations', array('ds' => $ds));
			?>
			
			<?php 
			   include_partial('dsEditionFormContent', array('ds' => $ds, 'declarations' => $ds->declarations,'form' => $form));
			?>
		</section>
    </section>
    <!-- fin #principal -->
    
    <!-- #colonne -->
    <aside id="colonne">
        <div class="bloc_col" id="contrat_aide">
            <h2>Aide</h2>
            
            <div class="contenu">
                <ul>
                    <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                    <li class="assistance"><a href="#">Assistance</a></li>
                    <li class="contact"><a href="#">Contacter le support</a></li>
                </ul>
            </div>
        </div>
    </aside>
    <!-- fin #colonne -->
</div>

<script type="text/javascript">
    
    $(document).ready( function()
	{
            $('#generation_ds').bind('click', function()
            {
                $('form#generation_form').submit();
            });
        });
    
</script>

