<?php use_helper('Float'); ?>
<?php use_helper('Rectificative'); ?>
<div id="col_recolte_<?php echo $form->getObject()->getKey() ?>" class="col_recolte<?php if ($active): ?> col_active<?php endif; ?>" data-input-focus="#drm_detail_sorties_vracsanscontrat" data-cssclass-rectif="<?php echo ($form->getObject()->getDocument()->isRectificative()) ? rectifierCssClass() : '' ?>">
    <form action="<?php echo url_for('drm_edition_update', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
        <h2><?php echo $form->getObject()->getLibelle("%g% %a% %co% %ce%") ?></h2>
        <div class="col_cont">
            <p class="label" style="font-size: 12px; text-align: center;">
   <?php echo $form->getObject()->getLabelsLibelle() ?> <?php echo $form->getObject()->label_supplementaire ?> (&nbsp;<a href="<?php echo url_for("drm_edition_produit_addlabel", $form->getObject()) ?>">éditer</a>&nbsp;)
            </p>
            <div class="groupe" data-groupe-id="1">
                <p class="itemcache <?php echo isRectifierCssClass($form->getObject(), 'total_debut_mois') ?>">
                    <?php echo $form['total_debut_mois']->render(array('data-val-defaut' => sprintFloat($form->getObject()->total_debut_mois), 'class' => 'num num_float somme_groupe somme_stock_debut test')) ?>
                </p>
                <ul>
                    <?php foreach($form['stocks_debut'] as $key => $subform): ?>
                    <li class="<?php echo isRectifierCssClass($form->getObject()->stocks_debut, $key); if ($key != 'revendique') { echo ' itemcache';}  ?>">
   <?php echo $form['stocks_debut'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_debut'][$key]->getValue()), 'class' => 'num somme_detail num_float')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="groupe" data-groupe-id="2">
                <p class="<?php echo isRectifierCssClass($form->getObject(), 'total_entrees') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total_entrees ?>" class="num num_float somme_groupe somme_entrees" data-val-defaut="<?php echo $form->getObject()->total_entrees ?>" readonly="readonly" />
                </p>
                <ul>
                    <?php foreach($form['entrees'] as $key => $subform): ?>
                    <li class="<?php echo isRectifierCssClass($form->getObject()->entrees, $key) ?>">
                        <?php echo $form['entrees'][$key]->render(array('data-val-defaut' => $form['entrees'][$key]->getValue(),
                                                                        'class' => 'num num_float somme_detail')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="groupe" data-groupe-id="3">
                <p class="<?php echo isRectifierCssClass($form->getObject(), 'total_sorties') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total_sorties ?>" class="num num_float somme_groupe somme_sorties" data-val-defaut="<?php echo $form->getObject()->total_sorties ?>" readonly="readonly" />
                </p>
                <ul>
                    <?php foreach($form['sorties'] as $key => $subform): ?>
                    <li class="<?php echo isRectifierCssClass($form->getObject()->sorties, $key) ?>">
                    	<?php if($key=="vrac"): ?>
                                <input type="text" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>" />
                                <a class="btn_majeur btn_modifier drm_details drm_details_sortie_vrac" href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>">
                                    &nbsp;
                                </a>
                                <input type="hidden" class="drm_details_sortie_vrac_vol num num_float num_light"
                                       data-val-defaut="<?php echo $detail->sorties->vrac > 0 ? $detail->sorties->vrac : "0" ?>"
                                       value="<?php echo $detail->sorties->vrac > 0 ? $detail->sorties->vrac  : "0"; ?>"
                                       name="drm_detail[sorties][vrac]" /> 
                    	<?php elseif($key=="export"): ?>
                                <input type="text" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>" />
                    		<a class="btn_majeur btn_modifier drm_details drm_details_sortie_export" href="<?php echo url_for("drm_export_details", $form->getObject()) ?>">
                    			 &nbsp;
                    		</a>
                                <input type="hidden" class="drm_details_sortie_export_vol num num_float num_light"
                                       data-val-defaut="<?php echo $detail->sorties->export > 0 ? $detail->sorties->export  : "0"; ?>" 
                                       value="<?php echo $detail->sorties->export > 0 ? $detail->sorties->export  : "0"; ?>" name="drm_detail[sorties][export]"/> 
                    	<?php elseif($key=="cooperative"): ?>
                                <input type="text" readonly="readonly" value="<?php echoFloat($detail->sorties->cooperative); ?>" />
                    		<a  class="btn_majeur btn_modifier drm_details drm_details_sortie_cooperative" href="<?php echo url_for("drm_cooperative_details", $form->getObject()) ?>">
                                        &nbsp;
                    		</a>
                                <input type="hidden" class="drm_details_sortie_cooperative_vol num num_float num_light"
                                       data-val-defaut="<?php echo $detail->sorties->cooperative > 0 ? $detail->sorties->cooperative  : "0"; ?>"
                                       value="<?php echo $detail->sorties->cooperative > 0 ? $detail->sorties->cooperative  : "0"; ?>"
                                       name="drm_detail[sorties][cooperative]"/>
                    	<?php else: ?>
                        <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => $form['sorties'][$key]->getValue(),
                                                                        'class' => 'num num_float somme_detail')) ?>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- <p><input type="text" value="0" class="num num_float somme_stock_fin" readonly="readonly" /></p>  -->
            <div class="groupe" data-groupe-id="4">
                <p class="<?php echo isRectifierCssClass($form->getObject(), 'total') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total ?>" class="num num_float somme_stock_fin" readonly="readonly" data-val-defaut="<?php echo sprintFloat($form->getObject()->total) ?>" />
                </p>
                <ul>
                    <?php foreach($form['stocks_fin'] as $key => $subform): ?>
                    <li class="<?php echo isRectifierCssClass($form->getObject()->stocks_fin, $key) ?>">
                        <?php echo $form['stocks_fin'][$key]->render(array('data-val-defaut' => $form['stocks_fin'][$key]->getValue(),
                                                                        'class' => 'num num_float')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col_btn">
                <button class="btn_valider btn_majeur" type="submit">Valider</button>
                <button class="btn_reinitialiser btn_annuler btn_majeur" style="margin-top: 8px;" type="submit">Annuler</button>
            </div>
        </div>
    </form>
</div>