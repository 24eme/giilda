<?php use_helper('DRM'); ?>
<form action="<?php echo url_for('drm_crd', $crdsForms->getObject()); ?>" method="post">
    <h2>Saisie des CRD</h2>
    <?php echo $crdsForms->renderGlobalErrors(); ?>
    <?php echo $crdsForms->renderHiddenFields(); ?>
    <?php foreach ($allCrdsByRegimeAndByGenre as $regime => $crdAllGenre): ?>
        <?php if (count($allCrdsByRegimeAndByGenre) > 1): ?>
            <p>Régime de CRD : <?php echo EtablissementClient::$regimes_crds_libelles_longs[$regime]; ?></p>
        <?php endif; ?>
        <?php foreach ($crdAllGenre as $genre => $crds): ?>
            <h2><?php echo getLibelleForGenre($genre); ?></h2>
            <table id="table_drm_crds" class="table_recap">
                <thead >
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
                <tbody class="drm_crds_list">
                    <?php foreach ($crds as $crdKey => $crd): ?>
                        <tr class="crd_row" id="<?php echo $crdKey; ?>">
                            <td><?php echo $crd->getLibelle(); ?></td>
                            <td class="crds_debut_de_mois"><?php echo $crd->stock_debut; ?> <input type="hidden" value="<?php echo $crd->stock_debut; ?>"></td>
                            <td class="crds_entreesAchats"><?php echo $crdsForms['entrees_achats_' . $regime . '_' . $crdKey]->render(); ?></td>
                            <td class="crds_entreesRetours"><?php echo $crdsForms['entrees_retours_' . $regime . '_' . $crdKey]->render(); ?></td>
                            <td class="crds_entreesExcedents"><?php echo $crdsForms['entrees_excedents_' . $regime . '_' . $crdKey]->render(); ?></td>
                            <td class="crds_sortiesUtilisations"><?php echo $crdsForms['sorties_utilisations_' . $regime . '_' . $crdKey]->render(); ?></td>
                            <td class="crds_sortiesDestructions"><?php echo $crdsForms['sorties_destructions_' . $regime . '_' . $crdKey]->render(); ?></td>
                            <td class="crds_sortiesManquants"><?php echo $crdsForms['sorties_manquants_' . $regime . '_' . $crdKey]->render(); ?></td>
                            <td class="crds_fin_de_mois"><?php echo (is_null($crd->stock_fin)) ? "0" : $crd->stock_fin; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br/>

        <?php endforeach; ?>
        <br/>
        <div class="drm_add_crd_categorie">
            <a class="btn_majeur ajout_crds_popup" href="#add_crds_<?php echo $regime; ?>">Ajouter CRD</a>
        </div>
        <br/>
    <?php endforeach; ?>
    <div class="btn_etape">
        <a class="btn_etape_prec" href="<?php echo url_for('drm_edition', $drm); ?>">
            <span>Précédent</span>
        </a>
        <a class="lien_drm_supprimer" href="<?php echo url_for('drm_delete', $drm); ?>">
            <span>Supprimer la DRM</span>
        </a>
        <button class="btn_etape_suiv" id="button_drm_validation" type="submit"><span>Suivant</span></button>
    </div>
</form>
