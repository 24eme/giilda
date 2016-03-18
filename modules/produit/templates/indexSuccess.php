<ol class="breadcrumb">
    <li class="active">
        <a href="<?php echo url_for("produits") ?>">Produits</a>
    </li>
</ol>

<?php slot('global_css_class', 'no_right_col')?>

<a href="<?php echo url_for('produit_nouveau') ?>" class="btn btn-default pull-right"><span class="glyphicon glyphicon-plus"></span> Ajouter un produit</a>

<h2>Produits <span class="text-muted"> / <?php echo $config->_id ?><small style="font-size: 10px;">@<?php echo $config->_rev ?></small></span></h2>

<table class="table table-condensed table-striped table-bordered">
    <thead>
        <?php include_partial('produit/itemHeader') ?>
    </thead>
    <tbody>
    <?php foreach($produits as $produit): ?>
        <?php include_component('produit', 'item', array('produit' => $produit, 'date' => $date, 'supprimable' => false)) ?>
    <?php endforeach; ?>
    </tbody>
</table>

<a name="mouvements"></a>
<h2>Configuration des mouvements</h2>
<table class="table table-condensed table-striped table-bordered table-hover">
    <tbody>
        <?php foreach ($config->declaration->detail as $details): ?>
            <?php foreach($config->declaration->detail->getDetailsSorted($details) as $detail): ?>
            <tr>
                <td><span class="<?php if($detail->mouvement_coefficient == -1): ?>text-danger<?php endif; ?><?php if($detail->mouvement_coefficient == 1): ?>text-success<?php endif; ?>"><?php echo $detail->getParent()->getKey() ?></span></td>
                <td><?php echo $detail->getLibelle() ?> <small class="text-muted"><?php echo $detail->getKey() ?></small></td>
                <td><?php if($detail->isFavoris()): ?><span class="glyphicon glyphicon-star"></span><?php endif; ?></td>
                <td><?php if($detail->facturable): ?>CVO<?php endif; ?></td>
                <td><?php if($detail->taxable_douane): ?>DOUANE<?php endif; ?></td>
                <td><?php if($detail->recolte): ?>RECOLTE<?php endif; ?></td>
                <td><?php if($detail->revendique): ?>REVEND.<?php endif; ?></td>
                <td><?php if($detail->details): ?>DETAILS<?php endif; ?></td>
                <td><?php if($detail->vrac): ?>VRAC<?php endif; ?></td>
                <td><?php if($detail->readable): ?>R<?php endif; ?><?php if($detail->writable): ?>W<?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>
