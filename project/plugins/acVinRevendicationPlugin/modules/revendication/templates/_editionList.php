<?php 
use_helper('Float');
use_helper('Date');
use_helper('Revendication');
$odg = '';
$campagne = '';
if (isset($revendication)) {
  $odg = $revendication->odg;
  $campagne = $revendication->campagne;
}
?>        
<h2>Volumes revendiqués</h2>
<fieldset id="revendication_volume_revendiques_edition">
<?php if (isset($revendications) && count($revendications)) : ?>
        <?php if (isset($revendication)) :  ?>
        <a class="btn_majeur btn_modifier" href="<?php echo url_for('revendication_add_row', array('odg'=> $odg, 'campagne' => $campagne)); ?>"><span>Ajouter lignes</span></a>
        <a class="btn_majeur btn_excel" href="<?php echo url_for('revendication_downloadCSV', $revendication); ?>">Télécharger le fichier originel</a>
        <?php endif;?>
        <?php 
        include_partial('global/hamzaStyle', array('table_selector' => '#table_revendications', 
                                                 'mots' => revendication_get_words($revendications),
                                                 'consigne' => "Saisissez un produit, un numéro de cvi, un numéro de certificat ou un volume :")) ?>
    <table id="table_revendications" class="table_recap">
        <thead>
            <tr>
                <th>ODG</th>
                <th>Date et N° certif.</th>
                <th>CVI</th>
                <th style="width: 150px;">Nom</th>
                <th>Produit</th>
                <th style="width: 100px;">Volume (en hl)</th>
                <th>Editer</th>
            </tr>
        </thead>
        <tbody>
        <?php $lien = str_replace("%25s", "%s", url_for('revendication_edition_row', array('odg' => "%s",
                'campagne' => "%s",
                'identifiant' => "%s",
                'produit' => "%s",
                'row' => "%s",
                'retour' => $retour))); ?>
        <?php foreach ($revendications as $rev) : ?>
            
            <?php if ($rev->statut != RevendicationProduits::STATUT_SUPPRIME): ?>
            <tr id="<?php echo $rev->ligne_identifiant; ?>">
                <td><?php echo $rev->odg ?></td>
                <td><?php echo isset($rev->date_traitement)? format_date($rev->date_traitement, 'dd/MM/yyyy') : 'N/A';
                          echo ' ('.$rev->num_certif.')';  ?></td>
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
                    <a class="btn_majeur btn_modifier" href="<?php echo sprintf($lien, $rev->odg, $rev->campagne, $rev->etablissement_identifiant, $rev->code_douane, $rev->ligne_identifiant) ?>">&nbsp;</a>
                </td>
            </tr>
            <?php endif;?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</fieldset>
