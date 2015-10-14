<?php
use_helper('Date');
?>
<div class="row row-margin">
    <h2>Historique des factures</h2>
</div>

<div class="row row-margin">
    <div class="col-xs-12">
        <?php if (count($factures)): ?>
            <div class="list-group">
                <?php
                foreach ($factures->getRawValue() as $facture) :
                    $fc = FactureClient::getInstance();
                    $date = format_date($facture->value[FactureSocieteView::VALUE_DATE_FACTURATION], 'dd/MM/yyyy') . ' (créée le ' . $fc->getDateCreation($facture->id) . ')';
                    ?>
                    <li class="list-group-item col-xs-12">

                        <span class="col-xs-1"></span>
                        <span class="col-xs-2">
                            <a href="<?php echo url_for("facture_pdf", array("id" => $facture->key[FactureSocieteView::KEYS_FACTURE_ID])); ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-file"></span>&nbsp;Visualiser</a>
                        </span>
                        <span class="col-xs-2 text-right"><?php echoFloat($facture->value[FactureSocieteView::VALUE_TOTAL_TTC]); ?>&nbsp;€ TTC</span>
                        <span class="col-xs-3 text-right"><?php echo $facture->key[FactureSocieteView::KEYS_FACTURE_ID]; ?></span>
                        <span class="col-xs-3 text-right">
                            <a href="<?php echo url_for("facture_edition", array("id" => $facture->key[FactureSocieteView::KEYS_FACTURE_ID])); ?>" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-apple"></span>&nbsp;Edition</a>
                        </span>
                        <span class="col-xs-5 text-right">
                            <div class="btn-group text-left">
                                <ul class="dropdown-menu">
                                    <?php
                                    foreach ($facture->value[FactureSocieteView::VALUE_ORIGINES] as $drmid => $drmlibelle) {

                                        $drmIdFormat = (strstr($drmlibelle, 'DRM') !== FALSE) ? DRMClient::getInstance()->getLibelleFromId($drmlibelle) :
                                                SV12Client::getInstance()->getLibelleFromId($drmlibelle);
                                        echo link_to($drmIdFormat, 'facture_redirect_to_doc', array('iddocument' => $drmid)) . "<br/>";
                                    };
                                    ?>
                                </ul>
                            </div>
                        </span>
                        <span class="col-xs-12">
                            <?php if ($fc->isRedressee($facture)): ?>
                                redressée
                            <?php elseif ($fc->isRedressable($facture)): ?>
                                <?php echo link_to('défacturer les mouvements', '@defacturer?identifiant=' . str_replace('FACTURE-', '', $facture->key[FactureSocieteView::KEYS_FACTURE_ID])); ?>
                            <?php endif; ?>

                        </span>
                    </li>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted"><i>Aucune Facture</i></p>
        <?php endif; ?>
    </div>
</div>
