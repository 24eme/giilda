<?php
use_helper('Vrac');
$cpt = 0;
?>
<div class="bloc_form bloc_form_condensed">
    <div id="soussigne_recapitulatif_vendeur" class="<?php echoClassLignesVisu($cpt); ?> <?php echoPictoSignatureFromObject($societe, $vrac, 'Vendeur', $template_validation); ?>">
        <label>Vendeur :</label>
        <span>
            <?php if (!$isTeledeclarationMode): ?>
                <a href="<?php echo url_for('vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $vrac->vendeur_identifiant)) ?>">
                <?php endif; ?>
                <?php echo $vrac->getVendeurObject()->getNom(); ?>

                <?php if (!$isTeledeclarationMode): ?>
                </a>
            <?php endif; ?>
            <span><?php echo ($vrac->valide->date_signature_vendeur) ? '' : ' (en attente de signature)'; ?></span>
        </span>
    </div>
    <div id="soussigne_recapitulatif_acheteur" class="<?php echoClassLignesVisu($cpt); ?> <?php echoPictoSignatureFromObject($societe, $vrac, 'Acheteur', $template_validation); ?>">
        <label>Acheteur :</label>
        <span> <?php if (!$isTeledeclarationMode): ?>
                <a href="<?php echo url_for('vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $vrac->acheteur_identifiant)) ?>">
                <?php endif; ?>
                <?php echo $vrac->getAcheteurObject()->getNom(); ?>
                <?php if (!$isTeledeclarationMode): ?>
                </a>
            <?php endif; ?>
            <span><?php echo ($vrac->valide->date_signature_acheteur) ? '' : ' (en attente de signature)'; ?></span>
        </span>
    </div>

    <?php if (!$isTeledeclarationMode): ?>
        <div id="soussigne_recapitulatif_mandataire" class="<?php echoClassLignesVisu($cpt); ?>">
            <label>Entreprises liées :</label>
            <span><?php echo ($vrac->interne) ? 'Oui </small>(au sens de l’art. III-1 de l’accord interprofessionnel)</small>' : 'Non'; ?></span>
        </div>
    <?php endif; ?>

    <?php if ($vrac->mandataire_identifiant != null && $vrac->mandataire_exist): ?>
        <div id="soussigne_recapitulatif_mandataire" class="<?php echoClassLignesVisu($cpt); ?> <?php echoPictoSignatureFromObject($societe, $vrac, 'Courtier', $template_validation); ?>" >
            <label>Courtier&nbsp;:</label>
            <?php if (!$isTeledeclarationMode): ?>
                <a href="<?php echo url_for('vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $vrac->mandataire_identifiant)) ?>">
                <?php endif; ?>

                <span><?php echo $vrac->getMandataireObject()->getNom(); ?></span>
                <?php if (!$isTeledeclarationMode): ?>
                </a>
            <?php endif; ?>
        </div>
    <?php elseif (!$isTeledeclarationMode): ?>
        <div id="soussigne_recapitulatif_mandataire" class="<?php echoClassLignesVisu($cpt); ?>" >
            Ce contrat ne possède pas de courtier
        </div>
    <?php endif; ?>
</div>
