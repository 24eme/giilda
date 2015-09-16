<?php use_helper('DRM'); ?>
<?php if ($drm->hasManyCrds()): ?>
    <div class="row">
        <?php foreach ($drm->getAllCrdsByRegimeAndByGenre() as $regime => $crdAllGenre): ?>
            <?php foreach ($crdAllGenre as $genre => $crds) : ?>
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Compte capsules (CRD) : <?php echo getLibelleForGenre($genre); ?></h3>
                    </div>
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
                            <?php
                            foreach ($crds as $nodeName => $crd) :
                                ?>
                                <tr>
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
                </div>
            </div> 
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>