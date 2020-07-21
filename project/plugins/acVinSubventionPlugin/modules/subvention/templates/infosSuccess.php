<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
    <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $subvention->identifiant)) ?>"><?php echo $subvention->declarant->nom ?> (<?php echo $subvention->identifiant ?>)</a></li>
    <li><a href="<?php echo url_for('subvention_etablissement', $subvention->getEtablissement()) ?>"><?php echo $subvention->operation ?></a></li>
    <li class="active"><a href="">Informations</a></li>
</ol>

<section id="principal">
    <?php include_partial('subvention/etapes', array('subvention' => $subvention)); ?>

    <form class="form-horizontal" method="POST" action="">

        <?php echo $form->renderGlobalErrors(); ?>
        <?php echo $form->renderHiddenFields(); ?>

        <h1>Informations du dossier de subvention</h1>
        <p>Saisie de informations de votre dossier de subvention</p>
        <div class="row">
            <div class="col-xs-12">
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
                <?php foreach($form as $categorie => $items): ?>
            <?php if(!$items instanceof sfFormFieldSchema): continue; endif; ?>

            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title"><?php echo $subvention->infos->get($categorie)->getLibelle() ?></h3></div>
                <div class="panel-body">
                    <?php foreach($items as $key => $item): ?>
                        <div class="form-group">
                           <?php echo $item->renderError(); ?>
                           <?php echo $item->renderLabel(null, array("class" => "col-sm-3 control-label")); ?>
                           <div class="<?php if(get_class($item->getWidget()) == "bsWidgetFormInputFloat"): ?>col-sm-2<?php else: ?>col-sm-4<?php endif;?>">
                                <?php $unite = $subvention->infos->get($categorie)->getInfosSchemaItem($key, "unite") ?>
                                <?php if($unite): ?><div class="input-group"><?php endif ?>
                                <?php echo $item->render(); ?>
                                <?php if($unite): ?>
                                    <span class="input-group-addon"><?php echo $unite; ?></span>
                                    </div>
                                <?php endif ?>
                           </div>
                           <div class="col-sm-4">
                               <?php echo $item->renderHelp(); ?>
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
    </form>

</div>
