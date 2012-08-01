
<section id="contenu" style="background: #fff; padding: 0 10px;">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php /*include_partial('etapes', array('drm' => $drm, 
                                                   'etape' => 'mouvements', 
                                                   'pourcentage' => '10'));*/ ?>
    <?php include_partial('drm/controlMessage'); ?>

    
    <!-- #principal -->
    <section id="principal" style="width: auto;">
    <?php use_helper('Rectificative'); ?>
    <?php use_helper('Float'); ?>
    <?php foreach($drm->declaration->certifications as $certification): ?>
            <div class="tableau_ajouts_liquidations">
                    <h2><?php echo $certification->getConfig()->libelle ?></h2>
                    <table class="tableau_recap">
                            <thead>
                                    <tr>
                                            <td style="border: none;">&nbsp;</td>
                                            <th style="font-weight: bold; border: none;">Stock début de mois</th>
                                            <th style="font-weight: bold; border: none;">Entrées</th>
                                            <th style="font-weight: bold; border: none;">Sorties</th>
                                            <th style="font-weight: bold; border: none;"><strong>Stock fin de mois</strong></th>
                                    </tr>
                            </thead>
                            <tbody>
                                    <?php $details = $certification->getProduits(); 
                                            $i = 1;?>

                                    <?php foreach($details as $detail): 
                            $i++; ?>
                                                    <tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
                                                            <td><?php echo $detail->getLibelle(ESC_RAW) ?></td>
                                <td class="<?php echo isRectifierCssClass($detail, 'total_debut_mois') ?>"><strong><?php echoFloat($detail->total_debut_mois) ?></strong>&nbsp;<span class="unite">hl</span></td>
                                                            <td class="<?php echo isRectifierCssClass($detail, 'total_entrees') ?>"><?php echoFloat($detail->total_entrees) ?>&nbsp;<span class="unite">hl</span></td>
                                                            <td class="<?php echo isRectifierCssClass($detail, 'total_sorties') ?>"><?php echoFloat($detail->total_sorties) ?>&nbsp;<span class="unite">hl</span></td>
                                                            <td class="<?php echo isRectifierCssClass($detail, 'total') ?>"><strong><?php echoFloat($detail->total) ?></strong>&nbsp;<span class="unite">hl</span></td>
                                                    </tr>
                            <?php endforeach; ?>
                    </tbody>
            </table>
    </div>
    <?php endforeach; ?>
    </section>
</section>