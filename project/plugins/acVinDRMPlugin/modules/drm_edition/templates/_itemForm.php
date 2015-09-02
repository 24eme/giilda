<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>
<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
?>
<div class="pull-left" style="width: 200px; padding-right: 20px;">
<div data-hash="<?php echo $detail->getHash() ?>"  class="panel panel-default col_recolte<?php if ($active): ?> col_active<?php endif; ?> <?php echo ($detail->isEdited()) ? 'col_edited' : '' ?>" data-input-focus="#drm_detail_sorties_vracsanscontrat" data-cssclass-rectif="<?php echo ($form->getObject()->getDocument()->isRectificative()) ? VersionnerCssClass() : '' ?>">

        <div class="panel-heading titre_produit"><?php echo $form->getObject()->getLibelle("%format_libelle%") ?></div>
        <div class="col_cont panel-body">
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
            <form action="<?php echo url_for('drm_edition_update', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
            <div class="groupe" data-groupe-id="1">
                <p class="form-group itemcache <?php echo isVersionnerCssClass($form->getObject(), 'total_debut_mois') ?>">
                    <?php echo $form['total_debut_mois']->render(array('data-val-defaut' => sprintFloat($form->getObject()->total_debut_mois), 'class' => 'num num_float somme_groupe form-control input-md text-right')) ?>
                </p>
                <ul class="list-unstyled">
                    <?php foreach ($form['stocks_debut'] as $key => $subform): ?>
                        <li class="form-group <?php if ($key == 'revendique') echo "li_gris";
                    else echo isVersionnerCssClass($form->getObject()->stocks_debut, $key);
                    if ($key != 'revendique') {
                        echo ' itemcache';
                    } else {
                        echo ' somme_stock_debut';
                    } ?>">
                        <?php if ($key == 'revendique'): ?>
        <?php echo $form['stocks_debut'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_debut'][$key]->getValue()), 'class' => 'num somme_detail num_float somme_stock_debut bold_on_blur form-control input-md text-right')) ?>
    <?php else: ?>
        <?php echo $form['stocks_debut'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_debut'][$key]->getValue()), 'class' => 'num somme_detail num_float bold_on_blur form-control input-md text-right')) ?>
    <?php endif; ?>
                        </li>
<?php endforeach; ?>
                </ul>
            </div>
            <div class="groupe p_gris" data-groupe-id="2">
                <p class="form-group <?php echo isVersionnerCssClass($form->getObject(), 'total_entrees') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total_entrees ?>" class="num num_float somme_groupe somme_entrees form-control input-md text-right" data-val-defaut="<?php echo $form->getObject()->total_entrees ?>" readonly="readonly" />
                </p>
                <ul class="list-unstyled">
                    <?php foreach ($form['entrees'] as $key => $subform): ?>
                        <?php if ($favoris_entrees->exist($key)): ?>
                            <li class="form-group <?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                            <?php echo $form['entrees'][$key]->render(array('data-val-defaut' => $form['entrees'][$key]->getValue(),
                                'class' => 'num num_float somme_detail bold_on_blur form-control input-md text-right')); ?>
                                                </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

                <div class="groupe p_gris" data-groupe-id="3">
                    <p style="height: 34px;" class="form-group <?php echo isVersionnerCssClass($form->getObject(), 'total_entrees') ?> extendable">
                        
                    </p>
                    <ul class="list-unstyled">
                        <?php foreach ($form['entrees'] as $key => $subform): ?>
                            <?php if (!$favoris_entrees->exist($key)): ?>
                                <li class="form-group <?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                                    <?php echo $form['entrees'][$key]->render(array('data-val-defaut' => $form['entrees'][$key]->getValue(),
                                        'class' => 'num num_float somme_detail bold_on_blur form-control input-md text-right'))
                                    ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="groupe p_gris" data-groupe-id="4">
                <p class="form-group <?php echo isVersionnerCssClass($form->getObject(), 'total_sorties') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total_sorties ?>" class="num num_float somme_groupe somme_sorties form-control input-md text-right" data-val-defaut="<?php echo $form->getObject()->total_sorties ?>" readonly="readonly" />
                </p>
                <ul class="list-unstyled">
                        <?php foreach ($form['sorties'] as $key => $subform): ?>
                            <?php if ($favoris_sorties->exist($key)): ?>
                            <li class="form-group <?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                                <?php if ($key == "vrac"): ?>
                                    <input type="text" class="btn_detail num num_float somme_detail input_lien drm_details form-control input-md text-right" data-title="Details des contrats" data-href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>" />
                            <?php elseif ($key == "export"): ?>
                                    <input type="text" class="btn_detail num num_float somme_detail input_lien drm_details form-control input-md text-right" data-title="Details des exports" data-href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>"/>
                            <?php elseif ($key == "cooperative"): ?>
                                                        <input type="text" class="btn_detail num num_float somme_detail input_lien drm_details" data-title="Details des cooperatives" data-href="<?php echo url_for("drm_cooperative_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->cooperative); ?>"/>
                            <?php else: ?>
                                <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => $form['sorties'][$key]->getValue(), 'class' => 'num num_float somme_detail bold_on_blur form-control input-md text-right')) ?>
                            <?php endif; ?>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                </ul>

                <div class="groupe p_gris" data-groupe-id="5">
                    <p style="height: 34px;" class="form-group <?php echo isVersionnerCssClass($form->getObject(), 'total_sorties') ?> extendable">
                       
                    </p>
                    <ul class="list-unstyled">
                        <?php foreach ($form['sorties'] as $key => $subform): ?>
                            <?php if (!$favoris_sorties->exist($key)): ?>
                            <li class="form-group <?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                                <?php if ($key == "vrac"): ?>
                                    <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details form-control input-md text-right" data-title="Details des contrats" data-href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>" />
                            <?php elseif ($key == "export"): ?>
                                    <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details form-control input-md text-right" data-title="Details des exports" data-href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>"/>
                                <?php elseif ($key == "cooperative"): ?>
                                                            <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details form-control input-md text-right" data-title="Details des cooperatives" data-href="<?php echo url_for("drm_cooperative_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->cooperative); ?>"/>
                                <?php else: ?>
                                    <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => $form['sorties'][$key]->getValue(), 'class' => 'num num_float somme_detail bold_on_blur form-control input-md text-right')) ?>
                                <?php endif; ?>
                                                    </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- <p><input type="text" value="0" class="num num_float somme_stock_fin" readonly="readonly" /></p>  -->
            <div class="groupe p_gris" data-groupe-id="6">
                <p class="form-group itemcache <?php echo isVersionnerCssClass($form->getObject(), 'total') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total ?>" class="num num_float somme_groupe form-control input-md text-right" readonly="readonly" data-val-defaut="<?php echo sprintFloat($form->getObject()->total) ?>" />
                </p>
                <ul class="list-unstyled">
                        <?php foreach ($form['stocks_fin'] as $key => $subform): ?>
                        <li class="form-group <?php echo isVersionnerCssClass($form->getObject()->stocks_fin, $key);
                        if ($key == 'revendique') echo "li_gris";
                        if ($key != 'revendique') {
                            echo ' itemcache';
                        } ?>">
    <?php if ($key == 'revendique'): ?>
        <?php echo $form['stocks_fin'][$key]->render(array('data-val-defaut' => $form['stocks_fin'][$key]->getValue(),
            'class' => 'num num_float somme_detail somme_stock_fin form-control input-md text-right'))
        ?>
    <?php else: ?>
        <?php echo $form['stocks_fin'][$key]->render(array('data-val-defaut' => $form['stocks_fin'][$key]->getValue(),
            'class' => 'num num_float somme_detail form-control input-md text-right'))
        ?>
    <?php endif; ?>
                        </li>
<?php endforeach; ?>
                </ul>
            </div>

            <div class="col_btn">
                <button id="valide_<?php echo $detail->getHash() ?>" class="btn_valider btn_majeur btn_colonne_validation" type="submit">Valider</button>
                <button class="btn_reinitialiser btn_annuler btn_majeur" style="margin-top: 8px;" type="submit">Annuler</button>
            </div>
            </form>
        </div>
</div>
</div>
