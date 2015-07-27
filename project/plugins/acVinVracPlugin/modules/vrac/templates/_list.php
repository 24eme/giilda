<?php use_helper('Float'); ?>
<?php use_helper('Vrac'); ?>
<?php use_helper('Date'); ?>

<?php if(count($vracs->rows) > 0): ?>
<?php if(isset($hamza_style) && $hamza_style) : ?>
    <?php include_partial('global/hamzaStyle', array('table_selector' => '#table_contrats', 
                                                     'mots' => vrac_get_words($vracs->rows),
                                                     'consigne' => "Saisissez un numéro de contrat, un soussigné ou un produit :")) ?>
<?php endif; ?>

<table class="table table-condensed">    
    <thead>
        <tr>
            <th style="width: 0;">Type</th>
            <th style="width: 90px;">N°&nbsp;Contrat</th>
            <th style="width: 160px;">Date</th>
            <th>Soussignés</th>   
            <th style="width: 300px;">Produit</th>
            <th style="width: 100px;">Vol.&nbsp;Enlevé&nbsp;/&nbsp;Prop.</th>
            <th style="width: 140px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($vracs->rows as $value) {
            $elt = $value->getRawValue()->value;
                $vracid = preg_replace('/VRAC-/', '', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]);
                $v = VracClient::getInstance()->findByNumContrat($vracid);
                ?>
                <tr id="<?php echo vrac_get_id($value) ?>" class="<?php echo statusCssClass($elt[VracClient::VRAC_VIEW_STATUT]) ?>" >
                    <td style="vertical-align: middle;" class="text-center">
                    <span class="<?php echo typeToPictoCssClass($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]) ?>" style="font-size: 24px;"></span>
                    </td>
                    <td style="vertical-align: middle;">
                    <a href="<?php echo url_for('@vrac_visualisation?numero_contrat='.$vracid) ?>">
                    <?php if($v->numero_archive): ?>
                    <?php echo $v->numero_archive ?>
                    <?php elseif(!$elt[VracClient::VRAC_VIEW_STATUT]): ?>
                        Brouillon
                    <?php else: ?>
                        Non visé
                    <?php endif; ?>
                    </a>
                    <br />
                    <?php if($v->isTeledeclare()): ?>
                    Télédeclaré
                    <?php endif; ?>
                    </td>
                    <td style="vertical-align: middle;">
                    <?php if($elt[VracClient::VRAC_VIEW_STATUT] && $elt[VracClient::VRAC_VIEW_DATE_SIGNATURE]): ?>
                        Signé le <?php echo format_date($elt[VracClient::VRAC_VIEW_DATE_SIGNATURE], "dd/MM/yyyy", "fr_FR"); ?>
                    <?php else: ?>
                    <?php endif; ?>
                    </td>
                    <td style="vertical-align: middle;">
        <?php
        echo ($elt[VracClient::VRAC_VIEW_VENDEUR_ID]) ?
                'Vendeur : ' . link_to($elt[VracClient::VRAC_VIEW_VENDEUR_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_VENDEUR_ID])) : '';
        ?>
        <br />
        <?php
        echo ($elt[VracClient::VRAC_VIEW_ACHETEUR_ID]) ?
                'Acheteur : ' . link_to($elt[VracClient::VRAC_VIEW_ACHETEUR_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_ACHETEUR_ID])) : '';
        ?>
        <?php if($elt[VracClient::VRAC_VIEW_MANDATAIRE_ID]): ?>
            <br />
        <?php
        echo ($elt[VracClient::VRAC_VIEW_MANDATAIRE_ID]) ?
                'Courtier : ' . link_to($elt[VracClient::VRAC_VIEW_MANDATAIRE_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_MANDATAIRE_ID])) : '';
        ?>
                            </li>
        <?php endif; ?>
                        </ul>
                    </td>              
                    <td style="vertical-align: middle;"><?php echo $elt[VracClient::VRAC_VIEW_PRODUIT_LIBELLE]; ?></td>
                    <td style="vertical-align: middle;" class="text-right">           
        <?php
        if (isset($elt[VracClient::VRAC_VIEW_VOLENLEVE]))
            echoFloat($elt[VracClient::VRAC_VIEW_VOLENLEVE]);
        else
            echo '0.00';
        echo '&nbsp;/&nbsp;';
        if (isset($elt[VracClient::VRAC_VIEW_VOLPROP]))
            echoFloat($elt[VracClient::VRAC_VIEW_VOLPROP]);
        else
            echo '0.00';
        ?>
                    </td>
                    <td style="vertical-align: middle;" class="text-center">
                    <?php if($elt[VracClient::VRAC_VIEW_STATUT]): ?>
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