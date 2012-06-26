<?php use_helper('Produit'); ?>

<tr>
    <td>
    	<?php if (!$form->getObject()->total_debut_mois && !$form->getObject()->hasStockFinDeMoisDrmPrecedente()): ?>
    	<a href="<?php echo url_for('drm_mouvements_generaux_produit_delete', $form->getObject()) ?>" class="supprimer">Supprimer</a>
    	<?php endif; ?>
    	<?php echo produitLibelleFromDetail($form->getObject()); ?>
    </td>
	<td>
        <?php echo $form->getObject()->total_debut_mois ?> <span class="unite">hl </span>
	</td>
	<td>
		<form class="updateProduct" action="<?php echo url_for('drm_mouvements_generaux_produit_update', $form->getObject()) ?>" method="post">
			<?php echo $form->renderHiddenFields() ?>
			<?php echo $form['pas_de_mouvement_check']->render(array("class" => "pas_de_mouvement")) ?>
		</form>
	</td>
</tr>