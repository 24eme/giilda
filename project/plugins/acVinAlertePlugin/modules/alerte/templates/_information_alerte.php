<div id="infos_alerte">	
	<h2>Alerte</h2>
	
	<div class="bloc_form">
		<div class="ligne_form">
			<label>Type d'alerte :</label> <?php echo AlerteClient::$alertes_libelles[$alerte->type_alerte]; ?>
		</div>
		<div class="ligne_form ligne_form_alt">
			<span><label>Libellé :</label> <?php echo $alerte->id_document; ?></span>
		</div>
		<div class="ligne_form">
			<label>Opérateur :</label> <?php echo  $alerte->declarant_nom. ' ('.$alerte->identifiant . ') '; ?>
		</div>
	</div>
</div>