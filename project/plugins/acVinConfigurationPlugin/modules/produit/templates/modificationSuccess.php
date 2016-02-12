<ol class="breadcrumb">
    <li>
        <a href="<?php echo url_for("produits") ?>">Produits</a>
    </li>
    <li class="active">
        <a href=""><?php echo sprintf("Modification du noeud %s : %s (%s)", $form->getObject()->getTypeNoeud(), $form->getObject()->getLibelle(), $form->getObject()->getKey()) ?></a>
    </li>
</ol>

<h2>Modification du noeud</h2>

<?php include_partial('produit/form', array('form' => $form, 'produit' => $produit)) ?>
