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
			            <th class="col-xs-4">Intitulé</th>
			            <th class="col-xs-1 text-center" >Date</th>   
                                    <th class="col-xs-3 text-center" >Nb mouvements (à facturer)</th>
			            <th class="col-xs-3 text-right">Montant (Restant à facturer)</th>
			            <th class="col-xs-1">&nbsp;</th>
			        </tr>
			    </thead>
			    <tbody>
		            <?php foreach ($factureMouvementsAll as $factureMouvement): ?>
			    	<tr class="vertical-center">
			    		<td class="col-xs-4 text-left"><?php echo $factureMouvement->libelle; ?></td>
			    		<td class="col-xs-2 text-center"><?php echo Date::francizeDate($factureMouvement->date); ?></td>
			    		<td class="col-xs-2 text-center"><?php echo $factureMouvement->getNbMvts(). ' ('.$factureMouvement->getNbMvtsAFacture().')'; ?></td>
			    		<td class="col-xs-2 text-right"><?php echo sprintFloat($factureMouvement->getTotalHt()).'&nbsp;&euro; ('.sprintFloat($factureMouvement->getTotalHtAFacture()).'&nbsp;&euro;)'; ?></td>
			    		<td class="col-xs-2 text-center"><a href="<?php echo url_for('facture_mouvements_edition', array('id' => $factureMouvement->identifiant)); ?>" class="pull-right btn btn-default">Modifier</a></td>
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
