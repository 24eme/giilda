
<ol class="breadcrumb">
    <li>
        <a href="/produits">Page d'accueil</a>
    </li>
    <li class="active">
        <strong><?php echo sprintf("Modification du noeud %s: %s (%s)", $form->getObject()->getTypeNoeud(), $form->getObject()->getLibelle(), $form->getObject()->getKey()) ?></strong>
    </li>
</ol>

<h2>Modification du noeud</h2>

<?php include_partial('produit/form', array('form' => $form, 'produit' => $produit)) ?>
