<div class="drm_validation_etablissement_info">
    <div class="title"><?php echo $drm->declarant->nom; ?></div>
    <div class="panel">
        <ul>
            <li>
                <span class="label">CVI :</span>
                <span class="infos"><?php echo $drm->declarant->cvi; ?></span>
            </li>
            <li>
                <span class="label">ACCISE :</span>
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

        </ul>  
        <?php if ($isModifiable): ?>
            <div id="btn_etape_dr">
                <a href="#" class="btn_majeur btn_modifier" style="float: right;" id="drm_validation_etablissement_info_btn"><span>modifier</span></a>
            </div>
        <?php endif; ?>
    </div>
</div>