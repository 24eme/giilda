<?php slot('global_css_class', 'no_right_col')?>

<section id="principal"  class="produit">
    <h2>Produits &nbsp;<a href="<?php echo url_for('produit_nouveau') ?>" class="btn_ajouter">Ajouter</a></h1>

    <table class="table_recap">
        <thead>
			<?php include_partial('produit/itemHeader') ?>
        </thead>
        <tbody>
    	<?php foreach($produits as $produit): ?>
    		<?php include_component('produit', 'item', array('produit' => $produit, 'supprimable' => false)) ?>
    	<?php endforeach; ?>
    	</tbody>
	</table>
</section>
