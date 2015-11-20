<?php use_helper('Float'); ?>
<?php use_javascript('facture.js'); ?>


<h2><?php if ($facture->numero_ava): ?>Édition de <?php if ($facture->isAvoir()): ?>l'<?php else: ?>la <?php endif; ?><?php else: ?>Création <?php if ($facture->isAvoir()): ?>d'un<?php else: ?>d'une<?php endif; ?> <?php endif; ?><?php if ($facture->isAvoir()): ?>Avoir <?php else: ?>Facture<?php endif; ?> <?php if ($facture->numero_ava): ?>n°<?php echo $facture->numero_ava; ?><?php endif; ?> <small>(Daté du <?php
        $date = new DateTime($facture->date_facturation);
        echo $date->format('d/m/Y');
        ?>)</small>
    <br />
    <small><?php echo $facture->declarant->raison_sociale ?>
        (<?php echo $facture->declarant->adresse ?> <?php echo $facture->declarant->code_postal ?> <?php echo $facture->declarant->commune ?>)</small>
</h2>

<form id="form_edition_facture" action="" method="post" class="form-horizontal">

    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>

    <?php if ($form->hasErrors()): ?>
        <div class="alert alert-danger" role="alert">
            Veuuillez compléter ou corriger les erreurs
        </div>
    <?php endif; ?>

    <div class="row row-margin">
        <div class="col-xs-12" style="border-bottom: 1px dotted #d2d2d2;">
            <?php if ($sans_categorie): ?>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-6 text-center lead text-muted">Libellé</div>
                        <div class="col-xs-3 text-center lead text-muted">Code comptable</div>
                        <div class="col-xs-3 text-center lead text-muted">Quantité</div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="row">
                        <div class="col-xs-5 text-center lead text-muted">Prix&nbsp;U. (Taux)</div>
                        <div class="col-xs-7 text-center lead text-muted">Total</div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-xs-7">
                    <div class="row">
                        <div class="col-xs-3 text-center lead text-muted">Quantité</div>
                        <div class="col-xs-6 text-center lead text-muted">Libellé / Code comptable</div>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="row">
                        <div class="col-xs-5 text-center lead text-muted">Prix&nbsp;U.</div>
                        <div class="col-xs-7 text-center lead text-muted">Total</div>
                    </div>
                </div>
                <div class="col-xs-2 text-center lead text-muted">Taux&nbsp;TVA</div>

            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <?php foreach ($form['lignes'] as $key_ligne => $f_ligne): ?>
            <div id="<?php echo $f_ligne->renderId() ?>" class="col-xs-12">
                <?php if (!$sans_categorie): ?>
                    <div class="form-group line <?php if (!$f_ligne['libelle']->getValue() && !$f_ligne->hasError()): ?>empty<?php endif; ?>" style="<?php echo (!$f_ligne['libelle']->getValue() && !$f_ligne->hasError()) ? "opacity: 0.5" : null ?>">
                        <div class="col-xs-7">
                            <div class="row">
                                <div class="col-xs-3">

                                </div>
                                <div class="col-xs-6 <?php echo (($f_ligne['libelle']->hasError()) ? 'has-error' : null) ?>">
                                    <?php echo $f_ligne['libelle']->render(array('class' => 'form-control input-lg', 'placeholder' => 'Libellé')); ?>
                                </div>
                                <div class="col-xs-3 <?php echo (($f_ligne['produit_identifiant_analytique']->hasError()) ? 'has-error' : null) ?>">
                                    <?php echo $f_ligne['produit_identifiant_analytique']->render(array('class' => 'form-control input-lg bg-info', 'placeholder' => 'Compta')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3 text-right">
                            <div class="row">
                                <div class="col-xs-7 col-xs-offset-5 <?php echo (($f_ligne['montant_ht']->hasError()) ? 'has-error' : null) ?> <?php echo (($f_ligne['montant_tva']->hasError()) ? 'has-error' : null) ?>">
                                    <?php $ids_montant_ht = array(); ?>
                                    <?php
                                    foreach ($f_ligne['details'] as $f_detail): $ids_montant_ht[] = "#" . $f_detail['montant_ht']->renderId();
                                    endforeach;
                                    ?>
                                    <?php echo $f_ligne['montant_ht']->render(array('class' => 'form-control input-lg text-right data-sum-element', 'data-sum' => implode(" + ", $ids_montant_ht), "readonly" => "readonly", 'data-sum-element' => "#total_ht")); ?>
                                    <?php $ids_montant_tva = array(); ?>
                                    <?php
                                    foreach ($f_ligne['details'] as $f_detail): $ids_montant_tva[] = "#" . $f_detail['montant_tva']->renderId();
                                    endforeach;
                                    ?>
                                    <?php echo $f_ligne['montant_tva']->render(array('class' => 'form-control input-lg text-right data-sum-element', 'data-sum' => implode(" + ", $ids_montant_tva), "readonly" => "readonly", 'data-sum-element' => "#total_tva", 'readonly' => 'readonly', 'type' => 'hidden')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <!--<button type="button" class="btn btn-danger btn-lg hidden"><span class="glyphicon glyphicon-trash"></span></button>-->
                        </div>
                    </div>
                <?php else: ?>
                    <div class="form-group line <?php if (!$f_ligne['libelle']->getValue() && !$f_ligne->hasError()): ?>empty<?php endif; ?>" style="padding-top:  15px;">
                        <div class="col-xs-7">
                            <div class="row">
                                <div class="col-xs-6 <?php echo (($f_ligne['libelle']->hasError()) ? 'has-error' : null) ?>">
                                    <?php echo $f_ligne['libelle']->render(array('class' => 'form-control', 'placeholder' => 'Libellé Catégorie')); ?>
                                </div>
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3"></div>
                            </div>
                        </div>
                        <div class="col-xs-3 text-right">
                            <div class="row">
                                <div class="col-xs-7 col-xs-offset-5"></div>
                            </div>
                        </div>
                        <div class="col-xs-2"></div>
                    </div>
                <?php endif; ?>
                <div class="form-group" style="border-bottom: 1px dotted #d2d2d2; padding-top:  15px;">
                    <div class="col-xs-12">
                        <?php foreach ($f_ligne['details'] as $key_detail => $f_detail): ?>
                            <div data-line="#<?php echo $f_ligne->renderId() ?>" id="<?php echo $f_detail->renderId() ?>" class="form-group detail <?php if (!$f_detail['libelle']->getValue() && !$f_detail->hasError()): ?>empty<?php endif; ?>" >
                                <div class="col-xs-8">
                                    <div class="row">
                                        <div class="col-xs-6 <?php echo (($f_detail['libelle']->hasError()) ? 'has-error' : null) ?>">
                                            <?php echo $f_detail['libelle']->render(array('class' => 'form-control', 'data-detail' => "#" . $f_detail->renderId(), 'placeholder' => 'Libellé du detail')); ?>
                                        </div>  
                                        <div class="col-xs-3 <?php echo (($f_detail['identifiant_analytique']->hasError()) ? 'has-error' : null) ?>">
                                            <?php echo $f_detail['identifiant_analytique']->render(array('class' => 'form-control bg-info', 'placeholder' => 'Compta')); ?>
                                        </div>
                                        <div class="col-xs-3 <?php echo (($f_detail['quantite']->hasError()) ? 'has-error' : null) ?>">
                                            <?php echo $f_detail['quantite']->render(array('class' => 'form-control text-right data-sum-element', 'data-sum-element' => "#" . $f_detail['montant_ht']->renderId(), 'data-detail' => "#" . $f_detail->renderId(), 'placeholder' => 'Quantité')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="row">
                                        <div class="col-xs-5 <?php echo (($f_detail['prix_unitaire']->hasError()) ? 'has-error' : null) ?>">
                                            <?php echo $f_detail['prix_unitaire']->render(array('class' => 'form-control text-right data-sum-element', 'data-sum-element' => "#" . $f_detail['montant_ht']->renderId(), 'data-detail' => "#" . $f_detail->renderId(), 'placeholder' => 'Prix U.')); ?>
                                        </div>
                                        <div class="col-xs-7 <?php echo (($f_detail['montant_ht']->hasError()) ? 'has-error' : null) ?>">
                                            <?php
                                            echo $f_detail['montant_ht']->render(
                                                    array('class' => 'form-control text-right data-sum-element',
                                                        'data-sum' => sprintf("#%s * #%s", $f_detail['quantite']->renderId(), $f_detail['prix_unitaire']->renderId()),
                                                        'data-sum-element' => json_encode(array("#" . $f_detail['montant_tva']->renderId(), "#" . $f_ligne['montant_ht']->renderId())),
                                                        "readonly" => "readonly", 'data-detail' => "#" . $f_detail->renderId()));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <div class="row">
                                        <div class="col-xs-7 <?php echo (($f_detail['taux_tva']->hasError()) ? 'has-error' : null) ?> <?php echo (($f_detail['montant_tva']->hasError()) ? 'has-error' : null) ?>">
                                            <?php echo $f_detail['taux_tva']->render(array('class' => 'form-control text-right data-sum-element', 'data-sum-element' => "#" . $f_detail['montant_tva']->renderId(), 'data-detail' => "#" . $f_detail->renderId(), 'placeholder' => 'Tx TVA')); ?>
                                            <?php echo $f_detail['montant_tva']->render(array('class' => 'form-control text-right data-sum-element', 'data-sum' => sprintf("#%s * #%s", $f_detail['montant_ht']->renderId(), $f_detail['taux_tva']->renderId()), 'data-sum-element' => '#' . $f_ligne['montant_tva']->renderId(), 'readonly' => 'readonly', 'type' => 'hidden', 'data-detail' => "#" . $f_detail->renderId())); ?>
                                        </div>
                                        <div class="col-xs-5">
                                            <?php if ($key_detail == count($f_ligne['details']) - 1): ?>
                                                                    <!--<button type="button" class="btn btn-success data-add-line hidden" data-form="#form_edition_facture" data-form-action="<?php // echo isset($baseFacture) ? url_for("facture_avoir", array('sf_subject' => $baseFacture, 'not_redirect' => true)) : url_for("facture_edition", array('id' => $f->_id, 'not_redirect' => true))      ?>"><span class="glyphicon glyphicon-plus"></span></button>-->
                                            <?php else: ?>
                                                                    <!--<button data-detail="#<?php echo $f_detail->renderId() ?>" type="button" class="btn btn-danger data-clean-line hidden"><span class="glyphicon glyphicon-trash"></span></button>-->
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group form-group-lg">
                <div class="col-xs-8 text-center"></div>
                <div class="col-xs-4 text-center">
                    <div class="row">
                        <div class="col-xs-5">
                            <label class="control-label lead">Total HT</label>
                        </div>
                        <div class="col-xs-7">
                            <?php $ids_total_ht = array(); ?>
                            <?php
                            foreach ($form['lignes'] as $f_ligne): $ids_total_ht[] = "#" . $f_ligne['montant_ht']->renderId();
                            endforeach;
                            ?>
                            <strong>    <input id="total_ht" type="text" class="form-control input-lg text-right data-sum-element" data-sum="<?php echo implode(" + ", $ids_total_ht) ?>" data-sum-element="#total_ttc" readonly="readonly" value="<?php echo $facture->total_ht ?>" /></strong>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group form-group-lg">
                <div class="col-xs-8 text-center"></div>
                <div class="col-xs-4 text-center">
                    <div class="row">
                        <div class="col-xs-5">
                            <label class="control-label lead">Total TVA</label>

                        </div>
                        <div class="col-xs-7 text-center">
                            <?php $ids_total_tva = array(); ?>
                            <?php
                            foreach ($form['lignes'] as $f_ligne): $ids_total_tva[] = "#" . $f_ligne['montant_tva']->renderId();
                            endforeach;
                            ?>
                            <strong><input id="total_tva" type="text" class="form-control input-lg text-right data-sum-element"  data-sum="<?php echo implode(" + ", $ids_total_tva) ?>" data-sum-element="#total_ttc" readonly="readonly" value="<?php echo $facture->total_taxe ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group form-group-lg">
                <div class="col-xs-8 text-center"></div>
                <div class="col-xs-4 text-center">
                    <div class="row">
                        <div class="col-xs-5">
                            <label class="control-label lead">Total TTC</label>
                        </div> 
                        <div class="col-xs-7 text-center">
                            <input id="total_ttc" type="text" class="form-control input-lg text-right" data-sum="#total_ht + #total_tva" readonly="readonly" value="<?php echo $facture->total_ttc ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-margin">
        <div class="col-xs-6 text-left">
            <a class="btn btn-danger btn-lg btn-upper" href="<?php echo url_for('facture_societe', $facture->getSociete()) ?>">Annuler</a>
        </div>
        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-success btn-lg btn-upper">Valider</button>
        </div>
    </div>

</form>
