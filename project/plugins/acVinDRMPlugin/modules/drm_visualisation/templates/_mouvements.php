<?php 
use_helper('DRM');
use_helper('Float');
use_helper('Date'); 
use_helper('Mouvement');

if(!isset($isTeledeclarationMode)) {
    $isTeledeclarationMode = false;
}
 ?>

<?php if (count($mouvements) > 0): ?>
    <?php if (isset($hamza_style)) : ?>
        <?php
        include_partial('global/hamzaStyle', array('table_selector' => '#table_mouvements',
            'mots' => mouvement_get_words($mouvements),
            'consigne' => "Saisissez un produit, un type de mouvement, un numéro de contrat, un pays d'export, etc. :"))
        ?>
    <?php endif; ?>

    <table id="table_mouvements" class="table_recap">
        <thead>
            <tr>
                <?php if (!$isTeledeclarationMode): ?>
                <th style="width: 225px;">Date de modification</th>
                <?php endif; ?>
                <th style="width: 225px;">Produits</th>
                <th>Type</th>
                <th>Volume&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_validation_aide5'); ?>"></a></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
    <?php
    foreach ($mouvements as $mouvement): 
        $i++; ?>
                <tr id="<?php echo mouvement_get_id($mouvement) ?>" class="<?php echo ($i % 2 != 0)? "alt" : "";?> <?php
        echo ($mouvement->facturable && (!$isTeledeclarationMode || $visualisation))? " facturable" : ""; ?>">
                    <?php if(!$isTeledeclarationMode): ?>
                    <td>
			<a title="Saisi le <?php echo format_date($mouvement->date_version, 'D') ?>" href="<?php echo url_for('redirect_visualisation', array('id_doc' => $mouvement->doc_id)) ?>"><?php echo acCouchdbManager::getClient($mouvement->type)->getLibelleFromId($mouvement->doc_id) ?><?php echo ($mouvement->version) ? ' ('.$mouvement->version.')' : '' ?></a><br/>
                        <small><em>Mvt saisi le <?php echo format_date($mouvement->date_version, 'D') ?></em></small>
                    </td>
                    <?php endif; ?>
                    </td>
                    <td><?php echo $mouvement->produit_libelle ?> </td>
                    <td><?php
                        if ($mouvement->vrac_numero) {
                            echo (!isset($no_link) || !$no_link) ? '<a href="' . url_for("vrac_visualisation", array("numero_contrat" => $mouvement->vrac_numero)) . '">' : '';
                            echo $mouvement->type_libelle . ' ' . $mouvement->numero_archive;
                            echo (!isset($no_link) || !$no_link) ? '</a>' : '';
                        } else {
                            echo $mouvement->type_libelle . ' ' . $mouvement->detail_libelle;
                        }
                        ?></td>
                    <td <?php echo ($mouvement->volume > 0) ? ' class="positif"' : 'class="negatif"'; ?> >
        <?php echoSignedFloat($mouvement->volume); ?>
                    </td>
                </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <h2>AUCUN MOUVEMENT</h2>
<?php endif; ?>
