<ol class="breadcrumb">
    <li class="active">
        <a href="<?php echo url_for("produits") ?>">Produits</a>
    </li>
</ol>

<?php include_component('produit', 'index', array('id' => $id, 'rev' => $rev, 'date' => $date));