<?php use_helper('Float'); ?>
<?php use_helper('Vrac'); ?>
<?php use_helper('Date'); ?>

<?php if(count($vracs->rows) > 0): ?>
<?php if(isset($hamza_style) && $hamza_style) : ?>
    <?php include_partial('global/hamzaStyle', array('table_selector' => '#table_contrats', 
                                                     'mots' => vrac_get_words($vracs->rows),
                                                     'consigne' => "Saisissez un numéro de contrat, un soussigné ou un produit :")) ?>
<?php endif; ?>

<table class="table">    
    <thead>
        <tr>
            <th style="width: 0;">Type</th>
            <th style="width: 90px;">N°&nbsp;Contrat</th>
            <th style="width: 150px;">Date</th>
            <th>Soussignés</th>   
            <th>Produit</th>
            <th style="width: 90px;">Millésime</th>
            <th style="width: 90px;">Vol. (hl)</th>
            <th style="width: 90px;">Prix (€/hl)</th>
            <th style="width: 140px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($vracs->rows as $value) {
            $elt = $value->getRawValue()->value;
                $vracid = preg_replace('/VRAC-/', '', $elt[VracHistoryView::VALUE_NUMERO]);
                $v = VracClient::getInstance()->findByNumContrat($vracid);
                ?>
                <tr id="<?php echo vrac_get_id($value) ?>" class="<?php echo statusCssClass($elt[VracHistoryView::VALUE_STATUT]) ?>" >
                    <td style="vertical-align: middle;" class="text-center">
                    <span class="<?php echo typeToPictoCssClass($elt[VracHistoryView::VALUE_TYPE]) ?>" style="font-size: 24px;"></span>
                    </td>
                    <td style="vertical-align: middle;">
                    <a href="<?php echo url_for('@vrac_visualisation?numero_contrat='.$vracid) ?>">
                    <?php if($v->numero_archive): ?>
                    <?php echo $v->numero_archive ?>
                    <?php elseif(!$elt[VracHistoryView::VALUE_STATUT]): ?>
                        Brouillon
                    <?php else: ?>
                        Non visé
                    <?php endif; ?>
                    </a>
                    <br />
                    <?php if($v->isTeledeclare()): ?>
                    Télédeclaré
                    <?php endif; ?>
                    <?php echo $v->getTeledeclarationStatutLabel() ?>
                    </td>
                    <td style="vertical-align: middle;">
                    <?php if($elt[VracHistoryView::VALUE_STATUT] && $elt[VracHistoryView::VALUE_DATE_SIGNATURE]): ?>
                        Signé le <?php echo format_date($elt[VracHistoryView::VALUE_DATE_SIGNATURE], "dd/MM/yyyy", "fr_FR"); ?>
                    <?php else: ?>
                    <?php endif; ?>
                    <?php if($elt[VracHistoryView::VALUE_DATE_SAISIE]): ?>
                        Saisie le <?php echo format_date($elt[VracHistoryView::VALUE_DATE_SAISIE], "dd/MM/yyyy", "fr_FR"); ?>
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
                    <td style="vertical-align: middle;"><?php echo ucfirst(showType($v)) ?> : <?php echo ($elt[VracHistoryView::VALUE_TYPE] == VracClient::TYPE_TRANSACTION_VIN_VRAC || $elt[VracHistoryView::VALUE_TYPE] == VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE)? $elt[VracHistoryView::VALUE_PRODUITLIBELLE] : $elt[VracHistoryView::VALUE_CEPAGELIBELLE]; ?></td>
                    <td style="vertical-align: middle;"><?php echo $elt[VracHistoryView::VALUE_MILLESIME]; ?></td>
                    <td style="vertical-align: middle;" class="text-right">           
        <?php
        if (isset($elt[VracHistoryView::VALUE_VOLUME_PROPOSE]))
            echoFloat($elt[VracHistoryView::VALUE_VOLUME_PROPOSE]);
        else
            echo '0.00';
        ?>
                    </td>
                    <td style="vertical-align: middle;" class="text-right">
                         
        <?php
        if (isset($elt[VracHistoryView::VALUE_PRIX_UNITAIRE]))
            echoFloat($elt[VracHistoryView::VALUE_PRIX_UNITAIRE]);
        else
            echo '0.00';
        ?>
                    </td>
                    <td style="vertical-align: middle;" class="text-center">
                    <?php if($elt[VracHistoryView::VALUE_STATUT]): ?>
                    <a class="btn btn-sm btn-default" href="<?php echo url_for('@vrac_visualisation?numero_contrat='.$vracid) ?>">Visualiser</a>
                    <?php else: ?>
                    <a class="btn btn-sm btn-default" href="<?php echo url_for('@vrac_soussigne?numero_contrat='.$vracid) ?>">Continuer</a>
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