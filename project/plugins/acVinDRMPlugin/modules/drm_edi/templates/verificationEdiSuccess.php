<section id="principal">
    <h2>Import d'une DRM <?php echo $drmCsvEdi->statut; ?></h2>
    <?php if (count($drmCsvEdi->erreurs)): ?>
        <h2>Rapport d'erreurs</h2>
        <table class="table_recap">
            <thead>
                <tr>                        
                    <th>Numéro de ligne</th>
                    <th>Erreur</th>
                    <th>Raison</th>
                </tr>
            </thead>
            <?php foreach ($drmCsvEdi->erreurs as $erreur) : ?>
                <tr>                        
                    <td><?php echo $erreur->num_ligne; ?></td>
                    <td style="<?php echo ($drmCsvEdi->statut == DRMCsvEdi::STATUT_ERREUR) ? "color: darkred;" : "color: goldenrod;"; ?>" ><?php echo $erreur->erreur_csv; ?></td>
                    <td style="color: darkgray"><?php echo $erreur->raison; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br/>
        <div class="btn_etape">
            <a class="btn_etape_prec" href="<?php echo url_for('drm_societe', array('identifiant' => $identifiant)); ?>">
                <span>Précédent</span>
            </a>
            <?php if ($drmCsvEdi->statut == DRMCsvEdi::STATUT_WARNING): ?>
            <a href="<?php echo url_for('drm_creation_fichier_edi', array('periode' => $periode, 'md5' => $md5,'identifiant' => $identifiant)); ?>" class="btn btn_majeur btn_etape_suiv" style="float: right;">Importer la DRM</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <br>
</section>