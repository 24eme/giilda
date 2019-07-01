<?php
use_helper('Date');
use_helper('DRM');
$isTeledeclarationMode = (!isset($isTeledeclarationMode))? false : $isTeledeclarationMode;
$fc = FactureClient::getInstance();
?>
<h2>Historique des factures</h2>

<form id="choix-campagne" method="POST" class="ligne_btn">
    <?= $campagneForm->renderGlobalErrors() ?>
    <?= $campagneForm->renderHiddenFields() ?>
    <?= $campagneForm['campagne']->renderLabel() ?>
    <?= $campagneForm['campagne']->render() ?>
    <button class="btn_majeur btn_vert" type="submit" form="choix-campagne" >Changer</button>
    <span class="infobulle" data-infobulle="<?= getHelpMsgText('drm_calendrier_aide1'); ?>"><i class="icon-msgaide size-24"></i></span>
</form>

<?php if(count($factures->getRawValue())==0) : ?>
    <p>
        <?php if(!$isTeledeclarationMode): ?>Il n'existe aucune facture générée pour cet établissement<?php else: ?>Vous n'avez aucune facture.<?php endif; ?>
    </p>
<?php else : ?>
    <table class="table_recap table_compact">
        <thead>
            <tr>
                <th>Num.</th>
                <th>Date de Facturation</th>
                <?php if(!$isTeledeclarationMode): ?><th>Documents</th>
                <th>Type de facture</th><?php endif ?>
                <th>Prix TTC</th>
                <th><?php if(!$isTeledeclarationMode): ?>Actions<?php else: ?>Facture<?php endif ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($factures->getRawValue() as $facture) :
                  $f = FactureClient::getInstance()->find($facture->id);
                  $numero_facture = $facture->value[5];?>
                <tr>
                    <td><?php echo $numero_facture; ?>
                      <?php if ($fc->isRedressable($facture) && !$isTeledeclarationMode): ?>
                      <br/>
                        <a href="<?php echo url_for('defacturer',array('identifiant' => str_replace('FACTURE-', '',$facture->key[FactureEtablissementView::KEYS_FACTURE_ID]))); ?>"
                           style="color: #2160cf;font-style: italic;text-decoration: underline;" onclick='return confirm("Souhaitez-vous confirmer la défacturation de la facture <?php echo $numero_facture ?> ?")' >défacturer</a>
                      <?php endif; ?>
                      <?php
                      if(!$isTeledeclarationMode && $fc->isRedressee($facture)):
                          echo '<br/><strong>(redressée)</strong>';
                        endif;
                        if($f->isAvoir()):
                            echo '<br/><strong>Avoir</strong>';
                        endif;
                        ?>
                    </td>
                    <td><?php

                        $d = format_date($facture->value[FactureEtablissementView::VALUE_DATE_FACTURATION],'dd/MM/yyyy');
                        if(!$isTeledeclarationMode){
                          $d.=' <br/>(créée&nbsp;'.$fc->getDateCreation($facture->id).')';
                        }
                        echo $d;
                        ?>
                    </td>
                    <?php if(!$isTeledeclarationMode): ?>
                      <td>
                        <?php foreach ($facture->value[FactureEtablissementView::VALUE_ORIGINES] as $drmid => $drmlibelle) {
                            if (strstr($drmlibelle, 'DRM') !== false) {
                                $drmIdFormat = DRMClient::getInstance()->getLibelleFromId($drmlibelle, true);
                                $viti = DRMClient::getInstance()->find($drmid)->declarant->nom;
                            } else {
                                $drmIdFormat = SV12Client::getInstance()->getLibelleFromId($drmlibelle);
                                $viti = SV12Client::getInstance()->find($drmid)->declarant->nom;
                            }
                            echo "<span class='infobulle' data-infobulle='".$viti."'>"
                                .  link_to(str_replace(" ",'&nbsp;',$drmIdFormat), 'facture_redirect_to_doc', array('iddocument' => $drmid))
                                . "</span><br/>";
                        } ?>
                     </td>
                    <td><?php echo ($f->exist('facture_electronique') && $f->facture_electronique)? "éléctronique" : "papier"; ?></td>
                    <?php endif; ?>
                    <td><?php echoFloat($facture->value[FactureEtablissementView::VALUE_TOTAL_TTC]); ?>&nbsp;€</td>
                  <td>
                    <a href="<?php echo url_for('facture_pdf', array('identifiant' => $facture->key[FactureEtablissementView::KEYS_FACTURE_ID])); ?>" class="btn_majeur btn_pdf center" style="font-size: 9px;" ><span>Télécharger</span></a>
                  </td>
                </tr>
<?php
endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
