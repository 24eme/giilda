<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <?php if (!$isTeledeclarationMode): ?>
        <p id="fil_ariane"><a href="<?php echo url_for('drm') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
    <?php else: if ($campagne == -1) : ?>
            <h2 class="titre_societe">Espace drm de <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant; ?>)</h2>
        <?php else: ?>
            <h2 class="titre_societe">Historique des drm de <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant; ?>)</h2>
        <?php endif; ?>
            <div style="text-align: center; font-weight: bold;"><?php echo getHelpMsgText('drm_calendrier_texte1'); ?></div>
<?php if (!$etablissement->hasLegalSignature()) { include_component('drm', 'legalSignature', array('etablissement' => $etablissement)); } ?>
    <?php endif; ?>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <?php if ($isTeledeclarationMode) : if ($campagne == -1) : ?>
                <?php include_component('drm', 'monEspaceDrm', array('etablissement' => $etablissement, 'campagne' => $campagne, 'isTeledeclarationMode' => $isTeledeclarationMode, 'accueil_drm' => true, 'calendrier' => $calendrier)); ?>
            <?php endif;
        else: ?>
            <?php include_component('drm', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
            <?php endif; ?>
        <fieldset id="historique_drm"> 
            <?php if (!$isTeledeclarationMode): ?>
                <legend>Historique des DRMs de l'opérateur</legend>
    <?php if ($etablissement->type_dr) : ?>
                    <div class="error_list">
                        Cet opérateur effectue des <?php echo $etablissement->type_dr; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
<?php if ($sf_user->hasFlash('drm_warning')) : ?>
<p style="text-align: center;background-color: orange;color: white;font-weight: bold;padding: 5px;"><?php echo $sf_user->getFlash('drm_warning'); ?></p>
<?php endif; ?>
<?php if (!$isTeledeclarationMode): ?>
                <nav>
                    <ul>
                        <li class="actif"><span>Vue calendaire</span></li>
                        <li><a href="<?php echo url_for('drm_etablissement_stocks', array('identifiant' => $etablissement->getIdentifiant(), 'campagne' => $campagne)); ?>">Vue stock</a></li>
                    </ul>
                </nav>
            <?php endif; ?>
<?php include_component('drm', 'calendrier', array('etablissement' => $etablissement, 'campagne' => $campagne, 'formCampagne' => $formCampagne, 'isTeledeclarationMode' => $isTeledeclarationMode, 'calendrier' => $calendrier)); ?>
        </fieldset>
    </section>
    <!-- fin #contenu_etape -->

</section>

<?php
include_partial('drm/colonne_droite', array('societe' => $etablissement->getSociete(),
    'etablissementPrincipal' => $etablissement,
    'isTeledeclarationMode' => $isTeledeclarationMode, 'isMonEspace' => true));
?>
<!-- fin #principal -->