<div id="contenu" class="sv12">    
	<!-- #principal -->
	<section id="principal">
		<p id="fil_ariane"><a href="<?php echo url_for('sv12') ?>">Page d'accueil</a> &gt; <a href="<?php echo url_for('sv12_etablissement', $sv12->getEtablissementObject()) ?>"><?php echo $sv12->declarant->nom ?></a> &gt; <a href="<?php echo url_for('sv12_update', $sv12) ?>"><?php echo $sv12 ?></a> &gt; <strong>Ajouter un produit</strong></p>
		
		<!-- #contenu_etape -->
		<section id="contenu_etape">
			<h2>Déclaration SV12</h2>
			<?php include_partial('produitForm', array('sv12' => $sv12, 'form' => $form)); ?>
		</section>
		<!-- fin #contenu_etape -->
	</section>
	
	<?php include_partial('colonne', array('negociant' => $sv12->declarant)); ?>
	<!-- fin #principal -->
</div>
<script type="text/javascript">
$(document).ready(function () {
		$( "#<?php echo $form['hashref']->renderId() ?>" ).combobox();
});
</script>
    
