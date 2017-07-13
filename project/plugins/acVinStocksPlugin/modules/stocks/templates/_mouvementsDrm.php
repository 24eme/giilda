
<?php
use_helper('Float');
use_helper('Date');
use_helper('Mouvement');

if (!isset($isTeledeclarationMode)) {
    $isTeledeclarationMode = false;
}
$hasDontRevendique = ConfigurationClient::getCurrent()->hasDontRevendique();
?>
<?php  if (isset($mouvementsByProduit[$typeKey]) && count($mouvementsByProduit[$typeKey]) > 0): ?>
    <?php if (isset($hamza_style)) : ?>
      <?php include_partial('global/hamzaStyle', array('table_selector' => '#table_mouvements',
                                                       'mots' => mouvement_get_words($mouvementsByProduit[$typeKey]),
                                                       'consigne' => "Saisissez un mouvement, une drm ou un produit :")) ?>
    <?php endif; ?>
    <table id="table_mouvements" class="table table-striped table-condensed">
        <thead>
            <tr>
                <th class="col-xs-2">DRM</th>
                <th class="col-xs-4">Produits</th>
                <th class="<?php if($hasDontRevendique): ?>col-xs-3<?php else: ?>col-xs-4<?php endif; ?>">Mouvement</th>
                <th class="<?php if($hasDontRevendique): ?>col-xs-1<?php else: ?>col-xs-2<?php endif; ?>"><span class="pull-right">Volume</span></th>
                <?php if($hasDontRevendique): ?>
                  <th class="col-xs-2"><span class="pull-left">(dont revendiqué)</span></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($mouvementsByProduit[$typeKey] as $produit_hash => $mouvements): ?>
                <?php foreach ($mouvements as $mouvement):
                  $libelleDoc = DRMClient::getInstance()->getLibelleFromId($mouvement->doc_id);
                  $produit_libelle = $mouvement->produit_libelle;
                  $drm_periode = DRMClient::getInstance()->getPeriodeFromId($mouvement->doc_id);
                  $drm_version = DRMClient::getInstance()->getVersionLibelleFromId($mouvement->doc_id);
                  ?>
                    <tr data-words='<?php echo json_encode(array_merge(array(strtolower($mouvement->produit_libelle), strtolower($mouvement->type_libelle), strtolower($mouvement->detail_libelle),strtolower($libelleDoc))), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>' id="<?php echo mouvement_get_id($mouvement) ?>" class="hamzastyle-item <?php echo ($mouvement->facturable && (!$isTeledeclarationMode || $visualisation)) ? " facturable" : ""; ?>">
                        <td><a href="<?php echo url_for('drm_visualisation', array('identifiant' => $identifiant, 'periode_version' => $drm_periode.$drm_version)); ?>"><?php  echo $libelleDoc; ?><br/><span class="font-style: italic;"><?php echo 'mvts du '.Date::francizeDate($mouvement->date_version); ?></span></a></td>
                        <td><a href="#tab=mouvements_<?php echo $typeKey ?>&filtre=<?php echo strtolower($produit_libelle); ?>"><?php if($drm_version): ?><small class="text-muted"><?php echo ($mouvement->version) ? $mouvement->version : "M00" ?></small> <?php endif; ?>
                          <?php echo $mouvement->produit_libelle; ?>
                        </a></td>
                        <td><?php
                            if ($mouvement->vrac_numero && $mouvement->vrac_numero!="SUPPRIME") {
                                echo (!isset($no_link) || !$no_link) ? '<a href="' . url_for("vrac_visualisation", array("numero_contrat" => $mouvement->vrac_numero)) . '">' : '';
                                if(preg_match("/^(creationvractirebouche_details|creationvrac_details)/",$mouvement->type_hash)){
                                  echo $mouvement->type_libelle . ' ' . $mouvement->detail_libelle;
                                }else{
                                  echo $mouvement->type_libelle . ' ' . $mouvement->numero_archive;
                                }
                                echo (!isset($no_link) || !$no_link) ? '</a>' : '';
                            } else {
                                echo $mouvement->type_libelle . ' ' . $mouvement->detail_libelle;
                                if($mouvement->vrac_numero == "SUPPRIME"){
                                  echo " supprimé";
                                }
                            }
                            ?>
                        </td>
                        <td <?php echo ($mouvement->volume > 0) ? ' class="positif"' : 'class="negatif"'; ?> >
                            <span class="pull-right"><?php echoSignedFloat($mouvement->volume); ?></span>
                        </td>
                      <?php if($hasDontRevendique): ?>  <td></td> <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-center"><em>Aucun mouvement</em></p>
<?php endif; ?>
