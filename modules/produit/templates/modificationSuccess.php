<?php slot('global_css_class', 'no_right_col')?>

<section id="principal"  class="produit">
    <h2>Modification du noeud <strong><?php echo $form->getObject()->getTypeNoeud() ?></strong>.</h2>
    <div class="form_contenu">
    <?php include_partial('produit/form', array('form' => $form)) ?>
    </div>
</section>