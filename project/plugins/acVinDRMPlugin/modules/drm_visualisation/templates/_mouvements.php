<?php
use_helper('Float');
use_helper('Date');
use_helper('Mouvement');

if (!isset($isTeledeclarationMode)) {
    $isTeledeclarationMode = false;
}
?>

<?php if (count($mouvementsByProduit) > 0): ?>
    <?php if (isset($hamza_style)) : ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <input type="hidden" data-placeholder="Saisissez un produit, un type de mouvement, un numéro de contrat, un pays d'export, etc. :" data-hamzastyle-container="#table_mouvements" class="hamzastyle form-control" />
                </div>
            </div>
        </div>
    <?php endif; ?>

    <table id="table_mouvements" class="table table-striped table-condensed">
        <thead>
            <tr>
                <th class="col-xs-3">Produits</th>
                <th class="col-xs-3">Type</th>
                <th class="col-xs-3"><span class="pull-right">Volume</span></th>
                <th class="col-xs-3"><span class="pull-left">(dont revendiqué)</span></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php
            foreach ($mouvementsByProduit as $produit_hash => $mouvements):
                $produit = $drm->get($produit_hash);
                $produit_libelle = $produit->getLibelle();
                $libelleDoc = DRMClient::getInstance()->getLibelleFromId($drm->_id);
                ?>
                <tr data-words='<?php echo json_encode(array_merge(Search::getWords($produit_libelle), Search::getWords($produit_libelle), Search::getWords($libelleDoc), Search::getWords($produit_libelle), array(strtolower($produit_libelle), strtolower($produit_libelle), strtolower($libelleDoc))), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>' id="<?php echo mouvement_get_id($drm->_id) ?>" class="hamzastyle-item">

                    <td><a href="#<?php echo str_replace(' ', '_', $produit_libelle) ?>" class="anchor_to_hamza_style"> <?php echo $produit_libelle ?> </a></td>
                    <td><strong>Stock début</strong></td>
                    <td> <span class="pull-right"><?php echoFloat($produit->total_debut_mois) . ' hl'; ?></span></td> 
                    <td> <span class="pull-left">( <?php echo ($produit->details->DEFAUT->stocks_debut->dont_revendique) ? sprintFloat($produit->details->DEFAUT->stocks_debut->dont_revendique) : "0.00"; ?> )</span></td>
                </tr>

                <?php foreach ($mouvements as $mouvement): ?>
                    <tr data-words='<?php echo json_encode(array_merge(Search::getWords($mouvement->produit_libelle), Search::getWords($mouvement->type_libelle), Search::getWords($libelleDoc), Search::getWords($mouvement->detail_libelle), array(strtolower($mouvement->produit_libelle), strtolower($mouvement->type_libelle), strtolower($libelleDoc), strtolower($mouvement->detail_libelle))), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>' id="<?php echo mouvement_get_id($mouvement) ?>" class="hamzastyle-item <?php echo ($mouvement->facturable && (!$isTeledeclarationMode || $visualisation)) ? " facturable" : ""; ?>">
                        <td><a href="#<?php echo str_replace(' ', '_', $mouvement->produit_libelle) ?>" class="anchor_to_hamza_style"> <?php echo $mouvement->produit_libelle ?> </a></td>
                        <td><?php
                            if ($mouvement->vrac_numero) {
                                echo (!isset($no_link) || !$no_link) ? '<a href="' . url_for("vrac_visualisation", array("numero_contrat" => $mouvement->vrac_numero)) . '">' : '';
                                echo $mouvement->type_libelle . ' ' . $mouvement->numero_archive;
                                echo (!isset($no_link) || !$no_link) ? '</a>' : '';
                            } else {
                                echo $mouvement->type_libelle . ' ' . $mouvement->detail_libelle;
                            }
                            ?>
                        </td>
                        <td <?php echo ($mouvement->volume > 0) ? ' class="positif"' : 'class="negatif"'; ?> >
                            <span class="pull-right"><?php echoSignedFloat($mouvement->volume); ?></span>
                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>


                <tr data-words='<?php echo json_encode(array_merge(Search::getWords($produit_libelle), Search::getWords($produit_libelle), Search::getWords($libelleDoc), Search::getWords($produit_libelle), array(strtolower($produit_libelle), strtolower($produit_libelle), strtolower($libelleDoc))), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>' id="<?php echo mouvement_get_id($drm->_id) ?>" class="hamzastyle-item">

                    <td><a href="#<?php echo str_replace(' ', '_', $produit_libelle) ?>" class="anchor_to_hamza_style"> <?php echo $produit_libelle ?> </a></td>
                    <td><strong>Stock fin</strong></td>
                    <td><span class="pull-right"><?php echoFloat($produit->total) . ' hl'; ?></span></td>
                    <td><span class="pull-left">( <?php echo ($produit->details->DEFAUT->stocks_fin->dont_revendique) ? sprintFloat($produit->details->DEFAUT->stocks_fin->dont_revendique) : "0.00"; ?> )</span></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-center"><em>Aucun mouvement</em></p>
<?php endif; ?>
