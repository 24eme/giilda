<?php use_helper('DRM'); ?>
<?php
$produits = $drm->getProduitsReserveInterpro();
if (count($produits) && DRMConfiguration::getInstance()->hasActiveReserveInterpro()): ?>
    <div class="row">
        <div class="col-xs-12">
            <h3>Réserve interprofessionnelle</small></h3>
            <p><?php echo DRMConfiguration::getInstance()->getRerserveInteproMessage(); ?></p>
            <div class="col-xs-8" style="padding-left: 0px;">
             <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th class="col-xs-8"><strong>Produit</strong></th>
                        <th class="col-xs-4 text-right"><strong>Volumes en réserve</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $p) : ?>
                                <tr class="text-right">
                                    <td style="text-align: left;"><strong><?php echo $p->getLibelle(); ?></strong></td>
                                    <td><?php echoFloat($p->getRerserveIntepro()); ?>&nbsp;hl</td>
                                </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
          </div>
        </div>
    </div>
<?php endif; ?>
