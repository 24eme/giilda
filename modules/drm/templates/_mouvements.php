<?php use_helper('Mouvement') ?>

<?php if(count($mouvements) > 0): ?>
<?php if(isset($hamza_style)) : ?>
    <?php include_partial('global/hamzaStyle', array('table_selector' => '#table_mouvements', 
                                                 'mots' => mouvement_get_words($mouvements))) ?>
<?php endif; ?>
<?php use_helper('Float'); use_helper('Date'); ?>

<table id="table_mouvements" class="table_recap">
    <thead>
        <tr>
            <th style="width: 170px;">Date de modification</th>
            <th style="width: 280px;">Produits</th>
            <th>Type</th>
            <th>Volume</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    <?php foreach($mouvements as $mouvement): ?>
    <?php $i++; ?>
        <tr id="<?php echo mouvement_get_id($mouvement) ?>" class="<?php if($i%2!=0) echo "alt"; if ($mouvement->facturable) {echo " facturable";}  ?>">
            <td>
                <?php echo sprintf("%s - %s", ($mouvement->version) ? $mouvement->version : 'M00', format_date($mouvement->date_version));?>
            </td>
            <td><?php echo $mouvement->produit_libelle ?> </td>
            <td><?php
	    if ($mouvement->vrac_numero)
	      echo '<a href="'.url_for("vrac_visualisation", array("numero_contrat" => $mouvement->vrac_numero)).'">'; 
echo $mouvement->type_libelle.' '.$mouvement->detail_libelle;
if ($mouvement->vrac_numero)
  echo "</a>"; 
?></td>
            <td <?php echo ($mouvement->volume > 0)? ' class="positif"' : 'class="negatif"';?> >
                <?php  echoSignedFloat($mouvement->volume); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>Pas de mouvements</p>
<?php endif; ?>