<?php use_helper('Version'); ?>
<table  class="table table-striped table-filter table-bordered" style="border-top:none;">
	<thead>
		<tr>
			<th style="background:none;border-color:#fff;">&nbsp;</th>
			<th colspan="2" class="text-center col-md-2"><?php echo $ds->millesime ?></th>
			<th colspan="2" class="text-center col-md-2"><?php echo $ds->millesime - 1 ?></th>
			<th colspan="2" class="text-center  col-md-2"><?php echo $ds->millesime - 2 ?>, précédent et non millésimé</th>
		</tr>
  		<tr>
  			<th>Produit</th>
  			<th class="text-center">Stock</th>
  			<th class="text-center">Dispo.</th>
  			<th class="text-center">Stock</th>
  			<th class="text-center">Dispo.</th>
  			<th class="text-center">Stock</th>
  			<th class="text-center">Dispo.</th>
  		</tr>
	</thead>
	<tbody>
    <?php
      foreach($ds->declaration as $hash => $produit):
        $libelle = $produit->libelle;
        foreach($produit->detail as $detail => $stocks):
          if ($stocks->denomination_complementaire) {
            $libelle .= ' '.$stocks->denomination_complementaire;
          }
    ?>
		<tr>
      <td><strong><?php echo $libelle ?><?php if ($detail !== "DEFAUT") echo " (".$detail.")"; ?></strong></td>
			<td class="text-right <?php echo isVersionnerCssClass($stocks, 'stock_declare_millesime_courant') ?>"><?php if($stocks->stock_declare_millesime_courant !== null): ?><?php echoFloat($stocks->stock_declare_millesime_courant) ?>&nbsp;<span class="text-muted">hl</small><?php endif; ?></td>
			<td class="text-right <?php echo isVersionnerCssClass($stocks, 'dont_vraclibre_millesime_courant') ?>"><?php if($stocks->dont_vraclibre_millesime_courant !== null): ?><?php echoFloat($stocks->dont_vraclibre_millesime_courant) ?>&nbsp;<span class="text-muted">hl</small><?php endif; ?></td>
			<td class="text-right <?php echo isVersionnerCssClass($stocks, 'stock_declare_millesime_precedent') ?>"><?php if($stocks->stock_declare_millesime_precedent !== null): ?><?php echoFloat($stocks->stock_declare_millesime_precedent) ?>&nbsp;<span class="text-muted">hl</small><?php endif; ?></td>
			<td class="text-right <?php echo isVersionnerCssClass($stocks, 'dont_vraclibre_millesime_precedent') ?>"><?php if($stocks->dont_vraclibre_millesime_precedent !== null): ?><?php echoFloat($stocks->dont_vraclibre_millesime_precedent) ?>&nbsp;<span class="text-muted">hl</small><?php endif; ?></td>
			<td class="text-right <?php echo isVersionnerCssClass($stocks, 'stock_declare_millesime_anterieur') ?>"><?php if($stocks->stock_declare_millesime_anterieur !== null): ?><?php echoFloat($stocks->stock_declare_millesime_anterieur) ?>&nbsp;<span class="text-muted">hl</small><?php endif; ?></td>
			<td class="text-right <?php echo isVersionnerCssClass($stocks, 'dont_vraclibre_millesime_anterieur') ?>"><?php if($stocks->dont_vraclibre_millesime_anterieur !== null): ?><?php echoFloat($stocks->dont_vraclibre_millesime_anterieur) ?>&nbsp;<span class="text-muted">hl</small><?php endif; ?></td>
		</tr>
		<?php endforeach;endforeach; ?>
	</tbody>
</table>
