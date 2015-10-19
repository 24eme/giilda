<?php use_helper('DRM'); ?>
<?php if ($drm->hasManyCrds()): ?>
    <div class="row">
        <div class="col-xs-12">
            <h3>Compte capsules <small>(CRD)</small></h3>
             <table class="table table-bordered table-condensed">
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
                <?php foreach ($drm->getAllCrdsByRegimeAndByGenre() as $regime => $crdAllGenre): ?>
                    <?php foreach ($crdAllGenre as $genre => $crds) : ?>
                            <?php foreach ($crds as $nodeName => $crd) :
                                ?>
                                <tr>
                                    <td><?php echo getLibelleForGenre($genre); ?> <?php echo $crd->getLibelle(); ?></td>
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
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>