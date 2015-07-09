<div class="drm_validation_societe_info">
    <div class="title"><span class="text"><?php echo $drm->getSocieteInfos()->raison_sociale; ?></span></div>
    <div class="panel">
        <ul>
            <li>
                <span class="label">SIRET :</span>
                <span class="infos"><?php echo $drm->getSocieteInfos()->siret; ?></span>
            </li>
            <li>
                <span class="label">Adresse :</span>
                <span class="infos"><?php echo $drm->getSocieteInfos()->adresse; ?></span>
            </li>
            <li>
                <span class="label">Code postal :</span>
                <span class="infos"><?php echo $drm->getSocieteInfos()->code_postal; ?></span>
            </li>
            <li>
                <span class="label">Commune :</span>
                <span class="infos"><?php echo $drm->getSocieteInfos()->commune; ?></span>
            </li>
            <li>
                <span class="label">E-mail :</span>
                <span class="infos"><?php echo $drm->getSocieteInfos()->email; ?></span>
            </li>
            <li>
                <span class="label">Téléphone :</span>
                <span class="infos"><?php echo $drm->getSocieteInfos()->telephone; ?></span>
            </li>
            <li>
                <span class="label">Fax :</span>
                <span class="infos"><?php echo $drm->getSocieteInfos()->fax; ?></span>
            </li>
        </ul>
        <?php if($isModifiable): ?>
        <div id="btn_etape_dr">
            <a href="#" class="btn_majeur btn_modifier" style="float: right;" id="drm_validation_societe_info_btn"><span>modifier</span></a>
        </div>
        <?php endif; ?>
    </div>
</div>
