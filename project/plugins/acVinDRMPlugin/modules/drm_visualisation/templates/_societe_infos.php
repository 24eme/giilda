<?php
$paiement_douane_frequence = (!$drm->getSocieteInfos()->paiement_douane_frequence) ? 'Non défini' : DRMPaiement::$frequence_paiement_libelles[$drm->getSocieteInfos()->paiement_douane_frequence];
$paiement_douane_moyen = (!$drm->getSocieteInfos()->paiement_douane_moyen) ? 'Non défini' : DRMPaiement::$moyens_paiement_libelles[$drm->getSocieteInfos()->paiement_douane_moyen];
?>
<div class="drm_validation_societe_info">
    <div class="title"><span class="text">VOTRE SOCIÉTÉ</span></div>
    <div class="panel">
        <p style="text-align: center"><strong><?php echo $drm->getSocieteInfos()->raison_sociale; ?></strong></p>
        <br />
        <strong>N° SIRET</strong> : <?php echo $drm->getSocieteInfos()->siret; ?><br />
        <br />
        <br />
        <strong>Adresse :</strong><br />
        <?php echo $drm->getSocieteInfos()->adresse; ?><br />
        <?php echo $drm->getSocieteInfos()->code_postal; ?> <?php echo $drm->getSocieteInfos()->commune; ?><br />
        <br />
        <strong>Email</strong> : <?php echo $drm->getSocieteInfos()->email; ?><br />
        <strong>Téléphone / Fax</strong> : <?php echo $drm->getSocieteInfos()->telephone; ?> / <?php echo $drm->getSocieteInfos()->fax; ?><br />
        <br/>
        <strong>Paiement des douanes :</strong><br/>
        Fréquence / Moyen : <?php echo $paiement_douane_frequence; ?> / <?php echo $paiement_douane_moyen; ?>

        <?php if ($isModifiable): ?>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_validation_update_societe', $drm); ?>" class="btn_majeur btn_modifier" style="float: right;" id="drm_validation_societe_info_btn"><span>modifier</span></a>
            </div>
        <?php endif; ?>
    </div>
</div>
