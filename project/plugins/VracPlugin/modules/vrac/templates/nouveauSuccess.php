<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<form id="vrac_soussigne" method="post" action="<?php echo url_for('vrac_soussigne') ?>">   
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
<section id="vendeur">  
    <h1>Sélectionner un vendeur</h1>        
    <section id="vendeur_choice">
        <?php echo $form['vendeur_identifiant']->renderLabel() ?>
        <?php echo $form['vendeur_identifiant']->render() ?>
        
    </section>
  
    <section id="vendeur_informations">
   <?php
   include_partial('vendeurInformations', array('form' => $form));
   ?>
    </section>
</section>
    
<section id="acheteur"> 
    <section id="acheteur_choice">
        <?php echo $form['acheteur_identifiant']->renderLabel() ?>
        <?php echo $form['acheteur_identifiant']->render() ?>
    </section>
    <section id="acheteur_informations">
    <?php
    include_partial('acheteurInformations', array('form' => $form));
    ?>
    </section>
</section>

<section id="mandataire"> 
    <section id="mandataire_choice">
        <?php echo $form['mandataire_identifiant']->renderLabel() ?>
        <?php echo $form['mandataire_identifiant']->render() ?>
        
    </section>
    <section id="mandataire_informations">
    <?php
    include_partial('mandataireInformations', array('form' => $form));
    ?>    
    </section>
</section>
     <div class="btnValidation">
    	<span>&nbsp;</span>
        <input class="btn_valider" type="submit" value="Etape Suivante" />
    </div>

</form>
