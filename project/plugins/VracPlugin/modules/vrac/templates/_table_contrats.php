<?php
use_helper('Vrac');
?>
<div style="margin: 10px;">
    <p>
        <form>
            <input name="identifiant" value="<?php echo (isset($identifiant)) ? $identifiant : '' ; ?>"/> <input type="submit" value="recherche"/>
        </form>
    </p>
</div>
<style>
.odd {background-color: #BBBBBB; width: 700px;}
td{padding: 0px 10px;}
.status { width: 5%; }
.num_contrat { width: 10%; }
.soussigne { width: 15%; }
.type { width: 10%; }
.vol { width: 5%; }
</style>

<table>    
    <thead>
        <tr class="odd" style="width:300px;">
            <th class="status">Statut</th>
            <th class="num_contrat">N° Contrat</th>
            <th class="soussigne">Acheteur</th>
            <th class="soussigne">Vendeur</th>
            <th class="soussigne">Mandataire</th>
            <th class="type">Type</th>
            <th class="soussigne">Produit</th>
            <th class="vol">Vol. com.</th>
            <th class="vol">Vol. enlv.</th>
        </tr>
    </thead>
    <tbody>
        <?php $cpt = 1;
foreach ($vracs->rows as $value) {    $cpt *= -1;
            $elt = $value->getRawValue()->value;
        ?>
        <tr<?php if($cpt > 0) echo ' class="odd"'; ?>  style="width:300px;">
            <td class="status">
                
                <?php 
                      $statutImg = statutImg($elt[0]);
                      if($elt[0])
                      { ?>
                        <img alt="<?php echo $statutImg->alt; ?>"
                            src="<?php echo $statutImg->src; ?>" />
                <?php } ?>
            </td>
            <td class="num_contrat"><?php $vracid = preg_replace('/VRAC-/', '', $elt[1]); echo link_to($vracid, '@vrac_termine?numero_contrat='.$vracid); ?></td>
            <td class="soussigne"><?php echo ($elt[2]) ? link_to($elt[3], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[2])) : ''; ?></td>
	      <td class="soussigne"><?php echo ($elt[4]) ? link_to($elt[5], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[4])) : ''; ?></td>
	      <td class="soussigne"><?php echo ($elt[6]) ? link_to($elt[7], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[6])) : ''; ?></td>
              <td class="type"><?php echo ($elt[8])? typeProduit($elt[8]) : ''; ?></td>
	      <td class="soussigne"><?php echo ($elt[9])? ConfigurationClient::getCurrent()->get($elt[9])->libelleProduit() : ''; ?></td>
              <td class="vol"><?php echo $elt[10]; ?></td>
              <td class="vol"><?php echo $elt[11]; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>    