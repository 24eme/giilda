<?php slot('global_css_class', 'no_right_col')?>

<section id="principal"  class="produit" style="padding-right: 5px; padding-left: 5px;">
    <p id="fil_ariane"><strong>Page d'accueil</strong></p>
    <a style="float:right" href="<?php echo url_for('produit_nouveau') ?>" class="btn_majeur btn_nouveau">Ajouter un produit</a>

    <h2>Produits <span style="color: #878787;"> / <?php echo $config->_id ?><small style="font-size: 10px;">@<?php echo $config->_rev ?></small></span></h2>

    <table class="table_recap table_compact">
        <thead>
            <?php include_partial('produit/itemHeader') ?>
        </thead>
        <tbody>
        <?php foreach($produits as $produit): ?>
            <?php include_component('produit', 'item', array('produit' => $produit, 'date' => $date, 'supprimable' => false)) ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

