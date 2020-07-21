<div class="row">
    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Entreprise</h3></div>
            <div class="panel-body">
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-4 control-label">Raison sociale</label>
                   <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $subvention->declarant->raison_sociale ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-4 control-label">Famille</label>
                   <div class="col-sm-8">
                        <p class="form-control-static"><?php echo EtablissementFamilles::getFamilleLibelle($subvention->declarant->famille) ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-4 control-label">SIRET</label>
                   <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $subvention->declarant->siret ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-4 control-label">Adresse</label>
                   <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $subvention->declarant->adresse ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-4 control-label">Code postal</label>
                   <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $subvention->declarant->code_postal ?></p>
                   </div>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-4 control-label">Commune</label>
                   <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $subvention->declarant->commune ?></p>
                   </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Engagements</h3></div>
            <div class="panel-body">
            <ul>
            <?php foreach ($subvention->engagements as $k => $v): ?>
            	<li>
            		<small>
            		<?php echo $subvention->getConfiguration()->getEngagementLibelle($k) ?>
            		<?php 
            		      if ($subvention->engagements_precisions->exist($k)): 
            		          $nb = count($subvention->engagements_precisions->get($k));
            		          $i=0;
            		          foreach ($subvention->engagements_precisions->get($k) as $sk => $sv): 
            		?>
            		<?php echo trim($subvention->getConfiguration()->getEngagementPrecisionLibelle($k, $sk)) ?><?php if ($i < ($nb-1)) echo ', '; ?>
            		<?php $i++; endforeach; endif; ?>
            		</small>
            	</li>
            <?php endforeach; ?>
            </ul>
            </div>
        </div>
    </div>
    <div class="col-xs-6">
    	<?php foreach($subvention->infos as $categorie => $items): ?>
    	<div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><?php echo $items->getLibelle() ?></h3></div>
            <div class="panel-body">
            	<?php foreach($items as $key => $item): ?>
                <div class="form-group" style="margin-bottom: 0">
                   <label class="col-sm-4 control-label"><?php echo $items->getInfosSchemaItem($key, "label") ?></label>
                   <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item ?>&nbsp;<small class="text-muted"><?php echo $items->getInfosSchemaItem($key, "unite") ?></small></p>
                   </div>
                </div>
            	<?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Documents</h3></div>
            <div class="panel-body">
                <a href="<?php echo url_for('subvention_pdf', $subvention) ?>" class="btn btn-default btn-xs">Fiche de pré-qualification</a>
                <a href="<?php echo url_for('subvention_xls', $subvention) ?>" class="btn btn-default btn-xs">Descriptif détaillé de l'opération</a>
                <a href="" class="btn btn-default btn-xs">Notice</a>
                <a href="" class="btn btn-default btn-xs">Charte graphique</a>
            </div>
        </div>
    </div>
</div>
<style>
.form-control-static {
    padding: 5px 0;
    min-height: 1px;
}
.form-horizontal .control-label {
    padding: 5px 10px 0 10px;
}
</style>