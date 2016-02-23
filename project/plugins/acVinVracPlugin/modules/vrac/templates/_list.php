<?php use_helper('Float'); ?>
<?php use_helper('Vrac'); ?>
<?php use_helper('Date'); ?>

<?php if(count($vracs->rows) > 0): ?>
<?php if(isset($hamza_style) && $hamza_style) : ?>
    <h3>Filtrer</h3>
    <div class="form-group">
        <input type="hidden" data-placeholder="Saisissez un numéro de contrat, un soussigné ou un produit" data-hamzastyle-container="#table_contrats" class="hamzastyle form-control" />
    </div>
<?php endif; ?>


<table id="table_contrats" class="table">
    <thead>
        <tr>
        <th style="width: 0;">&nbsp;</th>
            <th style="width: 70px;">&nbsp;</th>
            <th style="width: 110px;">Date</th>
            <th>Soussignés</th>   
            <th>Produit (Millésime)</th>
            <th style="width: 50px;">Vol.&nbsp;prop. (Vol.&nbsp;enl.)</th>
            <th style="width: 50px;">Prix</th>
            <th style="width: 90px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($vracs->rows as $value) {
            $elt = $value->getRawValue()->value;
                $vracid = preg_replace('/VRAC-/', '', $elt[VracHistoryView::VALUE_NUMERO]);
                $v = VracClient::getInstance()->findByNumContrat($vracid);
                ?>
                <tr data-words='<?php echo json_encode(array_merge(array(strtolower($elt[VracHistoryView::VALUE_ACHETEUR_NOM]), 
                                                                         strtolower($elt[VracHistoryView::VALUE_VENDEUR_NOM]), 
                                                                         strtolower($elt[VracHistoryView::VALUE_MANDATAIRE_NOM]), 
                                                                         strtolower($elt[VracHistoryView::VALUE_PRODUITLIBELLE]), 
                                                                         strtolower($elt[VracClient::VRAC_VIEW_NUMARCHIVE]), 
                                                                         strtolower($elt[VracHistoryView::VALUE_MILLESIME]), 
                                                                         strtolower(VracClient::$types_transaction[$elt[VracHistoryView::VALUE_TYPE]]))
                                                       ), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>' id="<?php echo vrac_get_id($value) ?>" class="<?php echo statusCssClass($elt[VracHistoryView::VALUE_STATUT]) ?> hamzastyle-item" >
                    <td style="vertical-align: middle;" class="text-center">
                    <span class="<?php echo typeToPictoCssClass($elt[VracHistoryView::VALUE_TYPE]) ?>" style="font-size: 24px;"></span>
                    </td>
                    <td style="vertical-align: middle;">
                    <?php if($elt[VracHistoryView::VALUE_STATUT]): ?>
                    <a href="<?php echo url_for('@vrac_visualisation?numero_contrat='.$vracid) ?>">
                    <?php else: ?>
                    <a href="<?php echo url_for('@vrac_redirect_saisie?numero_contrat='.$vracid) ?>">
                    <?php endif; ?>
                    <?php if($v->numero_archive): ?>
                    <?php echo $v->numero_archive ?>
                    <?php elseif(!$elt[VracHistoryView::VALUE_STATUT]): ?>
                        Brouillon
                    <?php else: ?>
                        Non visé
                    <?php endif; ?>
                    </a>
                    <br />
                    <?php if($v && $v->isTeledeclare()): ?>
                    Télédeclaré
                    <?php endif; ?>
                    <?php echo $v->getTeledeclarationStatutLabel() ?>
                    </td>
                    <td style="vertical-align: middle;">
                    <?php if($elt[VracHistoryView::VALUE_STATUT] && $elt[VracHistoryView::VALUE_DATE_SIGNATURE]): ?>
            <span class="glyphicon glyphicon-pencil" aria-hidden="true" title="Date de signature"></span> <?php echo format_date($elt[VracHistoryView::VALUE_DATE_SIGNATURE], "dd/MM/yyyy", "fr_FR"); ?><br/>
                    <?php else: ?>
                    <?php endif; ?>
                    <?php if($elt[VracHistoryView::VALUE_DATE_SAISIE]): ?>
                           <span class="text-muted"><span class="glyphicon glyphicon-check" aria-hidden="true" title="Date de saisie (validation interpro)"></span> <?php echo format_date($elt[VracHistoryView::VALUE_DATE_SAISIE], "dd/MM/yyyy", "fr_FR"); ?></span>
                    <?php else: ?>
                    <?php endif; ?>
                    </td>
                    <td style="vertical-align: middle;">
        <?php
        echo ($elt[VracHistoryView::VALUE_VENDEUR_ID]) ?
                'Vendeur : ' . link_to($elt[VracHistoryView::VALUE_VENDEUR_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracHistoryView::VALUE_VENDEUR_ID])) : '';
        ?>
        <br />
        <?php
        echo ($elt[VracHistoryView::VALUE_ACHETEUR_ID]) ?
                'Acheteur : ' . link_to($elt[VracHistoryView::VALUE_ACHETEUR_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracHistoryView::VALUE_ACHETEUR_ID])) : '';
            ?>
        <?php
            $has_representant = ($elt[VracHistoryView::VALUE_REPRESENTANT_ID] != $elt[VracHistoryView::VALUE_VENDEUR_ID]) ? $elt[VracHistoryView::VALUE_REPRESENTANT_ID] : 0;
            if ($has_representant) echo '<br/>';
            echo ($has_representant) ?
                'Representant : ' . link_to($elt[VracHistoryView::VALUE_REPRESENTANT_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracHistoryView::VALUE_REPRESENTANT_ID])) : '';
            ?>
        <?php if($elt[VracHistoryView::VALUE_MANDATAIRE_ID]): ?>
            <br />
        <?php
        echo ($elt[VracHistoryView::VALUE_MANDATAIRE_ID]) ?
                'Courtier : ' . link_to($elt[VracHistoryView::VALUE_MANDATAIRE_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracHistoryView::VALUE_MANDATAIRE_ID])) : '';
        ?>
                            </li>
        <?php endif; ?>
                        </ul>
                    </td>              
                    <td style="vertical-align: middle;"><?php

            $produit = ($elt[VracHistoryView::VALUE_TYPE] == VracClient::TYPE_TRANSACTION_VIN_VRAC || $elt[VracHistoryView::VALUE_TYPE] == VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE)? $elt[VracHistoryView::VALUE_PRODUITLIBELLE] : $elt[VracHistoryView::VALUE_CEPAGELIBELLE];
            $millesime = $elt[VracHistoryView::VALUE_MILLESIME] ? $elt[VracHistoryView::VALUE_MILLESIME] : 'nm';
            if ($produit)
                echo "<b>$produit</b> ($millesime)";?></td>
                     <td style="vertical-align: middle;" class="text-right">           
        <?php
        if (isset($elt[VracHistoryView::VALUE_VOLUME_PROPOSE])) {
            echoFloat($elt[VracHistoryView::VALUE_VOLUME_PROPOSE]);
            echo '&nbsp;'.VracConfiguration::getInstance()->getUnites()[$elt[VracHistoryView::VALUE_TYPE]]['volume_initial']['libelle'].'<br/>';
            echo '<span class="text-muted">';
            if ($elt[VracHistoryView::VALUE_VOLUME_ENLEVE]) {
                echoFloat($elt[VracHistoryView::VALUE_VOLUME_ENLEVE]);
                echo '&nbsp;'.VracConfiguration::getInstance()->getUnites()[$elt[VracHistoryView::VALUE_TYPE]]['volume_vigueur']['libelle'];
            }else{
                echo '0.00&nbsp;'.VracConfiguration::getInstance()->getUnites()[$elt[VracHistoryView::VALUE_TYPE]]['volume_vigueur']['libelle'];
            }
            echo '</span>';
        }
        ?>
                    </td>
                    <td style="vertical-align: middle;" class="text-right">
                         
        <?php
            if (isset($elt[VracHistoryView::VALUE_PRIX_UNITAIRE_INITIAL])) {
                echoFloat($elt[VracHistoryView::VALUE_PRIX_UNITAIRE_INITIAL]);
                echo "&nbsp;".VracConfiguration::getInstance()->getUnites()[$elt[VracHistoryView::VALUE_TYPE]]['prix_initial_unitaire']['libelle'] ;
            }
        ?>
                    </td>
                    <td style="vertical-align: middle;" class="text-center">
                    <?php if($elt[VracHistoryView::VALUE_STATUT]): ?>
                    <a class="btn btn-sm btn-default" href="<?php echo url_for('@vrac_visualisation?numero_contrat='.$vracid) ?>">Visualiser</a>
                    <?php else: ?>
                    <a class="btn btn-sm btn-default" href="<?php echo url_for('@vrac_redirect_saisie?numero_contrat='.$vracid) ?>">Continuer</a>
                    <?php endif; ?>
                    </td>
                </tr>
                <?php
            }
        ?>
    </tbody>
</table>

<?php else: ?>
<p> Pas de contrats </p>
<?php endif; ?>
