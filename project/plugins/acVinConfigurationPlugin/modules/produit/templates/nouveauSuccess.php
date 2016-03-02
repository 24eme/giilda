<ol class="breadcrumb">
    <li class="visited">
        <a href="<?php echo url_for("produits") ?>">Produits</a>
    </li>
    <li class="active">
        <a href="">Ajout d'un produit</a>
    </li>
</ol>

<div class="col-md-8 col-md-offset-2">
<h2>Noeuds</h2>
<?php include_partial('produit/formNouveau', array('form' => $form)) ?>
</div>