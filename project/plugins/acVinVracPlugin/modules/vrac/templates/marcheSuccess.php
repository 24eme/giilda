<?php use_helper('Float'); ?>
<?php $contratNonSolde = ((!is_null($form->getObject()->valide->statut)) && ($form->getObject()->valide->statut != VracClient::STATUS_CONTRAT_SOLDE)); ?>

<?php include_partial('vrac/etapes', array('vrac' => $form->getObject(), 'compte' => $compte, 'actif' => 2, 'urlsoussigne' => null, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<div class="page-header">
    <h2>Marché</h2>
</div>

<form action="" method="post" class="form-horizontal">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?php echo $form['attente_original']->renderError(); ?>
                <?php echo $form['attente_original']->renderLabel("En attente de l'original :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['attente_original']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['type_transaction']->renderError(); ?>
                <?php echo $form['type_transaction']->renderLabel("Type de transaction :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['type_transaction']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['produit']->renderError(); ?>
                <?php echo $form['produit']->renderLabel("Produit :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['produit']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['millesime']->renderError(); ?>
                <?php echo $form['millesime']->renderLabel("Millésime :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['millesime']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['categorie_vin']->renderError(); ?>
                <?php echo $form['categorie_vin']->renderLabel("Type :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['categorie_vin']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['domaine']->renderError(); ?>
                <?php echo $form['domaine']->renderLabel("Domaine :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['domaine']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <?php if (isset($form['label'])): ?>
            <div class="form-group">
                <?php echo $form['label']->renderError(); ?>
                <?php echo $form['label']->renderLabel("Label :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['label']->render(array('class' => 'form-control')); ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <?php echo $form['bouteilles_contenance_libelle']->renderError(); ?>
                <?php echo $form['bouteilles_contenance_libelle']->renderLabel("Contenance :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['bouteilles_contenance_libelle']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['bouteilles_quantite']->renderError(); ?>
                <?php echo $form['bouteilles_quantite']->renderLabel("Quantité :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['bouteilles_quantite']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['jus_quantite']->renderError(); ?>
                <?php echo $form['jus_quantite']->renderLabel("Volume proposé :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['jus_quantite']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['raisin_quantite']->renderError(); ?>
                <?php echo $form['raisin_quantite']->renderLabel("Quantité de raisins :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['raisin_quantite']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form['prix_initial_unitaire']->renderError(); ?>
                <?php echo $form['prix_initial_unitaire']->renderLabel("Prix :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['prix_initial_unitaire']->render(array('class' => 'form-control')); ?>
                </div>
            </div>

            <?php if ($form->getObject()->hasPrixVariable()): ?>
            <div class="form-group">
                <?php echo $form['prix_unitaire']->renderError(); ?>
                <?php echo $form['prix_unitaire']->renderLabel("Prix définitif :", array('class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?php echo $form['prix_unitaire']->render(array('class' => 'form-control')); ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <?php if ($form->getObject()->hasPrixVariable()): ?>
                <label class="col-sm-4 control-label">Prix initial total</label>
                <?php else: ?>
                <label class="col-sm-4 control-labell">Prix total</label>
                <?php endif; ?>
                <div class="col-sm-8"><?php echoFloat($form->getObject()->prix_initial_total) ?> <?php if(!is_null($form->getObject()->prix_initial_total)):?>€<?php endif; ?></div>
            </div>

            <?php if ($form->getObject()->hasPrixVariable()): ?>
            <div class="form-group">
                <label class="col-sm-4 control-label">Prix définitif total</label>
                <div class="col-sm-8"><?php echoFloat($form->getObject()->prix_total) ?> <?php if(!is_null($form->getObject()->prix_total)):?>€<?php endif; ?></div>
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