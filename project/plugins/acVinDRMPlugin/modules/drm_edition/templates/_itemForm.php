<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>
<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
?>
<div class="pull-left" style="width: 150px;">
<div data-hash="<?php echo $detail->getHash() ?>"  class="panel panel-default col_recolte<?php if ($active): ?> active col_active<?php endif; ?> <?php echo ($detail->isEdited()) ? 'col_edited panel-success' : '' ?>" data-input-focus="#drm_detail_sorties_vracsanscontrat" data-cssclass-rectif="<?php echo ($form->getObject()->getDocument()->isRectificative()) ? VersionnerCssClass() : '' ?>" style="margin-right: 10px;">

        <div class="panel-heading panel-heading-xs text-center"><?php echo $form->getObject()->getLibelle("%format_libelle%") ?></div>
        <div class="col_cont list-group">
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
            <form action="<?php echo url_for('drm_edition_update', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
            <div class="list-group-item list-group-item-xs groupe p_gris" data-groupe-id="1">
                <h4 class="form-group form-group-xs hidden <?php echo isVersionnerCssClass($form->getObject(), 'total_debut_mois') ?>">
                    <?php echo $form['total_debut_mois']->render(array('data-val-defaut' => sprintFloat($form->getObject()->total_debut_mois), 'class' => 'num num_float form-control somme_groupe text-right input-xs')) ?>
                </h4>
                    <?php foreach ($form['stocks_debut'] as $key => $subform): ?>
                        <h4 class="form-group form-group-xs <?php if ($key == 'revendique') echo "li_gris";
                    else echo isVersionnerCssClass($form->getObject()->stocks_debut, $key);
                    if ($key != 'revendique') {
                        echo ' hidden';
                    } else {
                        echo ' somme_stock_debut';
                    } ?>">
                        <?php if ($key == 'revendique'): ?>
        <?php echo $form['stocks_debut'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_debut'][$key]->getValue()), 'class' => 'num somme_detail num_float somme_stock_debut bold_on_blur form-control text-right')) ?>
    <?php else: ?>
        <?php echo $form['stocks_debut'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_debut'][$key]->getValue()), 'class' => 'num somme_detail num_float bold_on_blur somme_detail form-control text-right')) ?>
    <?php endif; ?>
                        </h4>
<?php endforeach; ?>
            </div>
            <div class="list-group-item list-group-item-xs groupe p_gris" data-groupe-id="2">
                <h4 class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject(), 'total_entrees') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total_entrees ?>" class="num num_float somme_groupe somme_entrees form-control input-xs text-right" data-val-defaut="<?php echo $form->getObject()->total_entrees ?>" readonly="readonly" />
                </h4>
                <ul class="list-unstyled">
                    <?php foreach ($form['entrees'] as $key => $subform): ?>
                        <?php if ($favoris_entrees->exist($key)): ?>
                            <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                            <?php echo $form['entrees'][$key]->render(array('data-val-defaut' => $form['entrees'][$key]->getValue(),
                                'class' => 'num num_float somme_detail bold_on_blur form-control text-right')); ?>
                                                </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

                <div class="groupe p_gris" data-groupe-id="3">
                    <p style="height: 22px;" class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject(), 'total_entrees') ?> extendable">
                        
                    </p>
                    <ul class="list-unstyled">
                        <?php foreach ($form['entrees'] as $key => $subform): ?>
                            <?php if (!$favoris_entrees->exist($key)): ?>
                                <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                                    <?php echo $form['entrees'][$key]->render(array('data-val-defaut' => $form['entrees'][$key]->getValue(),
                                        'class' => 'num num_float somme_detail bold_on_blur form-control text-right'))
                                    ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="list-group-item list-group-item-xs groupe p_gris" data-groupe-id="4">
                <h4 class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject(), 'total_sorties') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total_sorties ?>" class="num num_float somme_groupe somme_sorties form-control input-xs text-right" data-val-defaut="<?php echo $form->getObject()->total_sorties ?>" readonly="readonly" />
                </h4>
                <ul class="list-unstyled">
                        <?php foreach ($form['sorties'] as $key => $subform): ?>
                            <?php if ($favoris_sorties->exist($key)): ?>
                            <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                            <?php if ($key == "vrac"): ?>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-toggle="modal" data-remote="false" data-target="#ajax-modal" href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>" class="btn btn-default btn-xs" type="button"><span class="glyphicon glyphicon-list-alt"></span></a>
                                    </span>
                                    <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details form-control text-right" data-title="Details des contrats" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>" />
                                </div>
                            <?php elseif ($key == "export"): ?>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a data-toggle="modal" data-remote="false" data-target="#ajax-modal" href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" class="btn btn-default btn-xs" type="button"><span class="glyphicon glyphicon-list-alt"></span></a>
                                        </span>
                                        <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details form-control text-right" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>"/>
                                    </div>
                            <?php elseif ($key == "cooperative"): ?>
                                    <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details" data-title="Details des cooperatives" data-href="<?php echo url_for("drm_cooperative_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->cooperative); ?>"/>
                            <?php else: ?>
                                <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => $form['sorties'][$key]->getValue(), 'class' => 'num num_float somme_detail bold_on_blur form-control text-right')) ?>
                            <?php endif; ?>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                </ul>

                <div class="groupe p_gris" data-groupe-id="5">
                    <p style="height: 22px;" class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject(), 'total_sorties') ?> extendable">
                       
                    </p>
                    <ul class="list-unstyled">
                        <?php foreach ($form['sorties'] as $key => $subform): ?>
                            <?php if (!$favoris_sorties->exist($key)): ?>
                            <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                                <?php if ($key == "vrac"): ?>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a data-toggle="modal" data-remote="false" data-target="#ajax-modal" href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>" class="btn btn-default btn-xs" type="button"><span class="glyphicon glyphicon-list-alt"></span></a>
                                        </span>
                                        <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details form-control text-right" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>" />
                                    </div>
                                <?php elseif ($key == "export"): ?>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a data-toggle="modal" data-remote="false" data-target="#ajax-modal" href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" class="btn btn-default btn-xs" type="button"><span class="glyphicon glyphicon-list-alt"></span></a>
                                        </span>
                                        <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details form-control text-right" data-title="Details des exports" data-href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>"/>
                                    </div>
                                <?php elseif ($key == "cooperative"): ?>
                                                            <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details form-control text-right" data-title="Details des cooperatives" data-href="<?php echo url_for("drm_cooperative_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->cooperative); ?>"/>
                                <?php else: ?>
                                    <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => $form['sorties'][$key]->getValue(), 'class' => 'num num_float somme_detail bold_on_blur form-control text-right')) ?>
                                <?php endif; ?>
                                                    </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- <p><input type="text" value="0" class="num num_float somme_stock_fin" readonly="readonly" /></p>  -->
            <div class="list-group-item list-group-item-xs groupe p_gris" data-groupe-id="6">
                <h4 class="form-group form-group-xs hidden <?php echo isVersionnerCssClass($form->getObject(), 'total') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total ?>" class="num num_float somme_groupe form-control input-xs text-right" readonly="readonly" data-val-defaut="<?php echo sprintFloat($form->getObject()->total) ?>" />
                </h4>
                        <?php foreach ($form['stocks_fin'] as $key => $subform): ?>
                        <h4 class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->stocks_fin, $key);
                        if ($key == 'revendique') echo "li_gris";
                        if ($key != 'revendique') {
                            echo ' hidden';
                        } ?>">
    <?php if ($key == 'revendique'): ?>
        <?php echo $form['stocks_fin'][$key]->render(array('data-val-defaut' => $form['stocks_fin'][$key]->getValue(),
            'class' => 'num num_float somme_detail somme_stock_fin form-control text-right'))
        ?>
    <?php else: ?>
        <?php echo $form['stocks_fin'][$key]->render(array('data-val-defaut' => $form['stocks_fin'][$key]->getValue(),
            'class' => 'num num_float somme_detail form-control text-right'))
        ?>
    <?php endif; ?>
                        </h4>
<?php endforeach; ?>
            </div>

            <div class="col_btn list-group-item list-group-item-xs invisible">
                <button id="valide_<?php echo $detail->getHash() ?>" class="btn_valider btn_majeur btn_colonne_validation btn btn-xs btn-block btn-success" type="submit">Valider</button>
                <button class="btn_reinitialiser btn_annuler btn-block btn_majeur btn btn-xs btn-danger" type="submit">Annuler</button>
            </div>
            </form>
        </div>
</div>
</div>
