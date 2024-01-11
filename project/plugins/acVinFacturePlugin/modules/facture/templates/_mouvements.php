<?php
use_helper('Float');
use_helper('Date');
?>
<div class="row row-margin">
    <div class="col-xs-8">
        <h2 class="vertical-center" style="margin: 0 0 20px 0;">Mouvements en attente de facturation</h2>
    </div>
    <div class="col-xs-4 text-right">
        <a href="<?php echo url_for('facture_creation', array('identifiant' => $societe->identifiant)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Facturer les mouvements</a>
    </div>
</div>

<?php if (!count($mouvements)) : ?>
    <div class="row row-margin">
        <p class="text-center text-muted">Pas de mouvements en attente de facturation</p>
    </div>
<?php else : ?>
    <div class="row row-margin">
        <div class="col-xs-12">


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 90px;">Date</th>
                        <th>Document</th>
                        <th>Produits</th>
                        <th>Type</th>
                        <th style="width: 90px;">Quantité</th>
                        <th class="text-right" style="width: 90px;">Prix HT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mouvements as $mouvement): ?>
                        <?php
                        $idDoc = (isset($mouvement->origines) && strpos($mouvement->origines, ':') !== false)? substr($mouvement->origines, 0, strpos($mouvement->origines, ':')) : $mouvement->id_doc;
                        $drmMvt = (strstr($idDoc, 'DRM') !== false);
                        $sv12Mvt = (strstr($idDoc, 'SV12') !== false);
                         ?>
                        <tr class="vertical-center">
                            <td class="text-left">
                                <?php echo format_date($mouvement->date); ?>
                            </td>
                            <td class="text-left">
                                <?php
                                if(isset($mouvement->nom_facture)) {
                                    $numeroFormatted = $mouvement->nom_facture;
                                }
                                if ($drmMvt) {
                                  $numeroFormatted = DRMClient::getInstance()->getLibelleFromId($idDoc);
                                }else if ($sv12Mvt) {
                                  $numeroFormatted = SV12Client::getInstance()->getLibelleFromId($idDoc);
                                }
                                echo link_to($numeroFormatted, 'facture_redirect_to_doc', array('iddocument' => $idDoc));
                                ?>
                            </td>
                            <td class="text-left"><?php echo ($drmMvt || $sv12Mvt) ? $mouvement->produit_libelle : $mouvement->type_libelle; ?></td>
                            <td class="text-left"><?php echo ($drmMvt || $sv12Mvt) ? $mouvement->type_libelle : "" ?></td>
                            <td class="text-right">
                                <div class="row">
                                    <div class="col-xs-6" style="padding: 0;">
                                        <span class="text-right">
                                            <?php echoFloat($mouvement->quantite); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-6 text-left" style="padding: 0;">
                                        <span >
                                            <?php if ($drmMvt || $sv12Mvt): ?>&nbsp;hl<?php else: ?>&nbsp;<?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right"><?php echoFloat($mouvement->prix_ht); ?>&nbsp;&euro;</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
