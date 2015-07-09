<div class="drm_validation_etablissement_info">
    <div class="title"><span class="text"><?php echo $drm->declarant->nom; ?></span></div>
    <div class="panel">
        <ul>
            <li>
                <span class="label">CVI :</span>
                <span class="infos"><?php echo $drm->declarant->cvi; ?></span>
            </li>
            <li>
                <span class="label">Accise :</span>
                <span class="infos"><?php echo $drm->declarant->no_accises; ?></span>
            </li>
            <li>
                <span class="label">Adresse :</span>
                <span class="infos"><?php echo $drm->declarant->adresse; ?></span>
            </li>
            <li>
                <span class="label">Code postal :</span>
                <span class="infos"><?php echo $drm->declarant->code_postal; ?></span>
            </li>
            <li>
                <span class="label">Commune :</span>
                <span class="infos"><?php echo $drm->declarant->commune; ?></span>
            </li>
            <?php if ($drm->declarant->exist('adresse_compta')): ?>
                <li>
                    <span class="label">Adresse comptabilité matière :</span>
                    <span class="infos"><?php echo $drm->declarant->adresse_compta; ?></span>
                </li>
            <?php endif; ?>
            <?php if ($drm->declarant->exist('caution')): ?>
                <li>
                    <span class="label">Caution :</span>
                    <span class="infos"><?php echo $drm->declarant->caution; ?></span>
                </li>
            <?php endif; ?>
            <?php if ($drm->declarant->exist('raison_sociale_cautionneur')): ?>
                <li>
                    <span class="label">Raison sociale cautionneur :</span>
                    <span class="infos"><?php echo $drm->declarant->raison_sociale_cautionneur; ?></span>
                </li>
            <?php endif; ?>
        </ul>
        <?php if ($isModifiable): ?>
            <div id="btn_etape_dr">
                <a href="#" class="btn_majeur btn_modifier" style="float: right;" id="drm_validation_etablissement_info_btn"><span>modifier</span></a>
            </div>
        <?php endif; ?>
    </div>
</div>