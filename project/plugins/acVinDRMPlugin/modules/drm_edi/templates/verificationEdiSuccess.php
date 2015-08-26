<?php 
$csvDoc =  $drmCsvEdi->csvDoc;
?>
<section id="principal">
    <h2>Import d'une DRM <?php echo $csvDoc->statut; ?></h2>
    <?php if (count($csvDoc->erreurs)): ?>
        <h2>Rapport d'erreurs</h2>
        <table class="table_recap">
            <thead>
                <tr>                        
                    <th>Numéro de ligne</th>
                    <th>Erreur</th>
                    <th>Raison</th>
                </tr>
            </thead>
            <?php foreach ($csvDoc->erreurs as $erreur) : ?>
                <tr>                        
                    <td><?php echo $erreur->num_ligne; ?></td>
                    <td style="<?php echo ($csvDoc->statut == DRMCsvEdi::STATUT_ERREUR) ? "color: darkred;" : "color: goldenrod;"; ?>" ><?php echo $erreur->csv_erreur; ?></td>
                    <td style="color: darkgray"><?php echo $erreur->diagnostic; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br/>
    <?php endif; ?>
        <div class="btn_etape">
            <a class="btn_etape_prec" href="<?php echo url_for('drm_societe', array('identifiant' => $identifiant)); ?>">
                <span>Précédent</span>
            </a>
            <?php if ($csvDoc->statut != DRMCsvEdi::STATUT_ERREUR): ?>
            <a href="<?php echo url_for('drm_creation_fichier_edi', array('periode' => $periode, 'md5' => $md5,'identifiant' => $identifiant)); ?>" class="btn_majeur btn_vert" style="float: right;">Importer la DRM</a>
            <?php endif; ?>
        </div>
    <br>
</section>