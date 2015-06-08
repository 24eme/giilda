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
                    <?php echo $form['type_contrat']->render(); ?>
                </div>
            </div>

            <?php if (!$vrac->isTeledeclare()): ?>
                <div class="form-group">
                    <?php echo $form['prix_variable']->renderError(); ?>
                    <?php echo $form['prix_variable']->renderLabel("Partie de prix variable ?", array('class' => 'col-sm-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo $form['prix_variable']->render(); ?>
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


