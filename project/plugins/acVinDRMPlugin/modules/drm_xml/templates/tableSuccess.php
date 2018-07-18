<?php
use_helper("DRMXml");
?>
<div id="principal">
    <?php include_partial('drm/header', array('drm' => $drm)); ?>

    <h2>Visualisation de l'XML <?php if($retour) : ?>reçu Douane <?php else: ?>transmis <?php endif; ?><a style="text-decoration: underline;
    font-size: 8pt;" href="<?php if($retour) : echo url_for('drm_retour', $drm); else: echo url_for('drm_xml', $drm); endif; ?>"><small>(fichier XML brut)</small></a>
        <a class="btn_majeur" style="float:right;" href="<?php echo url_for('drm_xml_table', array('identifiant' => $drm->identifiant,"periode_version" => $drm->getPeriodeAndVersion(), 'retour' => intval(!$retour))); ?>"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;<?php if(!$retour) : ?> XML reçu Douane <?php else: ?> XML transmis <?php endif; ?></a>
    </h2>

    <?php if(!count($xml_table)): ?>
        <table class="table_recap">
        <thead>
            <tr>
                <th colspan="8"><h4>Absense de l'XML</h4></th>
            </tr>
        </thead>
    </table>
    <?php else: ?>
      <table class="table_recap">
        <thead>
            <tr>
                <th colspan="8"><h4>Description général</h4></th>
            </tr>
        </thead>
      <tbody>
            <?php echo xmlPartOfToTable($xml_table,array("identification-declarant","periode","declaration-neant")); ?>
      </tbody>
    </table>
    <br/>
    <h4 style="font-weight: bold; padding: 10px;">droits-suspendus</h4>

    <table class="table_recap">
        <tbody>
              <?php echo xmlProduitsToTable($xml_table,"droits-suspendus"); ?>
        </tbody>
  </table>
  <br/>
  <h4 style="font-weight: bold; padding: 10px;">droits-acquittes</h4>

  <table class="table_recap">
      <tbody>
            <?php echo xmlProduitsToTable($xml_table,"droits-acquittes"); ?>
      </tbody>
</table>

<br/>
<h4 style="font-weight: bold; padding: 10px;">compte-crd</h4>

<table class="table_recap">
    <tbody>
          <?php echo xmlCrdsToTable($xml_table,"compte-crd"); ?>
    </tbody>
</table>
<?php endif; ?>

<div id="btn_etape_dr">
    <a href="<?php echo url_for('drm_visualisation', $drm); ?>" class="btn_etape_prec"><span>Retour à la visualisation de la DRM</span></a>
</div>

</div>

<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => false));
?>
