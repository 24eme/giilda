<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<form id="vrac_marche" method="post" action="<?php echo url_for('vrac_marche',$vrac) ?>">    
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
<section id="marche">  
    <h1></h1>        
    <section id="type_transaction">
        <?php echo $form['type_transaction']->renderLabel() ?>
        <?php echo $form['type_transaction']->render() ?>        
    </section>
  
    <section id="vrac_marche_produitLabel">
        <?php
       include_partial('marche_produitLabel', array('form' => $form));
       ?>
    </section>
    <section id="vrac_marche_volumePrix">
    <?php
    include_partial('marche_volumePrix', array('form' => $form));
    ?>
    </section>
</section>
    <input class="btn_valider" type="submit" value="Etape Suivante" />
</form>