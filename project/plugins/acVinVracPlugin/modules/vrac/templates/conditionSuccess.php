<?php include_partial('vrac/etapes', array('vrac' => $form->getObject(), 'compte' => $compte, 'actif' => 3, 'urlsoussigne' => null, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<div class="page-header">
    <h2>Conditions</h2>
</div>

<form action="" method="post" class="form-horizontal">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?php echo $form['type_contrat']->renderError(); ?>
                <?php echo $form['type_contrat']->renderLabel("Type de contrat :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['type_contrat']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <?php if (!$vrac->isTeledeclare()): ?>
                <div class="form-group">
                    <?php echo $form['prix_variable']->renderError(); ?>
                    <?php echo $form['prix_variable']->renderLabel("Partie de prix variable ?", array('class' => 'col-sm-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo $form['prix_variable']->render(array('class' => 'form-control')); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$isTeledeclarationMode) : ?>
                <div class="form-group">
                    <?php echo $form['part_variable']->renderError(); ?>
                    <?php echo $form['part_variable']->renderLabel("Part du prix variable sur la quantité :", array('class' => 'col-sm-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo $form['part_variable']->render(array('class' => 'form-control')); ?>
                    </div>
                </div>

                <?php if(isset($form['cvo_nature']) || isset($form['cvo_repartition'])): ?>
                <div class="form-group">
                    <?php echo $form['cvo_nature']->renderError(); ?>
                    <?php echo $form['cvo_nature']->renderLabel("Nature de la transaction :", array('class' => 'col-sm-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo $form['cvo_nature']->render(array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form['cvo_repartition']->renderError(); ?>
                    <?php echo $form['cvo_repartition']->renderLabel("Répartition de la CVO :", array('class' => 'col-sm-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo $form['cvo_repartition']->render(array('class' => 'form-control')); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (isset($form['date_signature']) && isset($form['date_campagne'])): ?>
                <div class="form-group">
                    <?php echo $form['date_signature']->renderError(); ?>
                    <?php echo $form['date_signature']->renderLabel("Date de signature :", array('class' => 'col-sm-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo $form['date_signature']->render(array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form['date_campagne']->renderError(); ?>
                    <?php echo $form['date_campagne']->renderLabel("Date de campagne :", array('class' => 'col-sm-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo $form['date_campagne']->render(array('class' => 'form-control')); ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($form['enlevement_date']) && isset($form['enlevement_frais_garde'])): ?>
            <div class="form-group">
                <?php echo $form['enlevement_date']->renderError(); ?>
                <?php echo $form['enlevement_date']->renderLabel(null, array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['enlevement_date']->render(array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form['enlevement_frais_garde']->renderError(); ?>
                <?php echo $form['enlevement_frais_garde']->renderLabel(null, array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['enlevement_frais_garde']->render(array('class' => 'form-control')); ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!$isTeledeclarationMode) : ?>
            <div class="form-group">
                <?php echo $form['commentaire']->renderError(); ?>
                <?php echo $form['commentaire']->renderLabel(null, array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['commentaire']->render(array('class' => 'form-control')); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4 text-left">
            <a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-default">Etape précédente</a>
        </div>
        <div class="col-xs-4 text-center">
            <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
                <a class="btn btn-default" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>">Supprimer le brouillon</a>
            <?php endif; ?>  
        </div>
        <div class="col-xs-4 text-right">
            <button type="submit" class="btn btn-default">Étape suivante</button>
        </div>
    </div>
</form>

<section id="principal" style="display: none">
    <?php //include_partial('headerVrac', array('vrac' => $form->getObject(), 'compte' => $compte, 'actif' => 3,'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
    <div id="contenu_etape"> 
        <form id="vrac_condition" method="post" action="<?php echo url_for('vrac_condition', $vrac) ?>">  
            <?php echo $form->renderHiddenFields() ?>
            <?php echo $form->renderGlobalErrors() ?>
            <div id="condition">
                <!--  Affichage du type de contrat (si standard la suite n'est pas affiché JS)  -->
                <div id="type_contrat" class="section_label_maj">
                    <?php echo $form['type_contrat']->renderError() ?>        
                    <?php echo $form['type_contrat']->renderLabel() ?>
                    <?php echo $form['type_contrat']->render() ?>
                </div>
                <!--  Affichage de la présence de la part variable du contrat (si non la suite n'est pas affiché JS) -->
                <?php if (!$vrac->isTeledeclare()): ?>
                    <div id="prix_isVariable" class="section_label_maj" <?php echo ($displayPartiePrixVariable) ? '' : 'style="display:none;"'; ?>>
                        <?php echo $form['prix_variable']->renderError() ?>        
                        <?php echo $form['prix_variable']->renderLabel() ?> 
                        <?php echo $form['prix_variable']->render() ?>        
                    </div>
                <?php else: ?>
                    <input id="vrac_prix_variable" type="hidden" value="0" name="vrac[prix_variable]"/>
                <?php endif; ?>
                <?php if (!$isTeledeclarationMode) : ?>
                    <!--  Affiché si et seulement si type de contrat = 'pluriannuel' et partie de prix variable = 'Oui' -->
                    <div id="vrac_marche_prixVariable">
                        <?php
                        include_partial('condition_prixVariable', array('form' => $form, 'displayPrixVariable' => $displayPrixVariable));
                        ?>
                    </div>
                    <?php if (isset($form['date_signature']) && isset($form['date_campagne'])): ?>
                        <div class="section_label_maj">
                            <label>Dates</label>
                            <div class="bloc_form">
                                <!--  Affichage de la date de signature -->
                                <div id="date_signature" class="ligne_form champ_datepicker">
                                    <?php echo $form['date_signature']->renderError() ?>        
                                    <?php echo $form['date_signature']->renderLabel() ?>
                                    <?php echo $form['date_signature']->render() ?>   
                                    <span class="infos">(Date figurant sur le contrat)</span>
                                </div>
                                <!--  Affichage de la date de statistique -->
                                <div id="date_campagne" class="ligne_form ligne_form_alt champ_datepicker">
                                    <?php echo $form['date_campagne']->renderError() ?>        
                                    <?php echo $form['date_campagne']->renderLabel() ?>
                                    <?php echo $form['date_campagne']->render() ?>  
                                    <span class="infos">(Modifiable ultérieurement)</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($isTeledeclarationMode) : ?>
                    <?php if (isset($form['enlevement_date']) && isset($form['enlevement_frais_garde'])): ?>
                        <div class="section_label_maj condition_enlevement">
                            <label>Conditions&nbsp;d'enlévement (facultatif)</label>
                            <div class="bloc_form">
                                <div class="ligne_form champ_datepicker">
                                    <?php echo $form['enlevement_date']->renderError() ?>        
                                    <?php echo $form['enlevement_date']->renderLabel() ?>
                                    <?php echo $form['enlevement_date']->render() ?>
                                </div>
                                <div class="ligne_form ligne_form_alt">
                                    <?php echo $form['enlevement_frais_garde']->renderError() ?>        
                                    <?php echo $form['enlevement_frais_garde']->renderLabel() ?>
                                    <?php echo $form['enlevement_frais_garde']->render(array('class' => 'num_float')) ?>
                                    <span>€/hl</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!$isTeledeclarationMode) : ?>
                    <div id="commentaire" class="section_label_maj">
                        <?php echo $form['commentaire']->renderLabel() ?>
                        <div class="bloc_form">
                            <?php echo $form['commentaire']->renderError() ?>       
                            <?php echo $form['commentaire']->render() ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="btn_etape">
                    <a href="<?php echo url_for('vrac_marche', $vrac); ?>" class="btn_etape_prec"><span>Etape précédente</span></a>
                    <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
                        <a class="lien_contrat_supprimer_brouillon" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>" style="margin-left: 10px">
                            <span>Supprimer Brouillon</span>
                        </a>
                    <?php endif; ?>
                    <button class="btn_etape_suiv" type="submit"><span>Etape Suivante</span></button>
                </div>
            </div>
        </form>
    </div>
    <?php include_partial('popup_notices'); ?> 
</section>
<?php if ($isTeledeclarationMode): ?>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $(".champ_datepicker input").datepicker({
                showOn: "button",
                buttonImage: "/images/pictos/pi_calendrier.png",
                buttonImageOnly: true,
                dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
                monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre"],
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                defaultDate: "<?php echo $form->getDateEnlevementDefaultLabel() ?>"
            });
        });
    </script>
    <?php
    include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour' => true));
else:
    slot('colApplications');
    /*
     * Inclusion du panel de progression d'édition du contrat
     */
    if (!$contratNonSolde)
        include_partial('contrat_progression', array('vrac' => $vrac));

    /*
     * Inclusion du panel pour les contrats similaires
     */
    include_partial('contratsSimilaires', array('vrac' => $vrac));

    /*
     * Inclusion des Contacts
     */
    include_partial('contrat_infos_contact', array('vrac' => $vrac));

    end_slot();
endif;
?>  


