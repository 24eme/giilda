<?php
$societe_info = $drm->getSocieteInfos();
if (isset($societe_info->paiement_douane_frequence)) {
    $paiement_douane_frequence = (!$societe_info->paiement_douane_frequence) ? 'Non défini' : DRMPaiement::$frequence_paiement_libelles[$societe_info->paiement_douane_frequence];
}
if (isset($societe_info->paiement_douane_moyen)) {
    $paiement_douane_moyen = (!$societe_info->paiement_douane_moyen) ? 'Non défini' : DRMPaiement::$moyens_paiement_libelles[$societe_info->paiement_douane_moyen];
}
?>
<div class="drm_validation_societe_info">
    <div class="title"><span class="text">VOTRE SOCIÉTÉ</span><?php if($isModifiable): ?>&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_validation_aide1'); ?>"></a><?php endif; ?></div>
    <div class="panel">
        <p style="text-align: center"><strong><?php echo $societe_info->raison_sociale; ?></strong></p>
        <br />
        <strong>N° SIRET</strong> : <?php echo $societe_info->siret; ?><br />
        <br />
        <br />
        <strong>Adresse :</strong><br />
        <?php echo $societe_info->adresse; ?><br />
        <?php echo $societe_info->code_postal; ?> <?php echo $societe_info->commune; ?><br />
        <br />
        <strong>Email</strong> : <?php echo $societe_info->email; ?><br />
        <strong>Téléphone / Fax</strong> : <?php echo $societe_info->telephone; ?> / <?php echo $societe_info->fax; ?><br />
        <br/>
        <?php if (isset($paiement_douane_frequence) && isset($paiement_douane_moyen)): ?>
        <strong>Paiement des douanes :</strong><br/>
        Fréquence / Moyen : <?php echo $paiement_douane_frequence; ?> / <?php echo $paiement_douane_moyen; ?>
        <?php endif; ?>
        <?php if ($isModifiable): ?>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_validation_update_societe', $drm); ?>" class="btn_majeur btn_modifier" style="float: right;" id="drm_validation_societe_info_btn"><span>modifier</span></a>
            </div>
        <?php endif; ?>
    </div>
</div>
