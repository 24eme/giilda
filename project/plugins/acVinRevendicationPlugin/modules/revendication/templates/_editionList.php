<?php
use_helper('Float');
use_helper('Date');
use_helper('Revendication');
?>
<fieldset id="revendication_volume_revendiques_edition">
    <?php if (isset($revendications) && count($revendications)) : ?>
        <?php if (isset($revendication) && count($revendication->_attachments)) :  ?>
        <div class="generation_facture_options" style="text-align: center; margin-top: 20px;">
            <a class="btn_majeur btn_excel" href="<?php echo url_for('revendication_downloadCSV', $revendication); ?>">Télécharger le fichier originel</a>
            <a class="btn_majeur btn_excel" href="<?php echo url_for('revendication_download_imported_rowsCSV', $revendication); ?>">Télécharger le fichier des lignes importées</a>

        </div>
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
                <th style="width: 100px;">Volume<br />(en hl)</th>
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
            <tr id="<?php echo revendication_get_id($rev); ?>">
                <td><?php echo $rev->odg ?></td>
                <td><?php echo isset($rev->date_traitement)? format_date($rev->date_traitement, 'dd/MM/yyyy') : 'N/A';
                          echo ' ('.$rev->num_certif.')';  ?></td>
                <td><?php echo $rev->declarant_cvi; ?></td>
                <td>
                <?php
                if ($rev->bailleur_nom)
                echo 'Bailleur : '.$rev->bailleur_nom.' (en metayage avec : ' . $rev->declarant_nom . ')';
                else
                echo $rev->declarant_nom;
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
<?php else: ?>
<p>Aucun volume revendiqué</p>
<?php endif; ?>
</fieldset>
