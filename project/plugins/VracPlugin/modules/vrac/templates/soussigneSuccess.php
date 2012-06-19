<?php
/* Fichier : soussigneSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/nouveau-soussigne
 * Formulaire d'enregistrement de la partie soussigne des contrats (modification de contrat)
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
$nouveau = is_null($form->getObject()->numero_contrat);
$hasmandataire = !is_null($form->getObject()->mandataire_identifiant);

if($nouveau)
{
?>
<script type="text/javascript">
    $(document).ready(function() 
    {
        init_ajax_nouveau();
    });                        
</script>
<?php
}
else 
{
  $numero_contrat = $form->getObject()->numero_contrat;
?>
<script type="text/javascript">
    $(document).ready(function() 
    {
        ajaxifyAutocompleteGet('getInfos',{autocomplete : '#vendeur_choice','numero_contrat' : '<?php echo $numero_contrat;?>'},'#vendeur_informations');        
        ajaxifyAutocompleteGet('getInfos',{autocomplete : '#acheteur_choice','numero_contrat' : '<?php echo $numero_contrat;?>'},'#acheteur_informations');
        ajaxifyAutocompleteGet('getInfos',{autocomplete : '#mandataire_choice','numero_contrat' : '<?php echo $numero_contrat;?>'},'#mandataire_informations');
        majMandatairePanel();
    });
</script>
<?php
}
?>
<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">
        <?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 1)); ?>
            <section id="contenu_etape">
                <form id="vrac_soussigne" method="post" action="<?php echo ($form->getObject()->isNew())? url_for('vrac_nouveau') : url_for('vrac_soussigne',$vrac); ?>">   
                    <?php echo $form->renderHiddenFields() ?>
                    <?php echo $form->renderGlobalErrors() ?>

                <section id="vendeur">
                    <!--  Affichage des vendeurs disponibles  -->
                    <section id="vendeur_choice">
                        <?php echo $form['vendeur_identifiant']->renderError(); ?>
                        <h2>
                            <strong>
                                 <?php echo $form['vendeur_identifiant']->renderLabel() ?>
                            </strong>
                            <div class="f_right">
                                <?php echo $form['vendeur_identifiant']->render() ?> 
                            </div>
                        </h2>
                    </section>

                    <!--  Affichage des informations sur le vendeur sélectionné AJAXIFIED -->
                    <section id="vendeur_informations">
                    <?php   
                    $vendeurArray = array();
                    $vendeurArray['vendeur'] = $form->vendeur;
                    $vendeurArray['vendeur'] = ($nouveau)? $vendeurArray['vendeur'] : $form->getObject()->getVendeurObject();   
                    include_partial('vendeurInformations', $vendeurArray);    
                    ?>
                    </section>
                    <div class="btnModification f_right">
                        <a id="vendeur_modification_btn" class="btn_majeur btn_orange" style="cursor: pointer;">Modifier</a>
                    </div>
                </section>
                <br>

                <!--  Affichage des acheteurs disponibles  -->
                <section id="acheteur"> 
                    <section id="acheteur_choice">
                        <?php echo $form['acheteur_identifiant']->renderError(); ?>
                        <h2>
                            <strong> <?php echo $form['acheteur_identifiant']->renderLabel() ?></strong>
                            <div class="f_right">
                            <?php echo $form['acheteur_identifiant']->render() ?>
                            </div>
                        </h2>
                    </section>

                    <!--  Affichage des informations sur l'acheteur sélectionné AJAXIFIED -->
                    <section id="acheteur_informations">
                    <?php
                    $acheteurArray = array();
                    $acheteurArray['acheteur'] = $form->acheteur;
                    $acheteurArray['acheteur'] = ($nouveau)? $acheteurArray['acheteur'] : $form->getObject()->getAcheteurObject();    
                    include_partial('acheteurInformations', $acheteurArray);
                    ?>
                    </section>
                    <div class="btnModification f_right">
                        <a id="acheteur_modification_btn" class="btn_majeur btn_orange" style="cursor: pointer;"/>Modifier</a>
                    </div>
                </section>
                <br>

                <!--  Affichage des mandataires disponibles  -->

                <section id="has_mandataire">            
                        <?php echo $form['mandataire_exist']->render() ?>
                        <strong> <?php echo $form['mandataire_exist']->renderLabel() ?></strong>
                        <?php echo $form['mandataire_exist']->renderError(); ?>
                </section>
                <section id="mandataire">     
                    <section id="mandatant">
                    <?php echo $form['mandatant']->renderError(); ?>
                        <strong> <?php echo $form['mandatant']->renderLabel() ?></strong>
                        <?php echo $form['mandatant']->render() ?>        
                    </section>

                    <section id="mandataire_choice">
                        <?php echo $form['mandataire_identifiant']->renderError(); ?>
                        <h2>
                            <strong> <?php echo $form['mandataire_identifiant']->renderLabel() ?></strong>
                            <div class="f_right">                            
                                <?php echo $form['mandataire_identifiant']->render() ?>
                            </div>
                        </h2>

                    </section>
                    <!--  Affichage des informations sur le mandataire sélectionné AJAXIFIED -->
                    <section id="mandataire_informations">
                    <?php
                    $mandataireArray = array();    
                    $mandataireArray['mandataire'] = $form->mandataire;
                    if(!$nouveau)
                        $mandataireArray['mandataire'] = (!$hasmandataire)? $mandataireArray['mandataire'] : $form->getObject()->getMandataireObject();
                    include_partial('mandataireInformations', $mandataireArray); 
                    ?>    
                    </section>
                    <div class="btnModification f_right">
                        <a id="mandataire_modification_btn" class="btn_majeur btn_orange" style="cursor: pointer;">Modifier</a> 
                    </div>
                </section>

                <br>

                    <div id="btn_etape_dr">
                        <?php if($nouveau){ ?>
                        <div class="btnAnnulation">
                                <a href="<?php echo url_for('vrac'); ?>" class="btn_majeur btn_annuler"><span>Annuler la saisie</span></a>
                        </div>
                        <?php } ?>
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
