<section id="principal">
    <?php if(isset($drm)): ?>
        <h2>Bilan de l'import de la DRM de <?php echo $drm->getHumanPeriode(); ?> (<?php echo $drm->identifiant ?>)</h2>
    <?php else: ?>
        <h2>Import d'une DRM</h2>
    <?php endif; ?>
    <?php if (count($csvDoc->erreurs)): ?>
        <h2>Rapport d'erreurs</h2>
        <table class="table_recap">
            <thead>
                <tr>
                    <th>Numéro de ligne</th>
                    <th>Type</th>
                    <th>Raison</th>
                </tr>
            </thead>
            <?php foreach ($csvDoc->erreurs as $erreur) : ?>
                <tr>
                    <td><?php echo $erreur->num_ligne; ?></td>
                    <td style="<?php echo ($csvDoc->statut == DRMCsvEdi::STATUT_ERROR) ? "color: darkred;" : "color: goldenrod;"; ?>" ><?php echo $erreur->csv_erreur; ?></td>
                    <td style="color: darkgray"><?php echo $erreur->diagnostic; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br/>
    <?php else: ?>
        <h2>Le fichier a été intégré sans problème</h2>
        <br/>
    <?php endif; ?>
    <p><a href="<?php echo url_for('drm_csv_edi', array('identifiant' => $csvDoc->identifiant, 'periode' => $csvDoc->periode)) ?>">Télécharger le fichier versé</a></p>
    <br/>
    <?php if(!isset($drm)): ?>
    <br>
    <h2>Utiliser un autre fichier</h2>
    <form action="<?php echo url_for('drm_verification_fichier_edi', array('identifiant' => $identifiant, 'periode' => $periode, 'md5' => $md5)); ?>" method="post" enctype="multipart/form-data">
        <?php echo $creationEdiDrmForm->renderHiddenFields(); ?>
        <?php echo $creationEdiDrmForm->renderGlobalErrors(); ?>
        <div style="display: none;">
            <?php echo $creationEdiDrmForm['type_creation']->render(); ?>
        </div>
        <div class="ligne_form" >
            <span>
                <?php echo $creationEdiDrmForm['edi-file']->renderError(); ?>
                <?php echo $creationEdiDrmForm['edi-file']->renderLabel() ?>
                <?php echo $creationEdiDrmForm['edi-file']->render(); ?>
            </span>
            <div class="ligne_btn">
              <button id="drm_nouvelle_popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Recharger un fichier</span></button>
            </div>
        </div>
    </form>
    <br/><br/>
    <h2>Importer tout de même</h2>
    <p>Si les messages pointés ne vous paraissent pas problématique (ou que vous pouvez les résoudre directement en éditant la DRM), vous pouvez importer ces données.</p>
    <br/><br/>
    <div class="btn_etape">
        <a class="btn_etape_prec" href="<?php echo url_for('drm_societe', array('identifiant' => $identifiant)); ?>">
            <span>Précédent</span>
        </a>
        <?php if ($csvDoc->statut != DRMCsvEdi::STATUT_ERROR): ?>
            <a href="<?php echo url_for('drm_creation_fichier_edi', array('periode' => $periode, 'md5' => $md5, 'identifiant' => $identifiant)); ?>" class="btn_majeur btn_vert" style="float: right;">Importer la DRM</a>
        <?php endif; ?>
    </div>
    <?php else: ?>
        <div class="btn_etape">
            <a class="btn_etape_prec" href="<?php echo url_for('drm_visualisation', $drm); ?>">
                <span>Précédent</span>
            </a>
        </div>
    <?php endif; ?>
</section>
