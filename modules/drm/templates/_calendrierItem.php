<li>
    <p class="mois"><?php echo $calendrier->getPeriodeLibelle($periode) ?></p>
    <?php if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_VALIDEE): ?>
        <ul class="mois_infos valide_campagne">
            <li><label for="">&Eacute;tat:</label><span>Validée</span></li>
            <li><label for="">N&deg;&nbsp;:</label><?php echo $calendrier->getNumeroArchivage($periode) ?></li>
            <li><a href="<?php echo url_for('drm_visualisation', array('identifiant' => $calendrier->getIdentifiant(), 'periode_version' => $calendrier->getPeriodeVersion($periode))) ?>">Voir la drm</a></li>
        </ul>
    <?php elseif ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_EN_COURS): ?>
        <ul class="mois_infos attente_campagne">
            <li><label for="">&Eacute;tat:</label><span>En attente</span></li>
            <li><label for="">N&deg;&nbsp;:</label><?php echo $calendrier->getNumeroArchivage($periode) ?></li>
            <li><a href="<?php echo url_for('drm_init', array('identifiant' => $calendrier->getIdentifiant(), 'periode_version' => $calendrier->getPeriodeVersion($periode))); ?>">Términer la saisie</a></li>
        </ul>
    <?php elseif ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_CLOTURE): ?>
        <ul class="mois_infos cloture_campagne">
            <li><label for="">&Eacute;tat:</label><span>Cloturée</span></li>
            <li><label for="">N&deg;&nbsp;:</label>000000-1</li>
            <li><button>voir la drm</button></li>
        </ul>
    <?php elseif ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_NOUVELLE): ?>
        <ul class="mois_infos nouv_campagne">
            <li><label for="">&Eacute;tat:</label><span>Nouvelle</span></li>
            <li><label for="">&nbsp;</label></li>
            <li><a href="<?php echo url_for('drm_nouvelle', array('identifiant' => $calendrier->getIdentifiant(), 'periode' => $periode)) ?>">Créer une drm</a></li>
        </ul>
    <?php endif; ?>
</li>