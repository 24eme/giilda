<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
?>
<div class="col-xs-4">
    <form action="<?php echo url_for('drm_choix_favoris', array('identifiant' => $formFavoris->getObject()->getIdentifiant(),
    'periode_version' => $formFavoris->getObject()->getPeriodeAndVersion(),
    'details' => $detailsNodes->getKey())) ?>" id="colonne_intitules" method="post">
        <?php echo $formFavoris->renderHiddenFields(); ?>
        <?php echo $formFavoris->renderGlobalErrors(); ?>
        <div class="head" style="margin-top: 37px;"></div>
        <div class="list-group" >
            <div class="list-group-item list-group-item-xs groupe groupe_ouvert groupe_bloque" data-groupe-id="1">
                <div class="form-group form-group-xs" style="height:22px; font-weight: bold;">Stock début</div>
                <ul class="list-unstyled">
                    <?php foreach ($detailsNodes->getStocksDebut() as $key => $item): ?>
                        <?php if ($key != 'instance'): ?>
                    <li class="categorie_libelle form-group form-group-xs">
                                <span id="<?php echo 'stock_debut_' . $key ?>" class=" <?php echo 'stock_debut_' . $key ?>">
                                 <?php echo str_replace(" ", "&nbsp;", $item->getLibelle()); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="list-group-item list-group-item-xs groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="2">
                <div class="form-group form-group-xs" style="height:22px; font-weight: bold;">Entrées</div>
                <ul class="list-unstyled">
                    <?php foreach ($detailsNodes->getEntreesSorted() as $key => $item): ?>
                        <?php if ($favoris_entrees->exist($key)): ?>
                            <li class="form-group form-group-xs" style="cursor: pointer;">
                              <?php if($saisieSuspendu): ?>
                                <span class="glyphicon glyphicon-star"></span>
                              <?php endif; ?>
                                <span id="<?php echo ($saisieSuspendu)? 'star_favoris_entrees_' . $key : ''; ?>" class="categorie_libelle <?php echo 'entrees_' . $key; ?> <?php echo (count($favoris_entrees) > 1 ) ? 'clickable' : ''; ?>">
                                <?php echo $item->getLibelle(); ?>&nbsp;<small><span class="glyphicon glyphicon-question-sign" title="<?php echo $item->getLibelleLong(); ?>"></span></small>
                                </span>
                                &nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <div class="groupe no_favoris" data-groupe-id="3">
                    <?php if($saisieSuspendu): ?>
                    <p style="height:22px; cursor: pointer;" class="extendable form-group form-group-xs">Autres entrées<span style="margin-top: 5px;" class="glyphicon glyphicon-chevron-down pull-right"></span></p>
                    <?php endif; ?>
                    <ul class="list-unstyled" style="display: none;">
                        <?php foreach ($detailsNodes->getEntreesSorted() as $key => $item): ?>
                            <?php if (!$favoris_entrees->exist($key)): ?>
                                <li class="form-group form-group-xs" style="cursor: pointer;">
                                  <?php if($saisieSuspendu): ?>
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                  <?php endif; ?>
                                    <span id="<?php echo ($saisieSuspendu)? 'star_favoris_entrees_' . $key : ''; ?>" class="categorie_libelle <?php echo 'entrees_' . $key; ?>  <?php echo (count($favoris_entrees) < DRMClient::$drm_max_favoris_by_types_mvt[DRMClient::DRM_TYPE_MVT_ENTREES] ) ? 'clickable' : ''; ?>">
                                        <?php echo $item->getLibelle(); ?>&nbsp;<small><span class="glyphicon glyphicon-question-sign" title="<?php echo $item->getLibelleLong(); ?>"></span></small>
                                    </span>
                                    &nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="list-group-item list-group-item-xs groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="4">
                <div class="form-group form-group-xs" style="height:22px; font-weight: bold;">Sorties</div>
                <ul class="list-unstyled">
                    <?php foreach ($detailsNodes->getSortiesSorted() as $key => $item): ?>
                        <?php if ($favoris_sorties->exist($key)): ?>
                            <li class="form-group form-group-xs" style="cursor: pointer;">
                                <?php if($saisieSuspendu): ?>
                                <span class="glyphicon glyphicon-star"></span>
                              <?php endif; ?>
                                <span id="<?php echo ($saisieSuspendu)? 'star_favoris_sorties_' . $key : ''; ?>" class="categorie_libelle <?php echo 'sorties_' . $key; ?> <?php echo (count($favoris_sorties) > 1 ) ? 'clickable' : ''; ?>">
                                    <?php echo $item->getLibelle(); ?>&nbsp;<small><span class="glyphicon glyphicon-question-sign" title="<?php echo $item->getLibelleLong(); ?>"></span></small>
                                </span>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <div class="groupe no_favoris" data-groupe-id="5">
                  <?php if($saisieSuspendu): ?>
                    <p style="height:22px; cursor: pointer;" class="extendable form-group form-group-xs">Autres sorties<span style="margin-top: 5px;" class="glyphicon glyphicon-chevron-down pull-right"></span></p>
                  <?php endif; ?>
                    <ul class="list-unstyled" style="display: none;">
                        <?php foreach ($detailsNodes->getSortiesSorted() as $key => $item): ?>
                            <?php if (!$favoris_sorties->exist($key)): ?>
                                <li class="form-group form-group-xs">
                                    <?php if($saisieSuspendu): ?>
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                    <?php endif; ?>
                                    <span id="<?php echo ($saisieSuspendu)? 'star_favoris_sorties_' . $key : '' ?>" class="categorie_libelle <?php echo 'sorties_' . $key; ?> <?php echo (count($favoris_sorties) < DRMClient::$drm_max_favoris_by_types_mvt[DRMClient::DRM_TYPE_MVT_SORTIES] ) ? 'clickable' : ''; ?>">
                                        <?php echo $item->getLibelle(); ?>&nbsp;<small><span class="glyphicon glyphicon-question-sign" title="<?php echo $item->getLibelleLong(); ?>"></span></small>
                                    </span>
                                    &nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="list-group-item list-group-item-xs groupe groupe_ouvert groupe_bloque" data-groupe-id="6">
                <div class="form-group form-group-xs" style="height:22px; font-weight: bold;">Stock théorique fin de mois</div>
                <ul class="list-unstyled">
                    <?php foreach ($detailsNodes->getStocksFin() as $key => $item): ?>
                        <?php if ($key != 'instance'): ?>
                            <li class="form-group form-group-xs <?php echo ($key != 'revendique') ? '' : ' li_gris'; ?>">
                                <?php echo str_replace(" ", "&nbsp;", $item->getLibelle()); ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach;
                    ?>
                </ul>
            </div>
        </div>
    </form>
</div>
