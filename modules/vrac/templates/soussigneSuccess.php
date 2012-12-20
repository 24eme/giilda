<?php
/* Fichier : soussigneSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/nouveau-soussigne
 * Formulaire d'enregistrement de la partie soussigne des contrats (modification de contrat)
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
if ($nouveau) {
    ?>
    <script type="text/javascript">
        $(document).ready(function() 
        {
            init_ajax_nouveau();        
        });                        
    </script>
    <?php
} else {
    $numero_contrat = $form->getObject()->numero_contrat;
    ?>
    <script type="text/javascript">
        $(document).ready(function() 
        {
            ajaxifyAutocompleteGet('getInfos',{autocomplete : '#vendeur_choice','numero_contrat' : '<?php echo $numero_contrat; ?>'},'#vendeur_informations');        
            ajaxifyAutocompleteGet('getInfos',{autocomplete : '#acheteur_choice','numero_contrat' : '<?php echo $numero_contrat; ?>'},'#acheteur_informations');
            ajaxifyAutocompleteGet('getInfos',{autocomplete : '#mandataire_choice','numero_contrat' : '<?php echo $numero_contrat; ?>'},'#mandataire_informations');
            majMandatairePanel();
            //$('#vrac_vendeur_famille_viticulteur').attr('checked','checked');
            //$('#vrac_acheteur_famille_negociant').attr('checked','checked');
        });
    </script>
    <?php
}
?>
<section id="principal">
<?php include_partial('headerVrac', array('vrac' => $form->getObject(), 'actif' => 1)); ?>
    <div id="contenu_etape">
        <form id="vrac_soussigne" method="post" action="<?php echo ($form->getObject()->isNew()) ? url_for('vrac_nouveau') : url_for('vrac_soussigne', $vrac); ?>">   
    <?php echo $form->renderHiddenFields() ?>
<?php echo $form->renderGlobalErrors() ?>

            <?php echo $form['vendeur_identifiant']->renderError(); ?>
            <div id="vendeur">   
                <!--  Affichage des vendeurs disponibles  -->
                <div id="vendeur_choice" class="section_label_maj">
<?php echo $form['vendeur_identifiant']->renderLabel() ?>
<?php echo $form['vendeur_identifiant']->render() ?>
                </div>

                <!--  Affichage des informations sur le vendeur sélectionné AJAXIFIED -->
                <div id="vendeur_informations">
<?php
$vendeurArray = array();
$vendeurArray['vendeur'] = $form->vendeur;
$vendeurArray['vendeur'] = ($nouveau) ? $vendeurArray['vendeur'] : $form->getObject()->getVendeurObject();
include_partial('vendeurInformations', $vendeurArray);
?>
                </div>
                <div class="btnModification">
                    <a id="vendeur_annulation_btn" class="btn_majeur btn_annuler" style="display: none;">Retour</a>
                    <a id="vendeur_modification_btn" class="btn_majeur btn_modifier">Modifier</a>
                </div>
            </div>
<?php echo $form['acheteur_identifiant']->renderError(); ?>
            <!--  Affichage des acheteurs disponibles  -->
            <div id="acheteur"> 
                <div id="acheteur_choice" class="section_label_maj">
<?php echo $form['acheteur_identifiant']->renderLabel() ?>
<?php echo $form['acheteur_identifiant']->render() ?>
                </div>

                <!--  Affichage des informations sur l'acheteur sélectionné AJAXIFIED -->
                <div id="acheteur_informations">
<?php
$acheteurArray = array();
$acheteurArray['acheteur'] = $form->acheteur;
$acheteurArray['acheteur'] = ($nouveau) ? $acheteurArray['acheteur'] : $form->getObject()->getAcheteurObject();
include_partial('acheteurInformations', $acheteurArray);
?>
                </div>
                <div class="btnModification">
                    <a id="acheteur_annulation_btn" class="btn_majeur btn_annuler" style="display: none;">Retour</a>
                    <a id="acheteur_modification_btn" class="btn_majeur btn_modifier">Modifier</a>
                </div>
            </div>

            <div id="interne">            
<?php echo $form['interne']->render(); ?>
<?php echo $form['interne']->renderLabel(); ?>
                <?php echo $form['interne']->renderError(); ?>
            </div>

            <!--  Affichage des mandataires disponibles  -->

            <div id="has_mandataire">            
<?php echo $form['mandataire_exist']->render(); ?>
<?php echo $form['mandataire_exist']->renderLabel(); ?>
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
                    <?php echo $form['mandataire_identifiant']->render() ?>
                </div>

                <!--  Affichage des informations sur le mandataire sélectionné AJAXIFIED -->
                <div id="mandataire_informations">
<?php
$mandataireArray = array();
$mandataireArray['mandataire'] = $form->mandataire;
if (!$nouveau)
    $mandataireArray['mandataire'] = (!$hasmandataire) ? $mandataireArray['mandataire'] : $form->getObject()->getMandataireObject();
include_partial('mandataireInformations', $mandataireArray);
?>    
                </div>
                <div class="btnModification">
                    <a id="mandataire_annulation_btn" class="btn_majeur btn_annuler" style="display: none;" href="#">Retour</a>
                    <a id="mandataire_modification_btn" class="btn_majeur">Modifier</a>
                </div>
            </div>

            <div class="btn_etape" id="ligne_btn">
<?php if ($nouveau): ?>
                    <a href="<?php echo url_for('vrac'); ?>" class="btn_majeur btn_annuler"><span>Annuler la saisie</span></a>
                <?php endif; ?>

                <button id="btn_soussigne_submit" class="btn_etape_suiv" type="submit"><span>Etape Suivante</span></button>
            </div>

        </form>
    </div>
</section>
    
<?php
slot('colApplications');
/*
 * Inclusion du panel de progression d'édition du contrat
 */
if (!$contratNonSolde)
    include_partial('contrat_progression', array('vrac' => $vrac));

/*
 * Inclusion des Contacts
 */
include_partial('contrat_infos_contact', array('vrac' => $vrac));

end_slot();
?>
 