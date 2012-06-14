<?php
/* Fichier : conditionSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/condition
 * Formulaire concernant les conditions du contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
 ?>
<div id="contenu">
    <div id="rub_contrats">    
        <section id="principal">
        <?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 3)); ?>
            <section id="contenu_etape"> 
                <form id="vrac_condition" method="post" action="<?php echo url_for('vrac_condition',$vrac) ?>">  
                    <?php echo $form->renderHiddenFields() ?>
                    <?php echo $form->renderGlobalErrors() ?>
                <section id="condition">
                    <!--  Affichage du type de contrat (si standard la suite n'est pas affiché JS)  -->
                    <section id="type_contrat">
                        <?php echo $form['type_contrat']->renderError() ?>        
                        <h2>
                            <?php echo $form['type_contrat']->renderLabel() ?>
                        </h2>
                           <?php echo $form['type_contrat']->render() ?>
                    </section>
                    <!--  Affichage de la présence de la part variable du contrat (si non la suite n'est pas affiché JS) -->
                    <section id="prix_isVariable">
                        <?php echo $form['prix_variable']->renderError() ?>        
                        <h2>  <?php echo $form['prix_variable']->renderLabel() ?> </h2>  
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
                        <?php echo $form['date_signature']->renderError() ?>        
                        <?php echo $form['date_signature']->renderLabel() ?>
                        <?php echo $form['date_signature']->render() ?>        
                    </section>
                    <br>
                    <!--  Affichage de la date de statistique -->
                    <section id="date_stats">
                        <?php echo $form['date_stats']->renderError() ?>        
                        <?php echo $form['date_stats']->renderLabel() ?>
                        <?php echo $form['date_stats']->render() ?>        
                    </section>
                    <br>
                </section>
                    <div id="btn_etape_dr">

                         <a href="<?php echo url_for('vrac_marche', $vrac); ?>" class="btn_majeur btn_annuler">
                            <span>Précédent</span>
                        </a> 
                        <div class="btnValidation">
                            <span>&nbsp;</span>
                            <button class="btn_majeur btn_etape_suiv" type="submit">Etape Suivante</button>
         
                        </div>
                    </div>
                </form>
            </section>
        </section>
        <?php include_partial('colonne', array('vrac' => $form->getObject())); ?>
    </div>          
</div>