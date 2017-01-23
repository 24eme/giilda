<?php use_helper('Float') ?>
<?php use_helper('Date') ?>
<?php use_helper('DRM') ?>

<?php if(count($produits->getRawValue()) > 0): ?>
<div class="section_label_maj" id="calendrier_drm">
    <form method="post" class="form-inline" style="margin-top: 10px;">
        <?php echo $formCampagne->renderGlobalErrors() ?>
        <?php echo $formCampagne->renderHiddenFields() ?>
        <?php echo $formCampagne; ?> <input class="btn btn-default btn-sm" type="submit" value="Changer"/>
    </form>

    <?php if(isset($hamza_style) && $hamza_style) : ?>
        <?php include_partial('global/hamzaStyle', array('table_selector' => '#table_stocks',
                                                     'mots' => produit_get_words($produits),
                                                     'consigne' => "Saisissez un mois ou un produit :")) ?>
    <?php endif; ?>

    <?php if($vigilance): ?>
    <div id="points_vigilance">
        <ul>
            <li class="warning">
                Les informations fournies ci-dessous sont partielles car une DRM est en cours de saisie pour cette campagne
            </li>
        </ul>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xs-12">
            <h3>Filtrer</h3>
            <div class="form-group">
                <input type="hidden" data-placeholder="Saisissez un produit ou un mois" data-hamzastyle-container="#table_stocks" class="hamzastyle form-control" />
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 20px;">
        <div class="col-xs-12">
            <table id="table_stocks" class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-2">Mois</td>
                        <th class="col-xs-3">Produits</td>
                        <th class="col-xs-2">Stock début de mois</th>
                        <th class="col-xs-1">Entrées</th>
                        <th class="col-xs-2">Sorties (Fact.)</th>
                        <th class="col-xs-2"><strong>Stock fin de mois</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach($produits->getRawValue() as $produit): ?>
                    <?php $i++; ?>
                            <tr class="hamzastyle-item" data-words='<?php echo json_encode(array_merge(array(strtolower($produit->produit_libelle), strtolower($produit->mois))), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>' id="<?php echo produit_get_id($produit) ?>">
                                <td><a href="<?php echo url_for('drm_visualisation', array('identifiant' => $etablissement->identifiant, 'periode_version' => DRMClient::getInstance()->buildPeriodeAndVersion($produit->periode, $produit->version)))?>"><?php echo $produit->mois ?></a></td>
                                <td><?php echo $produit->produit_libelle ?></td>
                                <td><strong><?php echoFloat($produit->stocks_debut_initial) ?></strong><?php if(isset($produit->stocks_debut_dont_revendique) && $produit->stocks_debut_dont_revendique): ?> (<?php echoFloat($produit->stocks_debut_dont_revendique) ?>) <?php endif; ?></td>
                                <td><?php echoFloat($produit->total_entrees) ?></td>
                                <td><?php echoFloat($produit->total_sorties) ?> (<?php echoFloat($produit->total_facturable) ?>)</td>
                                <td><strong><?php echoFloat($produit->stocks_fin_final) ?></strong><?php if(isset($produit->stocks_fin_dont_revendique) && $produit->stocks_fin_dont_revendique): ?> (<?php echoFloat($produit->stocks_fin_dont_revendique) ?>)<?php endif; ?></td>
                            </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<p> Aucun stocks </p>
<?php endif; ?>
