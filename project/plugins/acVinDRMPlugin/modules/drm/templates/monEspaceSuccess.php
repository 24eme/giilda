<!-- #principal -->
<section id="principal" class="drm">
    <?php if (!$isTeledeclarationMode): ?>
        <p id="fil_ariane"><a href="<?php echo url_for('drm') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
    <?php endif; ?>
    <?php if ($isTeledeclarationMode): ?>
        <h2>Télédéclaration de l'établissement <?php echo $etablissement->nom ?></h2>
    <?php endif; ?>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <?php if (!$isTeledeclarationMode): ?>
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
            <nav>
                <ul>
                    <li class="actif"><span>Vue calendaire</span></li>
                        <?php if (!$isTeledeclarationMode): ?>
                        <li><a href="<?php echo url_for('drm_etablissement_stocks', array('identifiant' => $etablissement->getIdentifiant(), 'campagne' => $campagne)); ?>">Vue stock</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php include_component('drm', 'calendrier', array('etablissement' => $etablissement, 'campagne' => $campagne, 'formCampagne' => $formCampagne, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
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