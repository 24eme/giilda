<div class="drm_validation_societe_info">
    <div class="title"><?php echo $drm->societe->raison_sociale; ?></div>
    <div class="panel">
        <ul>
            <li>
                <span class="label">SIRET :</span>
                <span class="infos"><?php echo $drm->societe->siret; ?></span>
            </li>
            <li>
                <span class="label">Adresse :</span>
                <span class="infos"><?php echo $drm->societe->adresse; ?></span>
            </li>
            <li>
                <span class="label">Code postal :</span>
                <span class="infos"><?php echo $drm->societe->code_postal; ?></span>
            </li>
            <li>
                <span class="label">Commune :</span>
                <span class="infos"><?php echo $drm->societe->commune; ?></span>
            </li>
            <li>
                <span class="label">E-mail :</span>
                <span class="infos"><?php echo $drm->societe->email; ?></span>
            </li>
            <li>
                <span class="label">Téléphone :</span>
                <span class="infos"><?php echo $drm->societe->telephone; ?></span>
            </li>
            <li>
                <span class="label">Fax :</span>
                <span class="infos"><?php echo $drm->societe->fax; ?></span>
            </li>
        </ul>
        <?php if($isModifiable): ?>
        <div id="btn_etape_dr">
            <a href="#" class="btn_majeur btn_modifier" style="float: right;" id="drm_validation_societe_info_btn"><span>modifier</span></a>
        </div>
        <?php endif; ?>
    </div>
</div>
