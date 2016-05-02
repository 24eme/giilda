<?php
use_helper('Date');
?>
<div class="row row-margin">
    <div class="col-xs-12">
        <h2>Historique des factures</h2>
    </div>
    <div class="col-xs-12">
        <?php if (count($factures)): ?>
            <div class="list-group">
                <?php
                foreach ($factures->getRawValue() as $facture) :
                    $fc = FactureClient::getInstance();
                    $f = $fc->find($facture->id);
                    $date = format_date($facture->value[FactureSocieteView::VALUE_DATE_FACTURATION], 'dd/MM/yyyy') . ' (créée le ' . $fc->getDateCreation($facture->id) . ')';
                    ?>
                    <li class="list-group-item col-xs-12">
                        <span class="col-xs-1"><?php if ($f->isAvoir()): ?>AVOIR<?php else: ?>FACTURE<?php endif; ?></span>
                        <span class="col-xs-2">N° <?php echo $f->numero_piece_comptable ?></span>
                        <span class="col-xs-4"><?php echo $date; ?> <?php if($f->isRedressee()): ?><span class="label label-warning">Redressée</span><?php endif;?></span>
                        <span class="col-xs-2 text-right"><?php echo echoFloat($f->total_ht); ?> € HT</span>
                        <span class="col-xs-3 text-right">
                            <div class="btn-group text-left">
                                <?php if ($f->isRedressable()): ?>
                                    <a onclick="return confirm('Êtes-vous sur de vouloir créer annuler cette facture en créant un avoir ?');" href="<?php echo url_for("facture_avoir", array("id" => $f->_id)) ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-repeat"></span>&nbsp;Défacturer</a>
                                <?php endif; ?>                            
                                <a href="<?php echo url_for("facture_pdf", array("id" => $f->_id)) ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-file"></span>&nbsp;Visualiser</a>
                            </div>
                        </span>
                    </li>

                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted"><i>Aucune Facture</i></p>
        <?php endif; ?>
    </div>
</div>
