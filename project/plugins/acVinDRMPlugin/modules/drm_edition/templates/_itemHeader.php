<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
?>
<div class="col-xs-4">
    <form action="<?php echo url_for('drm_choix_favoris', $formFavoris->getObject()) ?>" method="post">
            <?php echo $formFavoris->renderHiddenFields(); ?>
            <?php echo $formFavoris->renderGlobalErrors(); ?>
    <div class="list-group" style="margin-top: 55px;" id="colonne_intitules">
        
            <div class="list-group-item list-group-item-xs groupe groupe_ouvert groupe_bloque" data-groupe-id="1">
                <h4 style="height:22px;" class="itemcache form-group form-group-xs">Stock théorique dbt de mois</h4>
                <ul class="list-unstyled hidden">
                    <?php foreach ($detailsNodes->getStocksDebut() as $key => $item): ?>               
                        <li class="form-group form-group-xs <?php echo ($key != 'revendique') ? ' itemcache' : ' li_gris'; ?>">
                            <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="list-group-item list-group-item-xs groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="2">
                <h4 style="height:22px;" class="form-group form-group-xs">Entrées</h4>
                <ul class="list-unstyled">
                    <?php foreach ($detailsNodes->getEntreesSorted() as $key => $item): ?>
                        <?php if ($favoris_entrees->exist($key)): ?>
                            <li class="form-group form-group-xs">
                                <span class="glyphicon glyphicon-star"></span>
                                <span id="<?php echo 'star_favoris_entrees_' . $key ?>" class="categorie_libelle <?php echo 'entrees_' . $key; ?> <?php echo (count($favoris_entrees) > 1 ) ? 'clickable' : ''; ?>">
                                    <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                                </span>
                                &nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

                <div class="groupe no_favoris" data-groupe-id="3">
                    <p style="height:22px;" class="extendable form-group form-group-xs"><strong>Autres entrées</strong><span style="margin-top: 5px;" class="glyphicon glyphicon-chevron-down pull-right"></span></p>
                    <ul class="list-unstyled" style="display: none;">
                        <?php foreach ($detailsNodes->getEntreesSorted() as $key => $item): ?>
                            <?php if (!$favoris_entrees->exist($key)): ?>
                                <li class="form-group form-group-xs">
                                    <span class="glyphicon glyphicon-star-empty"></span>     
                                    <span id="<?php echo 'star_favoris_entrees_' . $key ?>" class="categorie_libelle <?php echo 'entrees_' . $key; ?>  <?php echo (count($favoris_entrees) < DRMClient::$drm_max_favoris_by_types_mvt[DRMClient::DRM_TYPE_MVT_ENTREES] ) ? 'clickable' : ''; ?>">
                                        <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                                    </span>
                                    &nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>       

            <div class="list-group-item list-group-item-xs groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="4">
                <h4 style="height:22px;" class="form-group form-group-xs">Sorties</h4>
                <ul class="list-unstyled">
                    <?php foreach ($detailsNodes->getSortiesSorted() as $key => $item): ?>
                        <?php if ($favoris_sorties->exist($key)): ?>
                            <li class="form-group form-group-xs">
                                <span class="glyphicon glyphicon-star"></span> 
                                <span id="<?php echo 'star_favoris_sorties_' . $key ?>" class="categorie_libelle <?php echo 'sorties_' . $key; ?> <?php echo (count($favoris_sorties) > 1 ) ? 'clickable' : ''; ?>">
                                    <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                                </span>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <div class="groupe no_favoris" data-groupe-id="5">
                    <p style="height:22px;" class="extendable form-group form-group-xs"><strong>Autres sorties</strong><span style="margin-top: 5px;" class="glyphicon glyphicon-chevron-down pull-right"></span></p>
                    <ul class="list-unstyled" style="display: none;">
                        <?php foreach ($detailsNodes->getSortiesSorted() as $key => $item): ?>
                            <?php if (!$favoris_sorties->exist($key)): ?>
                                <li class="form-group form-group-xs">
                                    <span class="glyphicon glyphicon-star-empty"></span> 
                                    <span id="<?php echo 'star_favoris_sorties_' . $key ?>" class="categorie_libelle <?php echo 'sorties_' . $key; ?> <?php echo (count($favoris_sorties) < DRMClient::$drm_max_favoris_by_types_mvt[DRMClient::DRM_TYPE_MVT_SORTIES] ) ? 'clickable' : ''; ?>">
                                        <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                                    </span>
                                    &nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="list-group-item list-group-item-xs groupe groupe_ouvert groupe_bloque" data-groupe-id="6">
                <h4 style="height:22px;" class="itemcache form-group form-group-xs">Stock théorique fin de mois</h4>
                <ul class="list-unstyled hidden">
                    <?php foreach ($detailsNodes->getStocksFin() as $key => $item): ?>
                        <li class="form-group form-group-xs <?php echo ($key != 'revendique') ? ' itemcache' : ' li_gris'; ?>">
                            <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
    </div>
     </form>
</div>
