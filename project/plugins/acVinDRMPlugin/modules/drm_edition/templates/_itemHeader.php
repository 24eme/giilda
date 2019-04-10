<?php
use_helper('DRM');
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
$etablissement = $drm->getEtablissement();
$isAcquitteMode = ($detailsNodes->getKey() == DRM::DETAILS_KEY_ACQUITTE);
?>
<div id="colonne_intitules" style="width: 210px">
    <form action="<?php echo url_for('drm_choix_favoris', array('identifiant' => $formFavoris->getObject()->getIdentifiant(),
    'periode_version' => $formFavoris->getObject()->getPeriodeAndVersion(),
    'details' => $detailsNodes->getKey())) ?>" method="post">
        <p class="couleur">Produit</p>
        <?php echo $formFavoris->renderHiddenFields(); ?>
        <?php echo $formFavoris->renderGlobalErrors(); ?>
        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="1">
            <p class="itemcache">Stock théorique dbt de mois</p>
            <ul>
                <?php foreach ($detailsNodes->getStocksDebut() as $key => $item): ?>
                    <li class="<?php echo ($key != 'revendique') ? ' itemcache' : ' li_gris'; ?>">
                        <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" style="float: right; padding: 0 10px 0 0;" title="<?php echo getHelpMsgText('drm_mouvements_aide1'); ?>"></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="2">
            <p>Entrées&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" style="float: right; padding: 0 5px 0 0;" title="<?php echo getHelpMsgText('drm_mouvements_aide2'); ?>"></a></p>
            <ul>
                <?php foreach ($detailsNodes->getEntreesSorted() as $key => $item): ?>
                    <?php if ($item->isWritableForEtablissement($etablissement)): ?>
                        <?php if ($favoris_entrees->exist($key)): ?>
                            <li>
                                <span id="<?php echo 'star_favoris_entrees_' . $key ?>" class="categorie_libelle <?php echo 'entrees_' . $key; ?> <?php echo (count($favoris_entrees) > 1 && !$isAcquitteMode) ? 'clickable' : ''; ?>">
                                    <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                                </span>
                                &nbsp;<a href="" class="msg_aide_drm  icon-msgaide" style="float: right; padding: 0 10px 0 0;" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <div class="groupe no_favoris" data-groupe-id="3">
              <?php if(!$isAcquitteMode): ?>
                <p class="extendable">Autres entrées</p>
              <?php endif; ?>
                <ul style="display: none;">
                    <?php foreach ($detailsNodes->getEntreesSorted() as $key => $item): ?>
                        <?php if ($item->isWritableForEtablissement($etablissement)): ?>
                            <?php if (!$favoris_entrees->exist($key)): ?>
                                <li>
                                    <span id="<?php echo 'star_favoris_entrees_' . $key ?>" class="categorie_libelle <?php echo 'entrees_' . $key; ?>  <?php echo (count($favoris_entrees) < DRMClient::$drm_max_favoris_by_types_mvt[DRMClient::DRM_TYPE_MVT_ENTREES] ) ? 'clickable' : ''; ?>">
                                        <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                                    </span>
                                    &nbsp;<a href="" class="msg_aide_drm  icon-msgaide" style="float: right; padding: 0 10px 0 0;" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque favoris" data-groupe-id="4">
            <p>Sorties&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" style="float: right; padding: 0 5px 0 0;" title="<?php echo getHelpMsgText('drm_mouvements_aide3'); ?>"></a></p>
            <ul>
                <?php foreach ($detailsNodes->getSortiesSorted() as $key => $item): ?>
                    <?php if ($item->isWritableForEtablissement($etablissement)): ?>
                        <?php if ($favoris_sorties->exist($key)): ?>
                            <li>
                                <span id="<?php echo 'star_favoris_sorties_' . $key ?>" class="categorie_libelle <?php echo 'sorties_' . $key; ?> <?php echo (count($favoris_sorties) > 1  && !$isAcquitteMode) ? 'clickable' : ''; ?>">
                                    <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)

                                </span>&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" style="float: right; padding: 0 10px 0 0;" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>

                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <div class="groupe no_favoris" data-groupe-id="5">
              <?php if(!$isAcquitteMode): ?>
                <p class="extendable">Autres sorties</p>
              <?php endif; ?>
                <ul style="display: none;">
                    <?php foreach ($detailsNodes->getSortiesSorted() as $key => $item): ?>
                        <?php if ($item->isWritableForEtablissement($etablissement)): ?>
                            <?php if (!$favoris_sorties->exist($key)): ?>
                                <li>
                                    <span id="<?php echo 'star_favoris_sorties_' . $key ?>" class="categorie_libelle <?php echo 'sorties_' . $key; ?> <?php echo (count($favoris_sorties) < DRMClient::$drm_max_favoris_by_types_mvt[DRMClient::DRM_TYPE_MVT_SORTIES] ) ? 'clickable' : ''; ?>">
                                        <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)
                                    </span>

                                    &nbsp;<a href="" class="msg_aide_drm  icon-msgaide" style="float: right; padding: 0 10px 0 0;" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="<?php echo $item->getLibelleLong(); ?>"></a>

                                </li>
                            <?php endif; ?>
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
                        <?php echo $item->getLibelle(); ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" style="float: right; padding: 0 10px 0 0;" title="<?php echo getHelpMsgText('drm_mouvements_aide4'); ?>"></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </form>
</div>
