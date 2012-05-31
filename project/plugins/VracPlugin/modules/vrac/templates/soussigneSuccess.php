<?php
/* Fichier : soussigneSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/nouveau-soussigne
 * Formulaire d'enregistrement de la partie soussigne des contrats (modification de contrat)
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
$nouveau = is_null($form->getObject()->numero_contrat);
?>
<script type="text/javascript">
    $(document).ready(function() {
        ajaxifyAutocompleteGet('vendeurInformations','#vendeur_choice','#vendeur_informations');
        ajaxifyAutocompleteGet('acheteurInformations','#acheteur_choice','#acheteur_informations'); 
        ajaxifyAutocompleteGet('mandataireInformations','#mandataire_choice','#mandataire_informations');
        
            $('#vendeur_modification_btn').click(function()
            {
                $.get('vendeurModification', {id : $('#vrac_vendeur_identifiant').val()},
                    function(data){
                        $('#vendeur_informations').html(data);
                });
            });
    });                        
</script>

<section id="contenu">
<form id="vrac_soussigne" method="post" action="<?php if ($form->getObject()->isNew()) echo url_for('vrac_nouveau');  else echo url_for('vrac_soussigne',$vrac); ?>">   
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
<br>  
    
<section id="vendeur">
    <!--  Affichage des vendeurs disponibles  -->
    <section id="vendeur_choice">
        <?php echo $form['vendeur_identifiant']->renderError(); ?>
        <strong> <?php echo $form['vendeur_identifiant']->renderLabel() ?></strong>
       <?php echo $form['vendeur_identifiant']->render() ?> 
    </section>
  
    <!--  Affichage des informations sur le vendeur sélectionné AJAXIFIED -->
    <section id="vendeur_informations" data-action="">
   <?php
   include_partial('vendeurInformations', array('vendeur' => ($nouveau)? null : $form->getObject()->getVendeurObject()));
   ?>
        <?php 
        //echo url_for('vrac_vendeur');
        ?>
    </section>
    <div class="btnModification">
        <input type="button" id="vendeur_modification_btn" class="btn_modifier" value="Modifier" />            <!--
        <a href="<?php //echo url_for('vrac_vendeurModification', $vrac) ?>" class="btn_modifier">
            <span>Modifier</span>
        </a> -->
    </div>
</section>
<br>
    
<!--  Affichage des acheteurs disponibles  -->
<section id="acheteur"> 
    <section id="acheteur_choice">
   <?php echo $form['acheteur_identifiant']->renderError(); ?>
        <strong> <?php echo $form['acheteur_identifiant']->renderLabel() ?></strong>
        <?php echo $form['acheteur_identifiant']->render() ?>
    </section>
    <!--  Affichage des informations sur l'acheteur sélectionné AJAXIFIED -->
    <section id="acheteur_informations">
    <?php
    include_partial('acheteurInformations', array('acheteur' => ($nouveau)? null : $form->getObject()->getAcheteurObject()));
    ?>
    </section>
    <div class="btnModification">
       <!--  <a href="<?php //echo url_for('vrac_acheteurModification', $vrac) ?>" class="btn_modifier">
            <span>Modifier</span> -->
        </a> 
    </div>
</section>
<br>
    
<!--  Affichage des mandataires disponibles  -->
<section id="mandataire"> 
    <section id="mandataire_choice">
   <?php echo $form['mandataire_identifiant']->renderError(); ?>
        <strong> <?php echo $form['mandataire_identifiant']->renderLabel() ?></strong>
        <?php echo $form['mandataire_identifiant']->render() ?>
        
    </section>
    <!--  Affichage des informations sur le mandataire sélectionné AJAXIFIED -->
    <section id="mandataire_informations">
    <?php
    include_partial('mandataireInformations', array('mandataire' => ($nouveau)? null : $form->getObject()->getMandataireObject()));
    ?>    
    </section>
    <div class="btnModification">
        <a href="<?php echo url_for('vrac_mandataireModification', $vrac) ?>" class="btn_modifier">
            <span>Modifier</span>
        </a> 
    </div>
</section>

<br>

    <div id="btn_etape_dr">
        <div class="btnValidation">
            <span>&nbsp;</span>
            <input class="btn_valider" type="submit" value="Etape Suivante" />
        </div>
    </div>
</form>
</section>
