<?php use_helper('Float'); ?>
<?php use_helper('Vrac'); ?>

<?php if(count($vracs->rows) > 0): ?>
<?php if(isset($hamza_style) && $hamza_style) : ?>
    <?php include_partial('global/hamzaStyle', array('table_selector' => '#table_contrats', 
                                                     'mots' => vrac_get_words($vracs->rows),
                                                     'consigne' => "Saisissez un numéro de contrat, un soussigné ou un produit :")) ?>
<?php endif; ?>

<table class="table table-striped table-condensed">    
    <thead>
        <tr>
            <th style="width: 0;">Type</th>
            <th style="width: 160px;">N° Contrat</th>
            <th>Soussignés</th>   
            <th style="width: 350px;">Produit</th>
            <th style="width: 100px;">Vol.&nbsp;Enlevé&nbsp;/&nbsp;Prop.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($vracs->rows as $value) {
            $elt = $value->getRawValue()->value;
            if (!is_null($elt[VracClient::VRAC_VIEW_STATUT])) {
                $vracid = preg_replace('/VRAC-/', '', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]);
                $v = VracClient::getInstance()->findByNumContrat($vracid);
                ?>
                <tr id="<?php echo vrac_get_id($value) ?>" class="<?php echo statusCssClass($elt[VracClient::VRAC_VIEW_STATUT]) ?>" >
                    <td class="text-center">
                    <span class="<?php echo typeToPictoCssClass($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]) ?>" style="font-size: 24px;"></span>
                    </td>
                    <td>
                    <a href="<?php echo url_for('@vrac_visualisation?numero_contrat='.$vracid) ?>">
                    <?php if($v->numero_archive): ?>
                    <?php echo $v->numero_archive ?>
                    <?php else: ?>
                        Non visé
                    <?php endif; ?>
                    </a>
                    (<?php echo preg_replace('/(\d{4})(\d{2})(\d{2}).*/', '$3/$2/$1', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]) ?>)
                    <?php if($v->isTeledeclare()): ?>
                    Télédeclaré
                    <?php endif; ?>
                    </td>
                    <td>
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
                    <td class="produit"><?php echo $elt[VracClient::VRAC_VIEW_PRODUIT_LIBELLE]; ?></td>
                    <td class="text-right">           
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
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<?php else: ?>
<p> Pas de contrats </p>
<?php endif; ?>