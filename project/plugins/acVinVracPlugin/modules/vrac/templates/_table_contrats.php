<?php use_helper('Float'); ?>
<?php use_helper('Vrac'); ?>

<?php if(count($vracs->rows) > 0): ?>
<?php if(isset($hamza_style)) : ?>
    <?php include_partial('global/hamzaStyle', array('table_selector' => '#table_contrats', 
                                                     'mots' => vrac_get_words($vracs->rows),
                                                     'consigne' => "Saisissez un numéro de contrat, un soussigné ou un produit :")) ?>
<?php endif; ?>

<table id="table_contrats" class="table_recap">    
    <thead>
        <tr>
            <th class="type">Type</th>
            <th style="width: 100px;">N° Contrat</th>
            <th>Soussignés</th>   
            <th style="width: 120px;">Produit</th>
            <th style="width: 0;">Vol. enlevé. / Vol. prop.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($vracs->rows as $value) {
            $elt = $value->getRawValue()->value;
            if (!is_null($elt[VracClient::VRAC_VIEW_STATUT])) {
                $statusColor = statusColor($elt[VracClient::VRAC_VIEW_STATUT]);
                $vracid = preg_replace('/VRAC-/', '', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]);
                $v = VracClient::getInstance()->findByNumContrat($vracid);
                ?>
                <tr style="<?php if (!$v->numero_archive): ?>opacity: 0.7;<?php endif; ?>" id="<?php echo vrac_get_id($value) ?>" class="<?php echo $statusColor; ?>" >
                    <td class="type" ><span class="type_<?php echo strtolower($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]); ?>"><?php echo ($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]) ? typeProduit($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]) : ''; ?></span></td>
                    <td class="num_contrat">
                    <a href="<?php echo url_for('@vrac_visualisation?numero_contrat='.$vracid) ?>">
                    <?php if($v->numero_archive): ?>
                    <?php echo $v->numero_archive ?>
                    <?php else: ?>
                        Non visé
                    <?php endif; ?>
                    </a><br />
                    (<?php echo preg_replace('/(\d{4})(\d{2})(\d{2}).*/', '$3/$2/$1', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]) ?>)
                    <?php if($v->isTeledeclare()): ?>
                    <br />Télédeclaré
                    <?php endif; ?>
                    </td>
                    <td class="soussigne">
                        <ul>  
                            <li>
        <?php
        echo ($elt[VracClient::VRAC_VIEW_VENDEUR_ID]) ?
                'Vendeur : ' . link_to($elt[VracClient::VRAC_VIEW_VENDEUR_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_VENDEUR_ID])) : '';
        ?>
                            </li>
                            <li>
        <?php
        echo ($elt[VracClient::VRAC_VIEW_ACHETEUR_ID]) ?
                'Acheteur : ' . link_to($elt[VracClient::VRAC_VIEW_ACHETEUR_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_ACHETEUR_ID])) : '';
        ?>
                            </li>
                            <li>
        <?php
        echo ($elt[VracClient::VRAC_VIEW_MANDATAIRE_ID]) ?
                'Courtier : ' . link_to($elt[VracClient::VRAC_VIEW_MANDATAIRE_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_MANDATAIRE_ID])) : '';
        ?>
                            </li>
                        </ul>
                    </td>              
                    <td class="produit"><?php echo $elt[VracClient::VRAC_VIEW_PRODUIT_LIBELLE]; ?></td>
                    <td class="volume">           
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