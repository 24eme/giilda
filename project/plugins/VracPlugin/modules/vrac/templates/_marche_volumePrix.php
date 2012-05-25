<section id="stock">
        <?php 
        echo "500hl"; //recuperer la qt dans les DRM
        ?>
</section>

<section id="volume">
        <?php echo $form['bouteilles_quantite']->renderLabel() ?>
        <?php echo $form['bouteilles_quantite']->render() ?>
</section>

<section id="contenance">
        <?php echo $form['bouteilles_contenance']->renderLabel() ?>
        <?php echo $form['bouteilles_contenance']->render() ?>
</section>

<section id="prixUnitaire">
        <?php echo $form['prix_unitaire']->renderLabel() ?>
        <?php echo $form['prix_unitaire']->render() ?>
</section>
                

<section id="prixTotal">
        <?php echo $form['prix_total']->renderLabel() ?>
        <?php echo $form['prix_total']->render() ?>
</section>
                