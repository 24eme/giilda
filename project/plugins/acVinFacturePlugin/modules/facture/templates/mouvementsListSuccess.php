<?php
use_helper('Float');
?>

<div class="col-xs-12">
    <h2>Facturation libre</h2>

</div>
<br/>
<div class="row row-margin">
    <div class="col-xs-12">
    
    	<table class="table table-striped">
			    <thead>
			        <tr>
			            <th>Intitulé</th>
			            <th>Date</th>   
			            <th style="width: 90px;">Nb mouvements</th>
			            <th style="width: 90px;">Restant à facturer</th>
			            <th style="width: 90px;">&nbsp;</th>
			        </tr>
			    </thead>
			    <tbody>
		            <?php foreach ($factureMouvementsAll as $factureMouvement): ?>
			    	<tr class="vertical-center">
			    		<td class="text-left"><?php echo $factureMouvement->libelle; ?></td>
			    		<td class="text-right"><?php echo Date::francizeDate($factureMouvement->date); ?></td>
			    		<td class="text-right"><?php echo $factureMouvement->getNbMvts(); ?></td>
			    		<td class="text-right"><?php echo sprintFloat($factureMouvement->getTotalHtAFacture()); ?>&nbsp;&euro;</td>
			    		<td class="text-center"><a href="<?php echo url_for('facture_mouvements_edition', array('id' => $factureMouvement->identifiant)); ?>" class="pull-right btn btn-default">Modifier</a></td>
		            <?php endforeach; ?>
			    </tbody>
			</table>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        <a href="<?php echo url_for("facture"); ?>" class="btn btn-default">Retour à la facturation</a>
        <a href="<?php echo url_for("facture_mouvements_nouveaux"); ?>" class="btn btn-default pull-right">Facturation libre</a>

    </div>
</div>
