<?php use_helper('DRM'); ?>

<?php if ($sf_user->isAdmin()): ?>
    <div class="text-right" style="margin-top: -15px;position: relative;">
    <a class="text-muted btn-link btn-xs" href="<?php echo url_for('drm_ajout_recolte_etablissement', ['identifiant' => $drm->identifiant, 'periode_version' => $drm->periode])?>" style="position: absolute;right:0;bottom:-18px;">(Ajouter une réserve interprofessionnelle sur un produit)</a>
    </div>
<?php endif; ?>

<?php $produits = $drm->getProduitsReserveInterpro();
if (count($produits) && DRMConfiguration::getInstance()->hasActiveReserveInterpro()): ?>
    <div class="row">
        <div class="col-xs-12">
            <h3>
            Réserve interprofessionnelle
              <?php if ($sf_user->isAdmin()): ?>
              <small class="text-muted"><a href="<?php echo url_for('drm_transfert_recolte_etablissement',
                  ['identifiant' => $drm->identifiant, 'periode_version' => $drm->periode]) ?>"
                >(modifier)</a>
              </small>
              <?php endif ?>
            </h3>
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
