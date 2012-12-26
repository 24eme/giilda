<?php use_helper('Float'); ?>
<?php use_helper('Date');
?>        
<h2>Volumes revendiqués</h2>
<fieldset id="revendication_volume_revendiques_edition">
    
        <a class="btn_majeur btn_modifier" href="<?php echo url_for('revendication_add_row', array('odg'=> $odg, 'campagne' => $campagne)); ?>"><span>Ajouter lignes</span></a>
    <table class="table_recap">
        <thead>
            <tr>
                <th>ODG</th>
                <th>Date</th>
                <th>CVI</th>
                <th>Nom</th>
                <th>Produit</th>
                <th style="width: 100px;">Volume (en hl)</th>
                <th>Editer</th>
            </tr>
        </thead>
        <tbody>
        <?php $lien = str_replace("%25s", "%s", url_for('revendication_edition_row', array('odg' => "%s",
                'campagne' => "%s",
                'identifiant' => "%s",
                'row' => "%s",
                'retour' => $retour))); ?>
        <?php foreach ($revendications as $rev) : ?>
            <?php if ($rev->statut != RevendicationProduits::STATUT_SUPPRIME): ?>
            <tr>
                <td><?php echo $rev->odg ?></td>
                <td><?php echo format_date($rev->date_insertion, 'dd/MM/yyyy'); ?></td>
                <td><?php echo $rev->declarant_cvi; ?></td>
                <td>
                <?php echo $rev->declarant_nom ?>
                <?php
                /*if ($volume->bailleur_nom)
                echo 'Bailleur : '.$volume->bailleur_nom.' (en metayage avec : ' . $etb->declarant_nom . ')';
                else
                echo $etb->declarant_nom;*/
                ?></td>
                <td><?php echo $rev->produit_libelle; ?></td>
                <td><?php echoFloat($rev->volume); ?></td>
                <td>
                <a class="btn_majeur btn_modifier" href="<?php echo sprintf($lien, $rev->odg, $rev->campagne, $rev->etablissement_identifiant, $rev->ligne_identifiant) ?>">éditer</a>
                </td>
            </tr>
            <?php endif;?>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>
