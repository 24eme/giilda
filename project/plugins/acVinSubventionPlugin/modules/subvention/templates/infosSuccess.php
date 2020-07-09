<ol class="breadcrumb">
    <li class="active"><a href="<?php echo "#" ?>">Subvention</a></li>
</ol>

<h2><?php echo $subvention->declarant->raison_sociale ?> <small>(<?php echo $subvention->declarant->siret ?>)</small></h2>

<form class="form-horizontal" method="POST" action="">

    <?php echo $form->renderGlobalErrors(); ?>
    <?php echo $form->renderHiddenFields(); ?>

    <?php foreach($form as $categorie => $items): ?>
        <?php if(!$items instanceof sfFormFieldSchema): continue; endif; ?>
        <h3><?php echo $categorie  ?></h3>

        <?php foreach($items as $key => $item): ?>
            <?php if($item instanceof sfFormFieldSchema): ?>
                <div class="row">
                    <div class="col-xs-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="col-xs-8">Gamme de produit</th>
                                    <th class="col-xs-8">Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($item as $subItem): ?>
                                <tr>
                                    <?php foreach($subItem as $field): ?>
                                    <td>
                                    <?php if($field->renderHelp()): ?><div class="input-group"><?php endif; ?>
                                       <?php echo $field->render(); ?>
                                       <?php echo $field->renderHelp() ?>
                                    <?php if($field->renderHelp()): ?></div><?php endif; ?>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
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
            <?php endif; ?>
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
