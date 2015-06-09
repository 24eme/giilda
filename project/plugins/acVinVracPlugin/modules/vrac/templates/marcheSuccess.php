<?php use_helper('Float'); ?>
<?php $contratNonSolde = ((!is_null($form->getObject()->valide->statut)) && ($form->getObject()->valide->statut != VracClient::STATUS_CONTRAT_SOLDE)); ?>

<?php include_partial('vrac/etapes', array('vrac' => $form->getObject(), 'compte' => $compte, 'actif' => 2, 'urlsoussigne' => null, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<form action="" method="post" class="form-horizontal">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="row">
        <div class="col-sm-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Produit</h3>
                </div>
                <div class="panel-body">
                    <?php echo $form['produit']->renderError(); ?>
                    <?php echo $form['millesime']->renderError(); ?>
                    <div class="form-group">
                        <div class="col-xs-8 <?php if($form['produit']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['produit']->render(array('class' => 'form-control select2', 'placeholder' => 'Selectionner un produit', 'tabindex'=> '0')); ?>
                        </div>
                        <div class="col-xs-4 <?php if($form['millesime']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['millesime']->render(array('class' => 'form-control select2')); ?>
                        </div>
                    </div>
                    <?php echo $form['categorie_vin']->renderError(); ?>
                    <?php echo $form['domaine']->renderError(); ?>
                    <?php echo $form['label']->renderError(); ?>
                    <div class="form-group">
                        <div class="row bloc_condition" data-condition-cible="#bloc_domaine_notworking">
                            <div class="col-sm-6 <?php if($form['categorie_vin']->hasError()): ?>has-error<?php endif; ?>">
                                <?php echo $form['categorie_vin']->renderLabel("Type :", array("class" => "control-label col-sm-4")); ?>
                                <div class="col-sm-8">
                                    <?php echo $form['categorie_vin']->render(); ?>
                                </div>
                            </div>
                            <div id="bloc_domaine" data-condition-data="DOMAINE" class="bloc_conditionner col-sm-6 <?php if($form['domaine']->hasError()): ?>has-error<?php endif; ?>">
                                <?php echo $form['domaine']->renderLabel("Domaine :", array("class" => "control-label col-sm-4")); ?>
                                <div class="col-sm-8">
                                    <?php echo $form['domaine']->render(array('class' => 'form-control select2', 'placeholder' => 'Déclarer un domaine')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($form['label'])): ?>
                    <?php echo $form['label']->renderError(); ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 <?php if($form['label']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['label']->renderLabel("Label :", array('class' => 'col-sm-4 control-label')); ?>
                            <div class="col-sm-8">
                            <?php echo $form['label']->render(); ?>
                            </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Quantité</h3>
                    </div>
                    <div class="panel-body">
                    
                        <?php if(isset($form['bouteilles_contenance_libelle'])): ?>
                        <?php echo $form['bouteilles_contenance_libelle']->renderError(); ?>
                        <div class="form-group <?php if($form['bouteilles_contenance_libelle']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['bouteilles_contenance_libelle']->renderLabel("Contenance :", array('class' => 'col-sm-4 control-label')); ?>
                            <div class="col-sm-8">
                                <?php echo $form['bouteilles_contenance_libelle']->render(array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <?php endif; ?>
						
						<?php if(isset($form['jus_quantite'])): ?>
                        <?php echo $form['jus_quantite']->renderError(); ?>
                        <div class="form-group <?php if($form['jus_quantite']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['jus_quantite']->renderLabel("Volume proposé :", array('class' => 'col-sm-4 control-label')); ?>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <?php echo $form['jus_quantite']->render(array("class" => "form-control text-right", 'autocomplete' => 'off')); ?>
                                    <span class="input-group-addon">&nbsp;hl</span>
                                </div>
                            </div>
                        </div>
                        <?php endif;
			$unite = 'hl';
			if(isset($form['raisin_quantite'])):
			$unite = 'kg'; ?>
                        <?php echo $form['raisin_quantite']->renderError(); ?>
                        <div class="form-group <?php if($form['raisin_quantite']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['raisin_quantite']->renderLabel("Quantité :", array('class' => 'col-sm-4 control-label')); ?>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <?php echo $form['raisin_quantite']->render(array("class" => "form-control text-right", 'autocomplete' => 'off')); ?>
                                    <span class="input-group-addon">kg</span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Prix</h3>
                    </div>
                    <div class="panel-body">
                        <?php echo $form['prix_initial_unitaire']->renderError(); ?>
                        <div class="form-group <?php if($form['prix_initial_unitaire']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['prix_initial_unitaire']->renderLabel("Prix :", array('class' => 'col-sm-4 control-label')); ?>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <?php echo $form['prix_initial_unitaire']->render(array("class" => "form-control text-right", 'autocomplete' => 'off')); ?>
                                    <span class="input-group-addon">€ / <?php echo $unite; ?></span>
                                </div>
                            </div>
                        </div>

                        <?php if ($form->getObject()->hasPrixVariable()): ?>
                        <?php echo $form['prix_unitaire']->renderError(); ?>
                        <div class="form-group <?php if($form['prix_unitaire']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['prix_unitaire']->renderLabel("Prix définitif :", array('class' => 'col-sm-4 control-label')); ?>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <?php echo $form['prix_unitaire']->render(array("class" => "form-control text-right", 'autocomplete' => 'off')); ?>
                                    <span class="input-group-addon">&nbsp;€</span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <?php if ($form->getObject()->hasPrixVariable()): ?>
                            <label class="col-sm-4 control-label">Prix initial total :</label>
                            <?php else: ?>
                            <label class="col-sm-4 control-label">Prix total :</label>
                            <?php endif; ?>
                            <div class="col-sm-8"><p class="form-control-static"><?php echoFloat($form->getObject()->prix_initial_total) ?> <?php if(!is_null($form->getObject()->prix_initial_total)):?>€<?php endif; ?></p></div>
                        </div>

                        <?php if ($form->getObject()->hasPrixVariable()): ?>
                        <div class="form-group">
                            <label class="col-md-3 col-lg-2 col-sm-4 control-label">Prix définitif total :</label>
                            <div class="col-sm-8"><p class="form-control-static"><?php echoFloat($form->getObject()->prix_total) ?> <?php if(!is_null($form->getObject()->prix_total)):?>€<?php endif; ?></p></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
<?php /**            
            <div class="col-xs-6 pull-right">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Retiraison</h3>
                    </div>
                    <div class="panel-body">
                        <?php echo $form['enlevement_date']->renderError(); ?>
                        <div class="form-group <?php if($form['enlevement_date']->hasError()): ?>has-error<?php endif; ?>">
                            <?php echo $form['enlevement_date']->renderLabel("Date limite :", array('class' => 'col-sm-4 control-label')); ?>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <?php echo $form['enlevement_date']->render(array("class" => "form-control text-right", 'autocomplete' => 'off')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      **/ ?>            
            
            
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4 text-left">
            <a tabindex="-1" href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Etape précédente</a>
        </div>
        <div class="col-xs-4 text-center">
            <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
                <a class="btn btn-default" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>">Supprimer le brouillon</a>
            <?php endif; ?>  
        </div>
        <div class="col-xs-4 text-right">
            <button type="submit" class="btn btn-success">Étape suivante <span class="glyphicon glyphicon-chevron-right"></span></button>
        </div>
    </div>
</form>

<script type="text/javascript">

    var changeMillesimeLabelAndDefault = function(nextMillesime) {
        switch ($("#type_transaction input:checked").val()) {
            case "<?php echo VracClient::TYPE_TRANSACTION_MOUTS ?>":
            case "<?php echo VracClient::TYPE_TRANSACTION_RAISINS ?>":
                $("div#millesime label").text('Récolte');
                $("div#millesime > input").val(nextMillesime);
                $('#vrac_millesime').val(nextMillesime);
                break;

            case "<?php echo VracClient::TYPE_TRANSACTION_VIN_VRAC ?>":
            case "<?php echo VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE ?>":
                $("div#millesime label").text('Millésime');
                $("div#millesime > input").val('Non millésimé');
                $('#vrac_millesime').val("0");
                break;
        }
    }

    $(document).ready(function()
    {
        if (!('contains' in String.prototype)) {
            String.prototype.contains = function(str, startIndex) {
                return -1 !== String.prototype.indexOf.call(this, str, startIndex);
            };
        }


        initMarche(<?php echo ($isTeledeclarationMode) ? 'true' : 'false'; ?>);

<?php if (!$isTeledeclarationMode): ?>
            var ajaxParams = {'numero_contrat': '<?php echo $form->getObject()->numero_contrat ?>',
                'vendeur': '<?php echo $form->getObject()->vendeur_identifiant ?>',
                'acheteur': '<?php echo $form->getObject()->acheteur_identifiant ?>',
                'mandataire': '<?php echo $form->getObject()->mandataire_identifiant ?>'};

            $('#produit input').live("autocompleteselect", function(event, ui)
            {

                var integrite = getContratSimilaireParams(ajaxParams, ui);
                refreshContratsSimilaire(integrite, ajaxParams);

            });

            $('#volume_total').change(function()
            {
                var integrite = getContratSimilaireParams(ajaxParams, null);
                refreshContratsSimilaire(integrite, ajaxParams);
            });
<?php endif; ?>


        $('#type_transaction input').change(function()
        {
<?php if (!$isTeledeclarationMode): ?>
                var integrite = getContratSimilaireParams(ajaxParams, null);
                refreshContratsSimilaire(integrite, ajaxParams);
<?php endif; ?>
            changeMillesimeLabelAndDefault("<?php echo $form->getNextMillesime(); ?>");
        });

    });

    var densites = [];
<?php
foreach ($form->getProduits() as $key => $prod) :
    if ($key != "") :
        ?>
            densites["<?php echo $key ?>"] = "<?php echo ConfigurationClient::getCurrent()->get($key)->getDensite(); ?>";
        <?php
    endif;
endforeach;
?>

</script>
