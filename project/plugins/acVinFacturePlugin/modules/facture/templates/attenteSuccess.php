<?php use_helper('Date'); ?>
<?php use_helper('Float'); ?>

<?php include_partial('facture/preTemplate'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('facture') ?>">Factures</a></li>
    <li class="active">En attente</li>
</ol>

<h2>Mouvements de facturation en attente</h2>
<h4 class="page-header"><?php echo count($mouvements) ?> opérateurs avec des mouvements en attente</h4>

    <table class="table table-striped table-condensed">
      <thead>
        <tr><th colspan="7">Établissements <a href="<?php echo url_for('facture_en_attente', array('details' => 1)) ?>" class="btn btn-xs btn-link pull-right"><span class="glyphicon glyphicon-eye-open"></span > Voir tous les mouvements</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($mouvements->getRawValue() as $id => $mouvements): ?>
            <?php $etablissement = EtablissementClient::getInstance()->retrieveById($id, acCouchdbClient::HYDRATE_JSON); ?>
            <tr>
                <td colspan="3" title="<?php echo $id ?>"><?php echo $etablissement->raison_sociale ?> <small class="text-muted"><?php echo $etablissement->famille; ?></small></td>
                <td colspan="3">
                    <a href="<?php echo url_for('facture_etablissement', ['identifiant' => $id]) ?>" class="btn btn-xs btn-default pull-right">
                        <?php if($withDetails): ?>
                        Voir l'espace facture
                        <?php else: ?>
                        Voir le<?php echo (count($mouvements) > 1) ? 's' : '' ?> <?php echo count($mouvements) ?> mouvement<?php echo (count($mouvements) > 1) ? 's' : '' ?>
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <?php endif; ?>
                    </a>
                </td>
            </tr>
            <?php if($withDetails): ?>
                <?php foreach($mouvements as $mvt): ?>
                    <?php
                        $idDoc = $mvt->value[MouvementfactureFacturationView::VALUE_ID_DOC];
                        $drmMvt = (strstr($idDoc, 'DRM') !== false);
                        $sv12Mvt = (strstr($idDoc, 'SV12') !== false);
                        if ($drmMvt) {
                          $numeroFormatted = DRMClient::getInstance()->getLibelleFromId($idDoc);
                        } else if ($sv12Mvt) {
                          $numeroFormatted = SV12Client::getInstance()->getLibelleFromId($idDoc);
                        }
                     ?>
                    <tr>
                        <td><?php echo link_to($numeroFormatted, 'facture_redirect_to_doc', array('iddocument' => $idDoc)); ?></td>
                        <td><?php echo format_date($mvt->key[MouvementfactureFacturationView::KEYS_DATE], "dd/MM/yyyy", "fr_FR"); ?></td>
                        <td><?php echo $mvt->value[MouvementfactureFacturationView::VALUE_PRODUIT_LIBELLE] ?></td>
                        <td><?php echo $mvt->value[MouvementfactureFacturationView::VALUE_TYPE_LIBELLE] ?> <?php echo $mvt->value[MouvementfactureFacturationView::VALUE_DETAIL_LIBELLE] ?></td>
                        <td class="text-right"><?php echo echoFloat($mvt->value[MouvementfactureFacturationView::VALUE_QUANTITE]); ?><small class="text-muted"><?php if ($drmMvt || $sv12Mvt): ?>&nbsp;hl<?php else: ?>&nbsp;<?php endif; ?></small></td>
                        <td class="text-right"><?php echo echoFloat(round($mvt->value[MouvementfactureFacturationView::VALUE_QUANTITE] * $mvt->value[MouvementfactureFacturationView::VALUE_PRIX_UNITAIRE], 2)); ?>&nbsp;€</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach ?>
      </tbody>
    </table>

    <?php include_partial('facture/postTemplate'); ?>
