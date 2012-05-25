<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<form id="vrac_condition" method="post" action="<?php echo url_for('vrac_condition',$vrac) ?>">    
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
<section id="condition">  
    <h1></h1>        
    <section id="type_contrat">
        <?php echo $form['type_contrat']->renderLabel() ?>
        <?php echo $form['type_contrat']->render() ?>        
    </section>
    <section id="prix_variable">
        <?php echo $form['prix_variable']->renderLabel() ?>
        <?php echo $form['prix_variable']->render() ?>        
    </section>
  
    <section id="vrac_marche_prixVariable">
        <?php
       include_partial('condition_prixVariable', array('form' => $form));
       ?>
    </section>
    
    <section id="date_signature">
        <?php echo $form['date_signature']->renderLabel() ?>
        <?php echo $form['date_signature']->render() ?>        
    </section>
    <section id="date_stats">
        <?php echo $form['date_stats']->renderLabel() ?>
        <?php echo $form['date_stats']->render() ?>        
    </section>
</section>
    <input class="btn_valider" type="submit" value="Etape Suivante" />
</form>