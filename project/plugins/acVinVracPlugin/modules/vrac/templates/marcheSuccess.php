<?php
/* Fichier : marcheSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/marche
 * Formulaire d'enregistrement de la partie marche d'un contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0
 * Derniere date de modification : ${date}
 */
$contratNonSolde = ((!is_null($form->getObject()->valide->statut)) && ($form->getObject()->valide->statut != VracClient::STATUS_CONTRAT_SOLDE));
?>
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
            densites["<?php echo $key ?>"] = "<?php echo $vrac->getConfig()->get($key)->getDensite(); ?>";
        <?php
    endif;
endforeach;
?>

</script>
<section id="principal">
    <?php include_partial('headerVrac', array('vrac' => $vrac, 'compte' => $compte, 'actif' => 2,'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
    <div id="contenu_etape">
        <form id="vrac_marche" method="post" action="<?php echo url_for('vrac_marche', $vrac) ?>">

            <?php echo $form->renderHiddenFields() ?>
            <?php echo $form->renderGlobalErrors() ?>


            <div id="marche">

                <?php if (isset($form['attente_original'])): ?>
                    <!--  Affichage des loption original  -->
                    <div id="original" class="original section_label_strong">
                        <?php echo $form['attente_original']->renderLabel() ?>
                        <?php echo $form['attente_original']->render() ?>
                        <?php echo $form['attente_original']->renderError(); ?>
                    </div>
                <?php endif; ?>

                <!--  Affichage des transactions disponibles  -->
                <div id="type_transaction" class="type_transaction section_label_maj">
                    <?php echo $form['type_transaction']->renderLabel() ?>
                    <?php echo $form['type_transaction']->renderError(); ?>
                    <?php echo $form['type_transaction']->render() ?>
                </div>

                <!--  Affichage des produits, des labels et du stock disponible  -->
                <div id="vrac_marche_produitLabel" class="section_label_maj">
                    <?php include_partial('marche_produitLabel', array('form' => $form, 'isTeledeclarationMode' => $isTeledeclarationMode, 'defaultDomaine' => $defaultDomaine)); ?>
                </div>

                <!--  Affichage des volumes et des prix correspondant  -->
                <div id="vrac_marche_volumePrix" class="section_label_maj">
                    <?php include_partial('marche_volumePrix', array('form' => $form)); ?>
                </div>

            </div>

            <div class="btn_etape">
                <a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn_etape_prec"><span>Etape précédente</span></a>
                <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
                    <a class="lien_contrat_supprimer_brouillon" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>" style="margin-left: 10px">
                        <span>Supprimer Brouillon</span>
                    </a>
                <?php endif; ?>
                <button class="btn_etape_suiv" type="submit"><span>Etape Suivante</span></button>
            </div>
        </form>
    </div>
    <?php include_partial('popup_notices'); ?>
</section>

<?php
if ($isTeledeclarationMode):
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
