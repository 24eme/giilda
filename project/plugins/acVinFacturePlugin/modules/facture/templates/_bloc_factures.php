<?php
use_helper('Date');
use_helper('Float');
?>
<ul style="<?php if (!isset($btnAccess)): ?>height: auto<?php endif; ?>" class="">
    <li>
      <div class="etablissement_drms">
        <table class="table_recap table_compact">
            <thead>
                <tr>
                    <th class="center">Num.</th>
                    <th class="center">Prix TTC</th>
                </tr>
            </thead>
            <tbody>
              <?php
                $cpt = 0;
                foreach ($facturesSocietesWithInfos as $facture) :
                  if($cpt > 2){ break; }
                  $cpt++;
                  $numero_facture = $facture->value[5];
                ?>
                <tr>
                    <td><?php echo $numero_facture; ?></td>
                    <td><?php echoFloat($facture->value[FactureEtablissementView::VALUE_TOTAL_TTC]); ?>&nbsp;€</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
    </li>
</ul>
