<?php
/* Fichier : marcheSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/marche
 * Formulaire d'enregistrement de la partie marche d'un contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : ${date}
 */
?>

<section id="contenu">
    <?php include_partial('etapes', array('vrac' => $form->getObject(), 'pourcentage' => '50', 'actif' => 2)); ?>
<form id="vrac_marche" method="post" action="<?php echo url_for('vrac_marche',$vrac) ?>">    
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
<section id="marche">
    
    <!--  Affichage des l'option original  -->
    <section id="original" class="original">
    <?php echo $form['original']->renderError(); ?>
        <strong> <?php echo $form['original']->renderLabel() ?> </strong>
        <?php echo $form['original']->render() ?>        
    </section>

    <!--  Affichage des transactions disponibles  -->
    <section id="type_transaction" class="type_transaction">
    <?php echo $form['type_transaction']->renderError(); ?>
        <strong> <?php echo $form['type_transaction']->renderLabel() ?> </strong>
        <?php echo $form['type_transaction']->render() ?>        
    </section>
  
<!--  Affichage des produits, des labels et du stock disponible  -->
    <section id="vrac_marche_produitLabel">
        <?php
       include_partial('marche_produitLabel', array('form' => $form));
       ?>
    </section>

<!--  Affichage des volumes et des prix correspondant  -->
    <section id="vrac_marche_volumePrix">
    <?php
    include_partial('marche_volumePrix', array('form' => $form));
    ?>
    </section>

<br>
</section>
     <div id="btn_etape_dr">
       
        <a href="<?php echo url_for('vrac_soussigne', $vrac) ?>" class="btn_prec">
            <span>Précédent</span>
        </a> 
        <div class="btnValidation">
            <span>&nbsp;</span>
            <input class="btn_valider" type="submit" value="Etape Suivante" />
        </div>
     </div>
</form>
    
</section>
    
