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
                        <span class="col-xs-2">N° <?php echo $f->numero_ava ?></span>
                        <span class="col-xs-4"><?php echo $date; ?></span>
                        <span class="col-xs-2 text-right"><?php echo echoFloat($f->total_ttc); ?> € TTC</span>
                        <span class="col-xs-3 text-right">
                            <div class="btn-group text-left">                               
                                <button type="button" class="btn btn-default btn-default-step btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (!$f->isPayee() && !$f->versement_comptable): ?>
                                        <li><a href="<?php if (!$f->isPayee() && !$f->versement_comptable): ?><?php echo url_for("facture_edition", array("id" => $f->_id)) ?><?php endif; ?>">Modifier</a></li>
                                    <?php else: ?>
                                        <li class="disabled"><a href="">Modifier</a></li>
                                    <?php endif; ?>
                                    <?php if (!$f->isAvoir()): ?>
                                        <li><a href="<?php echo url_for("facture_avoir", array("id" => $f->_id)) ?>">Créér un avoir <small>(à partir de cette facture)</small></a></li>
                                    <?php endif; ?>
                                    <?php if (!$f->isAvoir() && !$f->isPayee() && !$f->versement_comptable): ?>
                                        <li><a onclick='return confirm("Étes vous sûr de vouloir regénérer la facture ?");' href="<?php echo url_for("facture_regenerate", array("id" => $f->_id)) ?>">Regénerer</a></li>
                                    <?php elseif (!$f->isAvoir()): ?>
                                        <li class="disabled"><a href="#">Regénerer</a></li>
                                    <?php endif; ?>
                                  
                                </ul>
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
