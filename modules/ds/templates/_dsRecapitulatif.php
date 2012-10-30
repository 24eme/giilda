<?php
use_helper('Float');
?>
<form action="<?php echo url_for('ds_edition_operateur_validation_visualisation', $ds); ?>" method="post" id="ds_edition_validation_form">
<fieldset id="dsRecapitulatif">
	<table id="ds_recapitulatif_table" class="table_recap">
	<thead>
		<tr>
			<th>Produits</th>
			<th>Stock initial</th>
			<th>Volume revendiqué</th>
		</tr>
	</thead>
	<tbody class="ds_recapitulatif_tableBody">
		<?php foreach ($ds->declarations as $declaration) :
		?>
		<tr id="ds_declaration_recapitulatif">
			<td class="ds_recap_declaration_appelation">
				<?php echo $declaration->produit_libelle; ?>
			</td>
			<td class="ds_recap_declaration_stockInitial">
				<?php echo $declaration->stock_initial; ?>
			</td>
			<td class="ds_recap_declaration_vr">
				<?php echoFloat($declaration->stock_revendique); ?>
			</td>
		  </tr>
		<?php
		endforeach;
		?>
	</tbody>
	</table> 
</fieldset>

<div id="ds_recapitulatif_commentaires">
    <h3>Commentaires&nbsp;: </h3>
	<p><?php echo $ds->commentaires; ?></p>
</div>

<div class="btn_etape">
	<a href="<?php echo url_for('ds_edition_operateur', $ds); ?>" class="btn_etape_prec"><span>Etape précédente</span></a>
	<button type="submit" id="ds_edition_validation" class="btn_majeur btn_valider ds_declaration_validation">Terminer</button>
</div>
    
</form>