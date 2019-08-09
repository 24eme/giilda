<?php use_helper('DRM'); ?>
<?php if ($drm->hasManyCrds()): ?>
    <?php foreach ($drm->getAllCrdsByRegimeAndByGenre() as $regime => $crdAllGenre): ?>
        <h2>COMPTE CAPSULES <?php echo EtablissementClient::$regimes_crds_libelles[$regime]; ?></h2>
        <fieldset id="crds_drm">
            <?php foreach ($crdAllGenre as $genre => $crds) : ?>
                <h2><?php echo getLibelleForGenre($genre); ?>&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_validation_aide6'); ?>"></a></h2>

                <table class="table_recap">
                    <thead>
                        <tr>                        
                            <th rowspan="2">CRD</th>
                            <th rowspan="2">Stock</th>
                            <th colspan="3">Entrées</th>
                            <th colspan="3">Sorties</th>
                            <th rowspan="2">Stock <?php echo getLastDayForDrmPeriode($drm); ?></th>
                        </tr>
                        <tr>                 

                            <th>Achat</th>
                            <th>Retour</th>
                            <th>Excéd.</th>

                            <th>Utilisé</th>
                            <th>Destr.</th>
                            <th>Manq.</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($crds as $nodeName => $crd) :
                            ?>
                            <tr >
                                <td><?php echo $crd->getLibelle(); ?></td>
                                <td><strong><?php echo $crd->stock_debut; ?></strong></td>
                                <td><strong><?php echo $crd->entrees_achats; ?></strong></td>
                                <td><strong><?php echo $crd->entrees_retours; ?></strong></td>
                                <td><strong><?php echo $crd->entrees_excedents; ?></strong></td>
                                <td><strong><?php echo $crd->sorties_utilisations; ?></strong></td>
                                <td><strong><?php echo $crd->sorties_destructions; ?></strong></td>
                                <td><strong><?php echo $crd->sorties_manquants; ?></strong></td>
                                <td><strong><?php echo $crd->stock_fin; ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </fieldset>
    <?php endforeach; ?>
<?php endif; ?>
