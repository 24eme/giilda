<ol class="breadcrumb">
    <li class="active"><a href="<?php echo "#" ?>">Subvention</a></li>
</ol>

<form class="form-horizontal" method="POST" action="">

    <?php echo $form->renderGlobalErrors(); ?>
    <?php echo $form->renderHiddenFields(); ?>

    <?php foreach($form as $categorie => $items): ?>
        <?php if(!$items instanceof sfFormFieldSchema): continue; endif; ?>
        <h3><?php echo $categorie  ?></h3>
        <?php foreach($items as $item): ?>
            <div class="form-group">
               <?php echo $item->renderError(); ?>
               <?php echo $item->renderLabel(null, array("class" => "col-sm-2 control-label")); ?>
               <div class="col-sm-4">
                 <?php echo $item->render(); ?>
               </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <div class="row">
        <div class="col-xs-6">
            <a class="btn btn-default" href="">Étape précédente</a>
        </div>
        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-success">Étape suivante</button>
        </div>
    </div>
</form>
