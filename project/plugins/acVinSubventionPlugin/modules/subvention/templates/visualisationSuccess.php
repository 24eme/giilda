<?php include_partial('subvention/breadcrumb', array('subvention' => $subvention)); ?>

<section id="principal" class="form-horizontal">
    <?php include_partial('subvention/etapes', array('subvention' => $subvention)); ?>
        <h1>Informations du dossier de subvention</h1>
        <p>Saisie de informations de votre dossier de subvention</p>
        <div class="row">
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Entreprise</h3></div>
                    <div class="panel-body">
                        <div class="form-group" style="margin-bottom: 0">
                           <label class="col-sm-3 control-label">Raison sociale</label>
                           <div class="col-sm-6">
                                <p class="form-control-static"><?php echo $subvention->declarant->raison_sociale ?></p>
                           </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0">
                           <label class="col-sm-3 control-label">Famille</label>
                           <div class="col-sm-6">
                                <p class="form-control-static"><?php echo EtablissementFamilles::getFamilleLibelle($subvention->declarant->famille) ?></p>
                           </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0">
                           <label class="col-sm-3 control-label">SIRET</label>
                           <div class="col-sm-6">
                                <p class="form-control-static"><?php echo $subvention->declarant->siret ?></p>
                           </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0">
                           <label class="col-sm-3 control-label">Adresse</label>
                           <div class="col-sm-6">
                                <p class="form-control-static"><?php echo $subvention->declarant->adresse ?></p>
                           </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0">
                           <label class="col-sm-3 control-label">Code postal</label>
                           <div class="col-sm-6">
                                <p class="form-control-static"><?php echo $subvention->declarant->code_postal ?></p>
                           </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0">
                           <label class="col-sm-3 control-label">Commune</label>
                           <div class="col-sm-6">
                                <p class="form-control-static"><?php echo $subvention->declarant->commune ?></p>
                           </div>
                        </div>
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
                           <label class="col-sm-3 control-label"><?php echo $items->getInfosSchemaItem($key, "label") ?></label>
                           <div class="col-sm-6">
                                <p class="form-control-static"><?php echo $item ?>&nbsp;<small class="text-muted"><?php echo $items->getInfosSchemaItem($key, "unite") ?></small></p>
                           </div>
                        </div>
                    	<?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-6">
                <a class="btn btn-default" tabindex="-1" href=""><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Étape précédente</a>
            </div>
            <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-success">Étape suivante&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
</div>
