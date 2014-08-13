<?php
?>
<section id="principal">

    

    <form id="vrac_choix_etablissement" method="post" action="<?php echo url_for('vrac_societe_choix_etablissement', array('identifiant' => $societe->identifiant)) ?>"> 
        <h2 class="titre_societe">
            Espace de <?php echo $societe->raison_sociale; ?>
        </h2>
        
        <p class="titre_section">Votre société possède plusieurs établissements. Veuillez renseigner l'établissement en charge de ce contrat.</p>
        <br/>
         <div id="condition"class="fond" >
            <div class="bloc_form bloc_form_condensed">
         <?php echo $form->renderHiddenFields() ?>
         <?php echo $form->renderGlobalErrors() ?>
                
            <div id="type_contrat" class="ligne_form">
                <?php echo $form['etablissementChoice']->renderError() ?>        
                <?php echo $form['etablissementChoice']->renderLabel() ?>
                <?php echo $form['etablissementChoice']->render() ?>
            </div>            
         </div>
             
             <div class="ligne_btn">
                    <a class="btn_orange btn_majeur" style="float: left;" href="<?php echo url_for('vrac_societe', array('identifiant' => $etablissementPrincipal->identifiant)); ?>">Retourner à l'espace contrats</a>
                    <button class="btn_etape_suiv" style="cursor: pointer; float: right;" type="submit"><span>Nouveau Contrat</span></button>
         
        </div>
         </div>
    </form>   

</section>

<?php

include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));

?>