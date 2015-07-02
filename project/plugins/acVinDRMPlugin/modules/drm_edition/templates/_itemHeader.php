<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
$detailNode = $config->details->get($drm->getDetailsConfigKey())->detail;
?>
<div id="colonne_intitules" style="width: 210px">
    <form action="<?php echo url_for('drm_choix_favoris', $formFavoris->getObject()) ?>" method="post">
        <p class="couleur">Produit</p>
        <?php if (!$isTeledeclarationMode): ?>
            <p class="label">Labels</p>
        <?php endif; ?>
        <?php echo $formFavoris->renderHiddenFields(); ?>
        <?php echo $formFavoris->renderGlobalErrors(); ?>
        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="1">
            <p class="itemcache">Stock théorique dbt de mois</p>
            <ul>
                <?php foreach ($detailNode->getStocksDebut() as $key => $item): ?>
                    <li class="<?php echo ($key != 'revendique') ? ' itemcache' : ' li_gris'; ?>">
                        <?php echo $item->getLibelle($drm->getDetailsConfigKey()); ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockdebut_<?php echo $key; ?>" title="Message aide"></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="2">
            <p >Entrées - Favoris</p>
            <ul>
                <?php foreach ($detailNode->getEntrees() as $key => $item): ?>
                    <?php if ($favoris_entrees->exist($key)): ?>
                        <li <?php echo ($item->getFacturable()) ? ' class="facturable"' : ''; ?> >
                            <span id="<?php echo 'star_favoris_entrees_' . $key ?>" class="categorie_libelle <?php echo (count($favoris_entrees) > 1 ) ? 'clickable' : ''; ?>">
                                <?php echo $item->getLibelle($drm->getDetailsConfigKey()); ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="Message aide"></a>
                            </span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe no_favoris" data-groupe-id="3">
            <p class="extendable">Autres entrées</p>
            <ul>
                <?php foreach ($detailNode->getEntrees() as $key => $item): ?>
                    <?php if (!$favoris_entrees->exist($key)): ?>
                        <li <?php echo ($item->getFacturable()) ? ' class="facturable"' : ''; ?> >      
                            <span id="<?php echo 'star_favoris_entrees_' . $key ?>" class="categorie_libelle  <?php echo (count($favoris_entrees) < DRMClient::$drm_max_favoris_by_types_mvt[DRMClient::DRM_TYPE_MVT_ENTREES] ) ? 'clickable' : ''; ?>">
                                <?php echo $item->getLibelle($drm->getDetailsConfigKey()); ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="Message aide"></a>
                            </span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="4">
            <p>Sorties - Favoris</p>
            <ul>
                <?php foreach ($detailNode->getSorties() as $key => $item): ?>
                    <?php if ($favoris_sorties->exist($key)): ?>
                        <li <?php echo ($item->getFacturable()) ? ' class="facturable"' : ''; ?> >    
                            <span id="<?php echo 'star_favoris_sorties_' . $key ?>" class="categorie_libelle <?php echo (count($favoris_sorties) > 1 ) ? 'clickable' : ''; ?>">
                                <?php echo $item->getLibelle($drm->getDetailsConfigKey()); ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="Message aide"></a>
                            </span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe no_favoris" data-groupe-id="5">
            <p class="extendable">Autres sorties</p>
            <ul>
                <?php foreach ($detailNode->getSorties() as $key => $item): ?>
                    <?php if (!$favoris_sorties->exist($key)): ?>
                        <li <?php echo ($item->getFacturable()) ? ' class="facturable"' : ''; ?> >
                            <span id="<?php echo 'star_favoris_sorties_' . $key ?>" class="categorie_libelle <?php echo (count($favoris_sorties) < DRMClient::$drm_max_favoris_by_types_mvt[DRMClient::DRM_TYPE_MVT_SORTIES] ) ? 'clickable' : ''; ?>">
                                <?php echo $item->getLibelle($drm->getDetailsConfigKey()); ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="Message aide"></a>
                            </span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="6">
            <p class="itemcache">Stock théorique fin de mois</p>
            <ul>
                <?php foreach ($detailNode->getStocksFin() as $key => $item): ?>
                    <li class="<?php echo ($key != 'revendique') ? ' itemcache' : ' li_gris'; ?>">
                        <?php echo $item->getLibelle($drm->getDetailsConfigKey()); ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockfin_<?php echo $key; ?>" title="Message aide"></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </form>
</div>
