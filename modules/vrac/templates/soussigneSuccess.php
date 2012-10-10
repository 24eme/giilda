<?php
/* Fichier : soussigneSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/nouveau-soussigne
 * Formulaire d'enregistrement de la partie soussigne des contrats (modification de contrat)
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
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
        //$('#vrac_vendeur_famille_viticulteur').attr('checked','checked');
        //$('#vrac_acheteur_famille_negociant').attr('checked','checked');
    });
</script>
<?php
}
?>
<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">
        <?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 1)); ?>
            <div id="contenu_etape">
                <form id="vrac_soussigne" method="post" action="<?php echo ($form->getObject()->isNew())? url_for('vrac_nouveau') : url_for('vrac_soussigne',$vrac); ?>">   
                    <?php echo $form->renderHiddenFields() ?>
                    <?php echo $form->renderGlobalErrors() ?>
                    
                    <?php echo $form['vendeur_identifiant']->renderError(); ?>
                <div id="vendeur">   
                    <!--  Affichage des vendeurs disponibles  -->
                    <div id="vendeur_choice" class="section_label_maj">
                       
                        <?php echo $form['vendeur_identifiant']->renderLabel() ?>

                        <div id="vendeur_choice"  class="f_right">
                            <?php echo $form['vendeur_identifiant']->render() ?> 
                        </div>
                    </div>

                    
                    <br>
                    
                    <!--  Affichage des informations sur le vendeur sélectionné AJAXIFIED -->
                    <div id="vendeur_informations" class="section_label_maj">
                        <?php   
                        $vendeurArray = array();
                        $vendeurArray['vendeur'] = $form->vendeur;
                        $vendeurArray['vendeur'] = ($nouveau)? $vendeurArray['vendeur'] : $form->getObject()->getVendeurObject();   
                        include_partial('vendeurInformations', $vendeurArray);    
                        ?>
                    </div>
                    <div class="btnModification">
                        <div id="vendeur_annulation_div" class="f_left" style="display: none;">
                            <a id="vendeur_annulation_btn" class="btn_majeur btn_annuler" style="cursor: pointer;">Retour</a>
                        </div>
                        <div class="modification_changement">
                            <a id="vendeur_modification_btn" class="btn_majeur btn_modifier">Modifier</a>
                        </div>
                    </div>
                </div>
                <br />
                
                <?php echo $form['acheteur_identifiant']->renderError(); ?>
                <!--  Affichage des acheteurs disponibles  -->
                <div id="acheteur"> 
                    <div id="acheteur_choice" class="section_label_maj">
                        <?php echo $form['acheteur_identifiant']->renderLabel() ?>

                        <div id="acheteur_choice" class="f_right">
                            <?php echo $form['acheteur_identifiant']->render() ?>
                        </div>
                    </div>

                    <br>
                    
                    <!--  Affichage des informations sur l'acheteur sélectionné AJAXIFIED -->
                    <div id="acheteur_informations" class="section_label_maj">
                        <?php
                        $acheteurArray = array();
                        $acheteurArray['acheteur'] = $form->acheteur;
                        $acheteurArray['acheteur'] = ($nouveau)? $acheteurArray['acheteur'] : $form->getObject()->getAcheteurObject();    
                        include_partial('acheteurInformations', $acheteurArray);
                        ?>
                    </div>
                    <div class="btnModification">
                        <div id="acheteur_annulation_div" class="f_left" style="display: none;">
                            <a id="acheteur_annulation_btn" class="btn_majeur btn_annuler" style="cursor: pointer;">Retour</a>
                        </div>
                        <div class="modification_changement">
                            <a id="acheteur_modification_btn" class="btn_majeur btn_modifier">Modifier</a>
                        </div>
                    </div>
                </div>
                <br />

                <!--  Affichage des mandataires disponibles  -->

                <div id="has_mandataire" class="section_label_maj">            
                        <?php echo $form['mandataire_exist']->render() ?>
                        <?php echo $form['mandataire_exist']->renderLabel() ?>
                        <?php echo $form['mandataire_exist']->renderError(); ?>
                </div>
                <div id="mandataire">     
                    <div id="mandatant" class="section_label_strong" >
                        <?php echo $form['mandatant']->renderError(); ?>
                        <?php echo $form['mandatant']->renderLabel() ?>
                        <?php echo $form['mandatant']->render() ?>        
                    </div>

                    <div id="mandataire_choice" class="section_label_maj">
                        <?php echo $form['mandataire_identifiant']->renderError(); ?>
                        <?php echo $form['mandataire_identifiant']->renderLabel() ?>
                        <div class="f_right">                            
                            <?php echo $form['mandataire_identifiant']->render() ?>
                        </div>
                    </div>
                    
                    <!--  Affichage des informations sur le mandataire sélectionné AJAXIFIED -->
                    <div id="mandataire_informations" class="section_label_maj">
                        <?php
                        $mandataireArray = array();    
                        $mandataireArray['mandataire'] = $form->mandataire;
                        if(!$nouveau)
                            $mandataireArray['mandataire'] = (!$hasmandataire)? $mandataireArray['mandataire'] : $form->getObject()->getMandataireObject();
                        include_partial('mandataireInformations', $mandataireArray); 
                        ?>    
                    </div>
                    <div class="btnModification">
                        <div id="mandataire_annulation_div" class="f_left" style="display: none;">
                            <a id="mandataire_annulation_btn" class="btn_majeur btn_annuler" style="cursor: pointer;">Retour</a>
                        </div>
                        <div class="modification_changement">
                            <a id="mandataire_modification_btn" class="btn_majeur">Modifier</a>
                        </div>
                    </div>
                </div>

                <br />

                <div id="ligne_btn">
                    <?php if($nouveau): ?>
                        <div class="btnAnnulation">
                            <a href="<?php echo url_for('vrac'); ?>" class="btn_majeur btn_annuler"><span>Annuler la saisie</span></a>
                        </div>
                    <?php endif; ?>
                    <div class="btnValidation">
                        <button class="btn_etape_suiv" type="submit"><span>Etape Suivante</span></button>
                    </div>
                </div>
                
                </form>
            </div>
        </section>
        <aside id="colonne">
            <?php
            /*
            * Inclusion du panel de progression d'édition du contrat
            */
            if(!$contratNonSolde) include_partial('contrat_progression', array('vrac' => $vrac));

            /*
            * Inclusion des Contacts
            */
            include_partial('contrat_infos_contact', array('vrac' => $vrac));

            /*
            * Inclusion de l'aide
            */
            include_partial('contrat_aide', array('vrac' => $vrac));

            ?>
        </aside>
    </div>
</div>
