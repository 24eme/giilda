<?php
/* Fichier : conditionSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/condition
 * Formulaire concernant les conditions du contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
 ?>
<section id="contenu">
<form id="vrac_condition" method="post" action="<?php echo url_for('vrac_condition',$vrac) ?>">    
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
<section id="condition">
    
    <br>
    <!--  Affichage du type de contrat (si standard la suite n'est pas affiché JS)  -->
    <section id="type_contrat">
        <strong>  <?php echo $form['type_contrat']->renderLabel() ?> </strong>
        <?php echo $form['type_contrat']->render() ?>        
    </section>
    <br>
    <!--  Affichage de la présence de la part variable du contrat (si non la suite n'est pas affiché JS) -->
    <section id="prix_variable">
        <strong>  <?php echo $form['prix_variable']->renderLabel() ?> </strong>  
        <?php echo $form['prix_variable']->render() ?>        
    </section>
  
    <!--  Affiché si et seulement si type de contrat = 'pluriannuel' et partie de prix variable = 'Oui' -->
    <section id="vrac_marche_prixVariable">
        <?php
       include_partial('condition_prixVariable', array('form' => $form));
       ?>
    </section>
    
    <br>
    <h2>Dates</h2>
    <br>
    <!--  Affichage de la date de signature -->
    <section id="date_signature">
        <?php echo $form['date_signature']->renderLabel() ?>
        <?php echo $form['date_signature']->render() ?>        
    </section>
    <br>
    <!--  Affichage de la date de statistique -->
    <section id="date_stats">
        <?php echo $form['date_stats']->renderLabel() ?>
        <?php echo $form['date_stats']->render() ?>        
    </section>
    <br>
</section>
    <input class="btn_valider" type="submit" value="Etape Suivante" />
</form>
</section>