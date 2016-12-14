<?php use_helper('Float'); ?>
<table class="table table-bordered table-striped table-condensed">
    <thead>
    <tr>
        <th>Produits</th>
        <th class="text-center">Volume de raisins</th>
        <th class="text-center">Volume de moûts</th>
        <th class="text-center">Total</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($sv12->totaux->produits as $libelle => $produit) :  ?>
        <tr>
            <td>
                <?php echo $libelle; ?>
            </td>
            <td class="text-right">
                <?php echoFloat($produit->volume_raisins); ?>&nbsp;hl
            </td>

            <td class="text-right">
                <?php echoFloat($produit->volume_mouts); ?>&nbsp;hl
            </td>

            <td class="text-right">
                <?php echoFloat($produit->volume_raisins + $produit->volume_mouts + $produit->volume_ecarts); ?>&nbsp;hl
            </td>
        </tr>
        <?php
        endforeach;
        ?>
        <tr>
            <td style="font-weight:bold;">Total</td>
            <td style="font-weight:bold;"  class="text-right">
                <?php echoFloat($sv12->totaux->volume_raisins); ?>&nbsp;hl
            </td>
            <td style="font-weight:bold;"  class="text-right">
                <?php echoFloat($sv12->totaux->volume_mouts); ?>&nbsp;hl
            </td>
            <td style="font-weight:bold;" class="text-right">
                <?php echoFloat($sv12->volume_total); ?>&nbsp;hl
            </td>
        </tr>
    </tbody>
</table>
