<?php
use_helper('Vrac');
?>
<div style="margin: 10px;">
<p><form>
<input name="identifiant" value="<?php echo (isset($identifiant)) ? $identifiant : '' ; ?>"/> <input type="submit" value="recherche"/>
</form></p>
</div>
<style>
.odd {background-color: #BBBBBB;}
td{padding: 0px 10px;}
</style>
<table>    
    <thead>
        <tr class="odd">
            <th>Statut</th>
            <th>N° Contrat</th>
            <th>Acheteur</th>
            <th>Vendeur</th>
            <th>Mandataire</th>
            <th>Type</th>
            <th>Produit</th>
            <th>Vol. com.</th>
            <th>Vol. enlv.</th>
        </tr>
    </thead>
    <tbody>
        <?php $cpt = 1;
foreach ($vracs->rows as $value) {    $cpt *= -1;
            $elt = $value->getRawValue()->value;
        ?>
        <tr<?php if($cpt > 0) echo ' class="odd"'; ?>>
            <td>
                
                <?php 
                      $statusImg = statusImg($elt[0]);
                      if($elt[0])
                      { ?>
                        <img alt="<?php echo $statusImg->alt; ?>"
                            src="<?php echo $statusImg->src; ?>" />
                <?php } ?>
            </td>
	      <td><?php $vracid = preg_replace('/VRAC-/', '', $elt[1]); echo link_to($vracid, '@vrac_termine?numero_contrat='.$vracid); ?></td>
	      <td><?php echo ($elt[2]) ? link_to($elt[3], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[2])) : ''; ?></td>
	      <td><?php echo ($elt[4]) ? link_to($elt[5], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[4])) : ''; ?></td>
	      <td><?php echo ($elt[6]) ? link_to($elt[7], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[6])) : ''; ?></td>
              <td><?php echo ($elt[8])? typeProduit($elt[8]) : ''; ?></td>
	      <td><?php echo ($elt[9])? ConfigurationClient::getCurrent()->get($elt[9])->libelleProduit() : ''; ?></td>
	      <td><?php echo $elt[10]; ?></td>
	      <td><?php echo $elt[11]; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>    