<div class="form_ligne" data-key="<?php echo $form->getName() ?>">
	<table>
		<tr>
			<td><span class="error"><?php echo $form['date']->renderError() ?></span><?php echo $form['date']->renderLabel() ?><br /><?php echo $form['date']->render() ?></td>
			<td><span class="error"><?php echo $form['libelle']->renderError() ?></span><?php echo $form['libelle']->renderLabel() ?><br /><?php echo $form['libelle']->render() ?></td>
			<td><span class="error"><?php echo $form['code']->renderError() ?></span><?php echo $form['code']->renderLabel() ?><br /><?php echo $form['code']->render() ?></td>
			<td><span class="error"><?php echo $form['taux']->renderError() ?></span><?php echo $form['taux']->renderLabel() ?><br /><?php echo $form['taux']->render() ?></td>
			<td><br />&nbsp;<a href="javascript:void(0)" class="removeForm btn_suppr"></a></td>
		</tr>
	</table>
</div>
