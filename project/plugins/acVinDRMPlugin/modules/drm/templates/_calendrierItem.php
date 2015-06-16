<?php use_helper('DRM'); ?>
<?php $etablissements = $etablissement->getSociete()->getEtablissementsObj(false); ?>
<?php $multiEtablissement = (count($etablissements) > 1); ?>


<li class="bloc_mois <?php echo getClassEtatDRMCalendrier($calendrier, $periode); ?>">
    <p class="mois"><?php echo $calendrier->getPeriodeLibelle($periode) ?></p>

    <div class="mois_infos">
        <?php if ($isTeledeclarationMode && $multiEtablissement): ?>
            <ul class="liste_etablissements clearfix">
                <?php foreach ($etablissements as $etablissement): ?>
                <?php // var_dump($etablissement->etablissement); exit;?>
                <li class="<?php echo getEtatDRMPictoCalendrier($calendrier, $periode); ?>">
                    <button class="btn_etablissement" type="button"><?php echo $etablissement->etablissement->nom; ?></button>

                    <div class="etablissement_tooltip">
                        <p class="etablissement_nom"><?php echo $etablissement->etablissement->nom; ?></p>
                        <p>Etat : <span class="statut"><?php echo getEtatDRMCalendrier($calendrier, $periode); ?></span></p>
                        <a href="<?php echo getEtatDRMHrefCalendrier($calendrier, $periode); ?>" class="action"><?php echo getEtatDRMLibelleCalendrier($calendrier, $periode); ?></a>
                    </div>
                </li>
                        
                   <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="<?php echo getEtatDRMPictoCalendrier($calendrier, $periode); ?>">
                <p class="etablissement_nom"><?php echo $etablissement->nom; ?></p>
                <p>Etat : <span class="statut"><?php echo getEtatDRMCalendrier($calendrier, $periode); ?></span></p>
                <a href="<?php echo getEtatDRMHrefCalendrier($calendrier, $periode); ?>" class="action"><?php echo getEtatDRMLibelleCalendrier($calendrier, $periode); ?></a>
            </div>
        <?php endif; ?>
    </div>
</li>
