<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>
<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
$etablissement = $drm->getEtablissement();
$drmTeledeclaree = $detail->getDocument()->teledeclare;
?>
<div data-hash="<?php echo $detail->getHash() ?>" class="col_recolte<?php if ($active): ?> col_active<?php endif; ?> <?php echo ($detail->isEdited()) ? 'col_edited' : '' ?>" data-input-focus="#drm_detail_sorties_vracsanscontrat" data-cssclass-rectif="<?php echo ($form->getObject()->getDocument()->isRectificative()) ? VersionnerCssClass() : '' ?>">
    <form action="<?php echo url_for('drm_edition_update', array('sf_subject' => $form->getObject(), 'details' => $detailsKey)) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
        <h2 class="titre_produit"><?php echo $form->getObject()->getLibelle("%format_libelle%") ?></h2>
        <div class="col_cont">
            <div class="groupe" data-groupe-id="1">
                <p class="itemcache <?php echo isVersionnerCssClass($form->getObject(), 'total_debut_mois') ?>">
                    <?php echo $form['total_debut_mois']->render(array('data-val-defaut' => sprintFloat($form->getObject()->total_debut_mois), 'class' => 'num num_float somme_groupe test')) ?>
                </p>
                <ul>
                    <?php foreach ($form['stocks_debut'] as $key => $subform):  ?>

                        <li class="<?php
                        if ($key == 'revendique')
                            echo "li_gris";
                        else
                            echo isVersionnerCssClass($form->getObject()->stocks_debut, $key);
                        if ($key != 'revendique') {
                            echo ' itemcache';
                        } else {
                            echo ' somme_stock_debut';
                        }
                        ?>">
                                <?php if ($key == 'revendique'): ?>
                                    <?php echo $form['stocks_debut'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_debut'][$key]->getValue()), 'class' => 'num somme_detail num_float somme_stock_debut bold_on_blur')) ?>
                                <?php else: ?>
                                    <?php echo $form['stocks_debut'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_debut'][$key]->getValue()), 'class' => 'num somme_detail num_float bold_on_blur')) ?>
                                <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="groupe p_gris" data-groupe-id="2">
                <p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_entrees') ?>">
                    <input type="text" value="<?php echoFloat($form->getObject()->total_entrees) ?>" class="num num_float somme_groupe somme_entrees" data-val-defaut="<?php echo $form->getObject()->total_entrees ?>" readonly="readonly" />
                </p>
                <ul>
                    <?php foreach ($form['entrees'] as $key => $subform): ?>

                        <?php
                            if (!$detail->getConfig()->isWritableForEtablissement('entrees', $key, $etablissement, $drmTeledeclaree)){ continue; }
                             if ($detail->getConfig()->isWritableForEtablissement('entrees', $key, $etablissement)):
                             if ($favoris_entrees->exist($key)): ?>
                                <li class="<?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                                    <?php
                                    echo $form['entrees'][$key]->render(array('data-val-defaut' => $form['entrees'][$key]->getValue(),
                                        'class' => 'num num_float somme_detail bold_on_blur'));
                                    ?>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

                <div class="groupe p_gris" data-groupe-id="3">
                    <p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_entrees') ?> extendable">

                    </p>
                    <ul>
                        <?php foreach ($form['entrees'] as $key => $subform):
                                if (!$detail->getConfig()->isWritableForEtablissement('entrees', $key, $etablissement, $drmTeledeclaree)){ continue; }
                                if ($detail->getConfig()->isWritableForEtablissement('entrees', $key, $etablissement)):
                               if (!$favoris_entrees->exist($key)): ?>
                                    <li class="<?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                                        <?php
                                        echo $form['entrees'][$key]->render(array('data-val-defaut' => $form['entrees'][$key]->getValue(),
                                            'class' => 'num num_float somme_detail bold_on_blur'))
                                        ?>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="groupe p_gris" data-groupe-id="4">
                <p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_sorties') ?>">
                    <input type="text" value="<?php echoFloat($form->getObject()->total_sorties) ?>" class="num num_float somme_groupe somme_sorties" data-val-defaut="<?php echo $form->getObject()->total_sorties ?>" readonly="readonly" />
                </p>
                <ul>
                    <?php foreach ($form['sorties'] as $key => $subform):
                      if (!$detail->getConfig()->isWritableForEtablissement('sorties', $key, $etablissement, $drmTeledeclaree)){ continue; }
                        if ($detail->getConfig()->isWritableForEtablissement('sorties', $key, $etablissement)):
                           if ($favoris_sorties->exist($key)): ?>
                                <li class="<?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                                    <?php if ($key == "vrac"):
                                        $isDisabled = (($detail->getCVOTaux() <= 0 ) && ($detail->getCertification()->getKey() != "IGP_VALDELOIRE")) || ($detail->getCertification()->getKey() == "AUTRES");
                                        ?>
                                        <input type="text" class="btn_detail num num_float somme_detail input_lien drm_details  <?php echo ($isDisabled)? 'opacity40' : '' ?>" data-title="Details des contrats" data-href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>" <?php echo ($isDisabled)? 'disabled="disabled"' : '' ?> />
                                    <?php elseif ($key == "export"):
                                        $isDisabled = ($detail->getCertification()->getKey() == "AUTRES");
                                        ?>
                                        <input type="text" class="btn_detail num num_float somme_detail input_lien drm_details <?php echo ($isDisabled)? 'opacity40' : '' ?>" data-title="Details des exports" data-href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>" <?php echo ($isDisabled)? 'disabled="disabled"' : '' ?> />
                                    <?php elseif ($key == "cooperative"): ?>
                                        <input type="text" class="btn_detail num num_float somme_detail input_lien drm_details" data-title="Details des cooperatives" data-href="<?php echo url_for("drm_cooperative_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->cooperative); ?>"/>
                                    <?php else: ?>
                                        <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => $form['sorties'][$key]->getValue(), 'class' => 'num num_float somme_detail bold_on_blur')) ?>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

                <div class="groupe p_gris" data-groupe-id="5">
                    <p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_sorties') ?> extendable">

                    </p>
                    <ul>
                        <?php foreach ($form['sorties'] as $key => $subform):
                            if (!$detail->getConfig()->isWritableForEtablissement('sorties', $key, $etablissement, $drmTeledeclaree)){ continue; }
			      if ($detail->getConfig()->isWritableForEtablissement('sorties', $key, $etablissement)): ?>
                                <?php if (!$favoris_sorties->exist($key)): ?>
                                    <li class="<?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                                        <?php if ($key == "vrac"): ?>
                                            <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details <?php echo (!$detail->getCVOTaux()) ? 'opacity40' : '' ?>" data-title="Details des contrats" data-href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>"  <?php echo (!$detail->getCVOTaux()) ? 'disabled="disabled"' : '' ?>  />
                                        <?php elseif ($key == "export"): ?>
                                            <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details" data-title="Details des exports" data-href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>"/>
                                        <?php elseif ($key == "cooperative"): ?>
                                            <input type="text" class="btn_detail num num_float somme_detail bold_on_blur input_lien drm_details" data-title="Details des cooperatives" data-href="<?php echo url_for("drm_cooperative_details", $form->getObject()) ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->cooperative); ?>"/>
                                        <?php else: ?>
                                            <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => $form['sorties'][$key]->getValue(), 'class' => 'num num_float somme_detail bold_on_blur')) ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- <p><input type="text" value="0" class="num num_float somme_stock_fin" readonly="readonly" /></p>  -->
            <div class="groupe p_gris" data-groupe-id="6">
                <p class="itemcache <?php echo isVersionnerCssClass($form->getObject(), 'total') ?>">
                    <input type="text" value="<?php echo $form->getObject()->total ?>" class="num num_float somme_groupe" readonly="readonly" data-val-defaut="<?php echo sprintFloat($form->getObject()->total) ?>" />
                </p>
                <ul>
                    <?php foreach ($form['stocks_fin'] as $key => $subform): ?>
                        <li class="<?php
                        echo isVersionnerCssClass($form->getObject()->stocks_fin, $key);
                        if ($key == 'revendique')
                            echo "li_gris";
                        if ($key != 'revendique' && $key != 'final') {
                            echo ' itemcache';
                        }
                        ?>">
                                <?php if ($key == 'revendique'): ?>
                                    <?php
                                    echo $form['stocks_fin'][$key]->render(array('data-val-defaut' => $form['stocks_fin'][$key]->getValue(),
                                        'class' => 'num num_float somme_detail somme_stock_fin'))
                                    ?>
                                <?php else: ?>
                                    <?php
                                    echo $form['stocks_fin'][$key]->render(array('data-val-defaut' => $form['stocks_fin'][$key]->getValue(),
                                        'class' => 'num num_float somme_detail'))
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
        </div>
    </form>
</div>
