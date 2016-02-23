<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>
<?php
$favoris_entrees = $favoris->entrees;
$favoris_sorties = $favoris->sorties;
$tabindex = $numProduit * 100 ;
?>
<div class="pull-left" style="width: 150px;">
    <div data-hash="<?php echo $detail->getHash() ?>"  class="panel panel-default col_recolte<?php if ($active): ?> active col_active<?php endif; ?> <?php echo ($detail->isEdited()) ? 'col_edited panel-success' : '' ?>" data-input-focus="<?php echo $tabindex; ?>" data-cssclass-rectif="<?php echo ($form->getObject()->getDocument()->isRectificative()) ? VersionnerCssClass() : '' ?>" style="margin-right: 10px;">
        <div class="panel-heading head panel-heading-xs text-center"><?php echo $form->getObject()->getLibelle("%format_libelle%") ?></div>
        <div class="col_cont list-group">
            <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
            <form action="<?php echo url_for('drm_edition_update', $form->getObject()) ?>" method="post">
                <?php echo $form->renderHiddenFields(); ?>
                <div class="list-group-item list-group-item-xs groupe p_gris" data-groupe-id="1">
                    <h4 class="form-group form-group-xs" style="height: 17px;">
                        <span>&nbsp;</span>
                    </h4>
                    <?php foreach ($form['stocks_debut'] as $key => $subform): ?>
                        <?php
                        if ($key != 'instance'):
                            $class = ($key == 'dont_revendique') ? ' somme_stock_debut_dont_revendique ' : ' somme_stock_debut ';
                            ?>
                            <h4 class="form-group form-group-xs <?php // echo isVersionnerCssClass($subform->getObject(), $key)                   ?>">
                                <?php
                                $allAttributes = array('data-val-defaut' => $form['stocks_debut'][$key]->getValue(), 'class' => $form['stocks_debut'][$key]->getWidget()->getAttribute('class') . $class . ' somme_detail ');
                                if (!$subform->getWidget()->getAttribute('readonly')) {
                                    $allAttributes = array_merge($allAttributes, array('tabindex' => $tabindex));
                                    $tabindex++;
                                }
                                echo $form['stocks_debut'][$key]->render($allAttributes);
                                ?>
                            </h4> 
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
                <div class="list-group-item list-group-item-xs groupe p_gris" data-groupe-id="2">
                    <h4 class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject(), 'total_entrees') ?>">
                        <input type="text" value="<?php echo $form->getObject()->total_entrees ?>" class="input-float somme_entrees_recolte form-control input-xs text-right" data-val-defaut="<?php echo $form->getObject()->total_entrees ?>" readonly="readonly" />
                        <input type="text" value="0" class="input-float  somme_entrees_revendique form-control input-xs text-right" data-val-defaut="0" readonly="readonly" style="display: none;" />

                    </h4>
                    <ul class="list-unstyled">
                        <?php foreach ($form['entrees'] as $key => $subform): ?>
                            <?php
                            if (!$favoris_entrees->exist($key)): continue;
                            endif;
                            $class = ($detail->getConfig()->get('entrees')->get($key)->revendique) ? " revendique_entree " : "";
                            $class .= ($detail->getConfig()->get('entrees')->get($key)->recolte) ? " recolte_entree " : "";
                            ?>
                            <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                                <?php
                                $isWritable = ($detail->getConfig()->get('entrees')->get($key)->writable && !$subform->getWidget()->getAttribute('readonly'));
                                $allAttributes = array('data-val-defaut' => $subform->getValue(), 'class' => $subform->getWidget()->getAttribute('class') . $class . ' somme_detail bold_on_blur');
                                if ($isWritable) {
                                    $allAttributes = array_merge($allAttributes, array('tabindex' => $tabindex));
                                    $tabindex++;
                                }
                                echo $subform->render($allAttributes);
                                ?>
                            </li>
                            <?php
                        endforeach;
                        ?> 
                        <li class="form-group form-group-xs groupe no_favoris" style="height: 21px;">
                            <a class="btn btn-default form-control raccourcis_ouvrir" style="border-color: #fff;" tabindex="<?php echo $tabindex ?>" data-groupe-id="3" ></a>
                        </li>                        
                    </ul>

                    <div class="groupe p_gris" data-groupe-id="3">
                        <p  class="form-group form-group-xs extendable"></p>
                        <ul class="list-unstyled">
                            <?php
                            $isfirst = true;
                            foreach ($form['entrees'] as $key => $subform):
                                ?>
                                <?php
                                if ($favoris_entrees->exist($key)): continue;
                                endif;
                                $class = $subform->getWidget()->getAttribute('class') . ' somme_detail bold_on_blur ';
                                $class.= ($detail->getConfig()->get('entrees')->get($key)->revendique) ? " revendique_entree " : "";
                                $class.= ($detail->getConfig()->get('entrees')->get($key)->recolte) ? " recolte_entree " : "";
                                $isWritable = ($detail->getConfig()->get('entrees')->get($key)->writable && !$subform->getWidget()->getAttribute('readonly'));
                                $allAttributes = array('data-val-defaut' => $subform->getValue(), 'data-previousfocus' => $tabindex);
                                if ($isWritable) {
                                    if ($isfirst) {
                                        $class.= ' tabIndexOnPrevious ';
                                        $isfirst = false;
                                    }
                                    $allAttributes = array_merge($allAttributes, array('tabindex' => $tabindex));
                                    $tabindex++;
                                }
                                $allAttributes = array_merge($allAttributes, array('class' => $class));
                                ?>
                                <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                                    <?php echo $subform->render($allAttributes); ?>
                                </li>
                                <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="list-group-item list-group-item-xs groupe p_gris" data-groupe-id="4">
                    <h4 class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject(), 'total_sorties') ?>">
                        <input type="text" value="<?php echo $form->getObject()->total_sorties ?>" class="input-float somme_sorties_recolte form-control input-xs text-right" data-val-defaut="<?php echo $form->getObject()->total_sorties ?>" readonly="readonly" />
                        <input type="text" value="0" class="input-float somme_sorties_revendique form-control input-xs text-right" data-val-defaut="0" readonly="readonly"  style="display: none;" />

                    </h4>
                    <ul class="list-unstyled">
                        <?php foreach ($form['sorties'] as $key => $subform): ?>
                            <?php
                            if ($favoris_sorties->exist($key)):
                                $class = $subform->getWidget()->getAttribute('class') . ' somme_detail bold_on_blur ';
                                $class .= ($detail->getConfig()->get('sorties')->get($key)->revendique) ? " revendique_sortie " : "";
                                $class .= ($detail->getConfig()->get('sorties')->get($key)->recolte) ? " recolte_sortie " : "";
                                ?>
                                <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                                    <?php if ($key == "vrac"): ?>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a id="lien_sorties_vrac_details_<?php echo $detail->getHashForKey() ?>" data-toggle="modal" data-remote="false" data-target="#ajax-modal" href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>" class="btn btn-default btn-xs" type="button"><span class="glyphicon glyphicon-list-alt"  tabindex="<?php echo $tabindex; ?>" ></span></a>
                                            </span>
                                            <input type="text" id="input_sortie_vrac_<?php echo $detail->getHashForKey() ?>" data-hash="<?php echo $detail->getHash() ?>" data-pointer="#lien_sorties_vrac_details_<?php echo $detail->getHashForKey() ?>" class="btn_detail pointer input-float somme_detail bold_on_blur drm_input_details form-control no-state text-right <?php echo $class; ?>" data-title="Details des contrats" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>" tabindex="100000" />
                                        </div>
                                    <?php elseif ($key == "export"): ?>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a id="lien_sorties_export_details_<?php echo $detail->getHashForKey() ?>" data-toggle="modal" data-remote="false" data-target="#ajax-modal" href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" class="btn btn-default btn-xs btn_details" type="button"><span class="glyphicon glyphicon-list-alt"  tabindex="<?php echo $tabindex; ?>"></span></a>
                                            </span>
                                            <input type="text" id="input_sortie_export_<?php echo $detail->getHashForKey() ?>" data-hash="<?php echo $detail->getHash() ?>" data-pointer="#lien_sorties_export_details_<?php echo $detail->getHashForKey() ?>" class="pointer input-float somme_detail bold_on_blur drm_input_details form-control no-state text-right <?php echo $class; ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>" tabindex="100000" />
                                        </div>
                                    <?php else: ?>
                                        <?php
                                        $allAttributes = array('data-val-defaut' => $subform->getValue(), 'class' => $class);
                                        $isWritable = ($detail->getConfig()->get('sorties')->get($key)->writable && !$subform->getWidget()->getAttribute('readonly'));
                                        if ($isWritable) {
                                            $allAttributes = array_merge($allAttributes, array('tabindex' => $tabindex));
                                            $tabindex++;
                                        }
                                        echo $subform->render($allAttributes);
                                        ?>
                                    <?php
                                    endif;
                                    ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <li class="form-group form-group-xs groupe no_favoris" style="height: 21px;">
                            <a class="btn btn-default form-control raccourcis_ouvrir" style="border-color: #fff" tabindex="<?php echo $tabindex ?>" data-groupe-id="5" ></a>
                        </li>

                    </ul>

                    <div class="groupe p_gris" data-groupe-id="5">
                        <p class="form-group form-group-xs extendable"></p>
                        <ul class="list-unstyled">
                            <?php
                            $isfirst = true;
                            foreach ($form['sorties'] as $key => $subform):
                                ?>
                                <?php
                                if (!$favoris_sorties->exist($key)):
                                    ?>
                                    <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                                        <?php if ($key == "vrac"): ?>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a id="lien_sorties_vrac_details_<?php echo $detail->getHashForKey() ?>" data-toggle="modal" data-remote="false" data-target="#ajax-modal" href="<?php echo url_for("drm_vrac_details", $form->getObject()) ?>" class="btn btn-default btn-xs" type="button" tabindex="<?php echo $tabindex; ?>" ><span class="glyphicon glyphicon-list-alt"></span></a>
                                                </span>
                                                <input id="input_sortie_vrac_<?php echo $detail->getHashForKey() ?>" data-hash="<?php echo $detail->getHash() ?>" type="text" data-pointer="#lien_sorties_vrac_details_<?php echo $detail->getHashForKey() ?>" class="btn_detail pointer input-float somme_detail bold_on_blur drm_input_details form-control no-state text-right <?php echo $class; ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->vrac); ?>" />
                                            </div>
                                        <?php elseif ($key == "export"): ?>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a id="lien_sorties_export_details_<?php echo $detail->getHashForKey() ?>" data-toggle="modal" data-remote="false" data-target="#ajax-modal" href="<?php echo url_for("drm_export_details", $form->getObject()) ?>" class="btn btn-default btn-xs" type="button" tabindex="<?php echo $tabindex; ?>"  ><span class="glyphicon glyphicon-list-alt"></span></a>
                                                </span>
                                                <input type="text" id="input_sortie_export_<?php echo $detail->getHashForKey() ?>" data-hash="<?php echo $detail->getHash() ?>" data-pointer="#lien_sorties_export_details_<?php echo $detail->getHashForKey() ?>" class="pointer input-float somme_detail bold_on_blur drm_input_details form-control no-state text-right <?php echo $class; ?>" readonly="readonly" value="<?php echoFloat($detail->sorties->export); ?>"/>
                                            </div>
                                        <?php
                                        else:
                                            $class = $subform->getWidget()->getAttribute('class') . ' somme_detail bold_on_blur ';
                                            $class.= ($detail->getConfig()->get('sorties')->get($key)->revendique) ? " revendique_sortie " : "";
                                            $class.= ($detail->getConfig()->get('sorties')->get($key)->recolte) ? " recolte_sortie " : "";
                                            $isWritable = ($detail->getConfig()->get('sorties')->get($key)->writable && !$subform->getWidget()->getAttribute('readonly'));
                                            $allAttributes = array('data-val-defaut' => $subform->getValue(), 'data-previousfocus' => $tabindex);
                                            if ($isWritable) {
                                                if ($isfirst) {
                                                    $class.= ' tabIndexOnPrevious ';
                                                    $isfirst = false;
                                                }
                                                $allAttributes = array_merge($allAttributes, array('tabindex' => $tabindex));
                                                $tabindex++;
                                            }
                                            $allAttributes = array_merge($allAttributes, array('class' => $class));
                                            echo $subform->render($allAttributes);
                                            ?>
                                    <?php endif; ?>
                                    </li>
                                    <?php
                                endif;
                                ?>
<?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="list-group-item list-group-item-xs groupe p_gris" data-groupe-id="6">
                    <h4 class="form-group form-group-xs " style="height: 17px;">
                        <span>&nbsp;</span>
                    </h4>
                    <ul class="list-unstyled">
                        <?php foreach ($form['stocks_fin'] as $key => $subform): ?>
                            <?php
                            if ($key == 'instance'):
                                continue;
                            endif;
                            ?>
                            <li class="form-group form-group-xs <?php echo isVersionnerCssClass($form->getObject()->stocks_fin, $key); ?>">

                                <?php
                                $class = " somme_detail ";
                                $class.= ($key == 'dont_revendique') ? " somme_stock_fin_dont_revendique " : " somme_stock_fin ";
                                echo $subform->render(array('data-val-defaut' => $subform->getValue(), 'class' => $subform->getWidget()->getAttribute('class') . $class . '  bold_on_blur'));
                                ?>
                            </li>
<?php endforeach; ?>
                    </ul>
                </div>

                <div class="col_btn list-group-item list-group-item-xs invisible">
                    <button id="valide_<?php echo $detail->getHash() ?>" class="btn_valider btn_majeur btn_colonne_validation btn btn-xs btn-block btn-success" type="submit" tabindex="<?php echo $tabindex++; ?>">Valider</button>
                    <button class="btn_reinitialiser btn_annuler btn-block btn_majeur btn btn-xs btn-danger"  type="submit">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
