<tr>
<td><?php echo $prod_libelle.' '.$prod_vol; if ($declaration->hasElaboration()) echo " - en cave"; ?></td>
<td><?php
echo $form['volumeStock_'.$key]->renderError();
echo $form['volumeStock_'.$key]->render();
?></td>
<td class="colonne_vci"><?php
  echo $form['vci_'.$key]->renderError();
echo $form['vci_'.$key]->render();
?></td>
<td class="colonne_reservequalitative"><?php
echo $form['reserveQualitative_'.$key]->renderError();
echo $form['reserveQualitative_'.$key]->render();
?></td>
</tr>
<?php if  ($declaration->hasElaboration()): ?>
<tr>
<td><?php echo $prod_libelle; ?> - en élaboration</td>
<td><?php
echo $form['elaboration_'.$key]->renderError();
echo $form['elaboration_'.$key]->render();
?></td>
<td class="colonne_vci">&nbsp;</td>
<td class="colonne_reservequalitative">&nbsp;</td>
</tr>
<?php endif; ?>