<?php
use_helper('Float');
$has_import_sv12 = ($sv12->totaux->exist('sv12_mouts') + $sv12->totaux->exist('sv12_raisins'));
?>
<style>
    .td_sv12 {color: #aaa;}
</style>
<fieldset id="recapitulatif_sv12">
        <table class="table_recap">
        <thead>
        <tr>
            <th rowspan="2">Produits</th>
            <th<?php echo ($has_import_sv12) ? ' colspan="2"': ''; ?>>Volume de raisins</th>
            <th<?php echo ($has_import_sv12) ? ' colspan="2"': ''; ?>>Volume de moûts</th>
            <th<?php echo ($has_import_sv12) ? ' colspan="2"': ''; ?>>Total</th>
        </tr>
        <tr>
            <th>Σ contrats</th>
            <?php if ($has_import_sv12): ?><th class="td_sv12">Σ imports</th><?php endif; ?>
            <th>Σ contrats</th>
            <?php if ($has_import_sv12): ?><th class="td_sv12">Σ imports</th><?php endif; ?>
            <th>Σ contrats</th>
            <?php if ($has_import_sv12): ?><th class="td_sv12">Σ imports</th><?php endif; ?>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($sv12->totaux->produits as $libelle => $produit) :  ?>
            <tr>
                <td>
                    <?php echo $libelle; ?>
                </td>
                <td>
                    <?php echoFloat($produit->volume_raisins).' hl'; ?>
                </td>
<?php if ($has_import_sv12): ?>
                <td class="td_sv12">
                    <?php echoFloat($produit->sv12_raisins).' hl'; ?>
                </td>
<?php endif; ?>
                <td>
                    <?php echoFloat($produit->volume_mouts).' hl'; ?>
                </td>
<?php if ($has_import_sv12): ?>
                <td class="td_sv12">
                    <?php echoFloat($produit->sv12_mouts).' hl'; ?>
                </td>
<?php endif; ?>
                <td>
                    <?php echoFloat($produit->volume_raisins + $produit->volume_mouts + $produit->volume_ecarts).' hl'; ?>
                </td>
<?php if ($has_import_sv12): ?>
                <td class="td_sv12">
                    <?php echoFloat($produit->sv12_raisins + $produit->sv12_mouts).' hl'; ?>
                </td>
<?php endif; ?>
            </tr>
            <?php
            endforeach;
            ?>
            <tr style="font-weight:bold;">
                <td>Total</td>
                <td>
                    <?php echoFloat($sv12->totaux->volume_raisins).' hl'; ?>
                </td>
<?php if ($has_import_sv12): ?>
                <td class="td_sv12">
                    <?php echoFloat($sv12->totaux->sv12_raisins).' hl'; ?>
                </td>
<?php endif; ?>
                <td>
                    <?php echoFloat($sv12->totaux->volume_mouts).' hl'; ?>
                </td>
<?php if ($has_import_sv12): ?>
                <td class="td_sv12">
                    <?php echoFloat($sv12->totaux->sv12_mouts).' hl'; ?>
                </td>
<?php endif; ?>
                <td>
                    <?php echoFloat($sv12->volume_total).' hl'; ?>
                </td>
<?php if ($has_import_sv12): ?>
                <td class="td_sv12">
                    <?php echoFloat($sv12->totaux->sv12_mouts + $sv12->totaux->sv12_raisins).' hl'; ?>
                </td>
<?php endif; ?>
            </tr>
        </tbody>
        </table>
</fieldset>
