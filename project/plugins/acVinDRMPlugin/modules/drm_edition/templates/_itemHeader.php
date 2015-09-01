<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
?>
<div class="pull-left" style="width: 210px">
<div class="panel panel-default" id="colonne_intitules">
        <div class="panel-heading">Produit</div>
        <div class="panel-body">
    <form action="<?php echo url_for('drm_choix_favoris', $formFavoris->getObject()) ?>" method="post">
        <?php echo $formFavoris->renderHiddenFields(); ?>
        <?php echo $formFavoris->renderGlobalErrors(); ?>
        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="1">
            <p class="itemcache">Stock théorique dbt de mois</p>
            <ul>
                <?php foreach ($detailsNodes->getStocksDebut() as $key => $item): ?>               
                    <li class="<?php echo ($key != 'revendique') ? ' itemcache' : ' li_gris'; ?>">
                        <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="2">
            <p>Entrées</p>
            <ul>
                <?php foreach ($detailsNodes->getEntreesSorted() as $key => $item): ?>
                    <?php if ($favoris_entrees->exist($key)): ?>
                        <li>
                            <span id="<?php echo 'star_favoris_entrees_' . $key ?>" class="categorie_libelle <?php echo 'entrees_' . $key; ?> <?php echo (count($favoris_entrees) > 1 ) ? 'clickable' : ''; ?>">
                                <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                            </span>
                            &nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <div class="groupe no_favoris" data-groupe-id="3">
                <p class="extendable">Autres entrées</p>
                <ul style="display: none;">
                    <?php foreach ($detailsNodes->getEntreesSorted() as $key => $item): ?>
                        <?php if (!$favoris_entrees->exist($key)): ?>
                            <li>      
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

        <div class="groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="4">
            <p>Sorties</p>
            <ul>
                <?php foreach ($detailsNodes->getSortiesSorted() as $key => $item): ?>
                    <?php if ($favoris_sorties->exist($key)): ?>
                        <li>    
                            <span id="<?php echo 'star_favoris_sorties_' . $key ?>" class="categorie_libelle <?php echo 'sorties_' . $key; ?> <?php echo (count($favoris_sorties) > 1 ) ? 'clickable' : ''; ?>">
                                <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                            </span>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <div class="groupe no_favoris" data-groupe-id="5">
                <p class="extendable">Autres sorties</p>
                <ul style="display: none;">
                    <?php foreach ($detailsNodes->getSortiesSorted() as $key => $item): ?>
                        <?php if (!$favoris_sorties->exist($key)): ?>
                            <li>
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

        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="6">
            <p class="itemcache">Stock théorique fin de mois</p>
            <ul>
                <?php foreach ($detailsNodes->getStocksFin() as $key => $item): ?>
                    <li class="<?php echo ($key != 'revendique') ? ' itemcache' : ' li_gris'; ?>">
                        <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </form>
        </div>
</div>
</div>
