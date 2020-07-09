<ol class="breadcrumb">
    <li class="active"><a href="<?php echo "#" ?>">Subvention</a></li>
</ol>


<form class="form-horizontal" method="POST" action="">

    <?php echo $form->renderGlobalErrors(); ?>
    <?php echo $form->renderHiddenFields(); ?>

    <h2><?php echo $subvention->declarant->raison_sociale ?></h2>

    <div class="form-group">
       <label class="col-sm-2 control-label">Famille</label>
       <div class="col-sm-4">
            <p class="form-control-static"><?php echo EtablissementFamilles::getFamilleLibelle($subvention->declarant->famille) ?></p>
       </div>
    </div>
    <div class="form-group">
       <label class="col-sm-2 control-label">SIRET</label>
       <div class="col-sm-4">
            <p class="form-control-static"><?php echo $subvention->declarant->siret ?></p>
       </div>
    </div>
    <div class="form-group">
       <label class="col-sm-2 control-label">Adresse</label>
       <div class="col-sm-4">
            <p class="form-control-static"><?php echo $subvention->declarant->adresse ?></p>
       </div>
    </div>
    <div class="form-group">
       <label class="col-sm-2 control-label">Code postal</label>
       <div class="col-sm-4">
            <p class="form-control-static"><?php echo $subvention->declarant->code_postal ?></p>
       </div>
    </div>
    <div class="form-group">
       <label class="col-sm-2 control-label">Commune</label>
       <div class="col-sm-4">
            <p class="form-control-static"><?php echo $subvention->declarant->commune ?></p>
       </div>
    </div>

    <?php foreach($form as $categorie => $items): ?>
        <?php if(!$items instanceof sfFormFieldSchema): continue; endif; ?>
        <h3><?php echo $categorie  ?></h3>

        <?php foreach($items as $key => $item): ?>
            <div class="form-group">
               <?php echo $item->renderError(); ?>
               <?php echo $item->renderLabel(null, array("class" => "col-sm-2 control-label")); ?>
               <div class="col-sm-4">
                    <?php if($item->renderHelp()): ?><div class="input-group"><?php endif; ?>
                       <?php echo $item->render(); ?>
                       <?php echo $item->renderHelp() ?>
                    <?php if($item->renderHelp()): ?></div><?php endif; ?>
               </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <div class="row">
        <div class="col-xs-6">
            <a class="btn btn-default" tabindex="-1" href="">Étape précédente</a>
        </div>
        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-success">Étape suivante</button>
        </div>
    </div>
</form>
