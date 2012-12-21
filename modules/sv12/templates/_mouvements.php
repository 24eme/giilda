<?php use_helper('Float'); ?> 
<?php use_helper('Date'); ?>
<?php use_helper('Mouvement') ?>

<?php if(count($mouvements) > 0): ?>

<?php if(isset($hamza_style)) : ?>
<?php include_partial('global/hamzaStyle', array('table_selector' => '#table_mouvements', 
                                                 'mots' => mouvement_get_words($mouvements))) ?>
<?php endif; ?>
<fieldset id="table_mouvements">
        <table class="table_recap">
        <thead>
        <tr>
            <th>Date de modification</th>
            <th>Contrat</th>
            <th>Produit</th>
            <th>Volume</th>

        </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($mouvements as $mouvement) :
            ?>   

            <tr id="<?php echo mouvement_get_id($mouvement) ?>" class="<?php if($i%2!=0) echo 'alt'; if($mouvement->facturable) echo " facturable"; ?>">
                <td><?php echo sprintf("%s - %s", ($mouvement->version) ? $mouvement->version : 'M00', format_date($mouvement->date_version));?></td>
                <td>
 <?php if ($mouvement->vrac_numero) { ?>
                    <a href="<?php echo url_for(array('sf_route' => 'vrac_visualisation', 'numero_contrat' => $mouvement->vrac_numero)) ?>"><?php echo VracClient::getInstance()->getLibelleFromId($mouvement->vrac_numero, '&nbsp;') ?></a> <?php echo sprintf("(%s, %s)", $mouvement->type_libelle, $mouvement->vrac_destinataire); ?>
     <?php }else if ($mouvement->vrac_destinataire) {
   echo "SANS CONTRAT ".sprintf("(%s, %s)", $mouvement->type_libelle, $mouvement->vrac_destinataire);
} else {  echo '-'; } ?>
                </td>
                <td>
                    <?php echo $mouvement->produit_libelle; ?>
                </td>
                <td <?php echo ($mouvement->volume > 0)? ' class="positif"' : 'class="negatif"';?> >
                    <?php  echoSignedFloat($mouvement->volume); ?>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
        </table> 
</fieldset>
<?php else: ?>
<p>Pas de mouvements</p>
<?php endif; ?>