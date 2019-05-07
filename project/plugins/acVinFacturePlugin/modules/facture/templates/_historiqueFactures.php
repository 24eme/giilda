<?php
use_helper('Date');
$isTeledeclarationMode = (!isset($isTeledeclarationMode))? false : $isTeledeclarationMode;
$k = 0;
?>
<h2>Historique des factures</h2>
<?php
if(count($factures->getRawValue())==0) :
?>
<p>
    <?php if(!$isTeledeclarationMode): ?>Il n'existe aucune facture générée pour cet établissement<?php else: ?>Vous n'avez aucune facture.<?php endif; ?>
</p>
<?php else : ?>
<fieldset>
    <table class="table_recap table_compact">
        <thead>
            <tr>
                <th>Num.</th>
                <th>Date</th>
                <?php if(!$isTeledeclarationMode): ?><th>Documents</th><?php endif ?>
                <th>Type de paiement</th>
                <th>Etat</th>
                <th>Prix TTC</th>
                <th><?php if(!$isTeledeclarationMode): ?>Actions<?php else: ?>Export<?php endif ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($factures->getRawValue() as $facture) :
                ?>
                <tr>
                    <td><?php echo $facture->value[5]; ?></td>
                    <td><?php
                        $fc = FactureClient::getInstance();
                        $d = format_date($facture->value[FactureEtablissementView::VALUE_DATE_FACTURATION],'dd/MM/yyyy');
                        if(!$isTeledeclarationMode){
                          $d.=' <br/>(créée&nbsp;'.$fc->getDateCreation($facture->id).')';
                        }
                        echo $d;
                        ?>
                    </td>
                    <?php if(!$isTeledeclarationMode): ?>
                      <td>
                        <?php
                        $drmIdFormatted = array();
                        foreach ($facture->value[FactureEtablissementView::VALUE_ORIGINES] as $drmid => $drmlibelle) {
                          $drmIdFormat = (strstr($drmlibelle, 'DRM')!==FALSE)? DRMClient::getInstance()->getLibelleFromId($drmlibelle,true) :
                          SV12Client::getInstance()->getLibelleFromId($drmlibelle);
                          $drmIdFormatted[$drmIdFormat] = $drmIdFormat;
                          echo link_to(str_replace(" ",'&nbsp;',$drmIdFormat), 'facture_redirect_to_doc', array('iddocument' => $drmid)) . "<br/>";
                        }
                        foreach ($drmIdFormatted as $drmIdFormat) {
                          // echo $drmIdFormat."<br/>";
                        }
                       ?>
                     </td>
                  <?php endif; ?>
                    <td><?php echo "prélèvement"; ?></td>
                    <td><?php echo (!$k)? "<span class='btn btn_majeur btn_orange label'>En attente</span><br/><p style='margin: 8px;'>(prévu&nbsp;le&nbsp;28/05/2019)</p>" : "<span class='btn btn_majeur btn_vert label'>Réglée</span>"; ?></td>
                    <td><?php echoFloat($facture->value[FactureEtablissementView::VALUE_TOTAL_TTC]); ?>&nbsp;€</td>
                  <td>
                    <a href="<?php echo url_for('facture_pdf', array('identifiant' => $facture->key[FactureEtablissementView::KEYS_FACTURE_ID])); ?>" class="btn_majeur btn_pdf center" style="font-size: 9px;" ><span>PDF</span></a>
                    <?php
                    if(!$isTeledeclarationMode):
                      if ($fc->isRedressee($facture)):
                        echo 'redressée';
                        elseif ($fc->isRedressable($facture)): ?>
                        <br/>
                        <a href="<?php echo url_for('defacturer',array('identifiant' => str_replace('FACTURE-', '',$facture->key[FactureEtablissementView::KEYS_FACTURE_ID]))); ?>"
                          class="btn btn_majeur" style="margin: 8px; padding:0 5px; font-size: 9px;" >défacturer</a>
                          <?php
                        endif;
                      endif;
                      ?>
                  </td>
                </tr>
<?php
$k++;
endforeach; ?>
        </tbody>
    </table>
</fieldset>
<?php endif; ?>
