<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
?>
<div id="colonne_intitules" style="width: 210px">
    <form action="<?php echo url_for('drm_choix_favoris', $formFavoris->getObject()) ?>" method="post">
        <p class="couleur">Produit</p>
        <p class="label">Labels</p>
        <?php echo $formFavoris->renderHiddenFields(); ?>
        <?php echo $formFavoris->renderGlobalErrors(); ?>
        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="1">
            <p class="itemcache">Stock théorique dbt de mois</p>
            <ul>
                <?php foreach ($config->detail->getStocksDebut() as $key => $item): ?>
                    <li class="<?php echo ($key != 'revendique') ? ' itemcache' : ' li_gris'; ?>">
                        <?php echo $item->getLibelle() ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockdebut_<?php echo $key; ?>" title="Message aide"></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="2">
            <p >Mouvements d'entrées</p>
            <ul>
                <?php foreach ($config->detail->getEntrees() as $key => $item): ?>
                    <?php if ($favoris_entrees->exist($key)): ?>
                        <li <?php echo ($item->getFacturable()) ? ' class="facturable"' : ''; ?> >
                            <?php echo $formFavoris['favoris_entrees_' . $key]->render(); ?>
                            <?php echo $item->getLibelle() ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="Message aide"></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe" data-groupe-id="3">
            <p class="extendable">Autres entrées</p>
            <ul>
                <?php foreach ($config->detail->getEntrees() as $key => $item): ?>
                    <?php if (!$favoris_entrees->exist($key)): ?>
                        <li <?php echo ($item->getFacturable()) ? ' class="facturable"' : ''; ?> >                        
                            <?php echo $formFavoris['favoris_entrees_' . $key]->render(); ?>
                            <?php echo $item->getLibelle() ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="Message aide"></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="4">
            <p>Mouvements de sorties</p>
            <ul>
                <?php foreach ($config->detail->getSorties() as $key => $item): ?>
                    <?php if ($favoris_sorties->exist($key)): ?>
                        <li <?php echo ($item->getFacturable()) ? ' class="facturable"' : ''; ?> >                        
                            <?php echo $formFavoris['favoris_sorties_' . $key]->render(); ?>
                            <?php echo $item->getLibelle() ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="Message aide"></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe" data-groupe-id="5">
            <p class="extendable">Autres sorties</p>
            <ul>
                <?php foreach ($config->detail->getSorties() as $key => $item): ?>
                    <?php if (!$favoris_sorties->exist($key)): ?>
                        <li <?php echo ($item->getFacturable()) ? ' class="facturable"' : ''; ?> >
                            <?php echo $formFavoris['favoris_sorties_' . $key]->render(); ?>
                            <?php echo $item->getLibelle() ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="Message aide"></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groupe groupe_ouvert groupe_bloque" data-groupe-id="6">
            <p class="itemcache">Stock théorique fin de mois</p>
            <ul>
                <?php foreach ($config->detail->getStocksFin() as $key => $item): ?>
                    <li class="<?php
                    if ($key != 'revendique')
                        echo ' itemcache';
                    else
                        echo ' li_gris'
                        ?>"><?php echo $item->getLibelle() ?>&nbsp;(<span class="unite">hl</span>)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockfin_<?php echo $key; ?>" title="Message aide"></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </form>
</div>
