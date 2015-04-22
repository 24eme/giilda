<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>

<!-- #principal -->
<section id="principal" class="drm">

    <?php if (!$isTeledeclarationMode): ?>
        <?php include_partial('drm/header', array('drm' => $drm)); ?>
        <h2>Déclaration Récapitulative Mensuelle</h2>
    <?php else: ?>
        <h2><?php echo getDrmTitle($drm); ?></h2>
    <?php endif; ?>
    <?php if (!$isTeledeclarationMode): ?>  
        <ul id="recap_infos_header">
            <li>
                <label>Nom de l'opérateur : </label> 
                <?php echo $drm->getEtablissement()->nom ?>
            </li>
            <li><label>Période : </label><?php echo $drm->periode ?></li>
        </ul>
    <?php endif; ?>

    <?php include_partial('drm_edition/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_CHOIX_PRODUITS)); ?>

    <?php include_partial('drm/controlMessage'); ?>


    <div id="application_drm">
        <?php if ($isTeledeclarationMode): ?>  
            <p class="choix_produit_explication">Afin de préparer le détail de la DRM, vous pouvez préciser ici vos stocks épuisés ou l'absence de mouvements pour tout ou partie des produits.</p>
            <div class="choix_produit_skip_etape">Je n'ai aucun mouvement à déclarer, il s'agit d'une DRM à néant : <a class="btn_majeur" href="#" >Passer cette étape</a></div>

        <?php endif; ?>

        <form action="<?php echo url_for('drm_choix_produit', $form->getObject()) ?>" method="post">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <div id="contenu_onglet">
                <?php
                include_partial('drm_edition/choixProduitsList', array(
                    'certificationsProduits' => $certificationsProduits,
                    'form' => $form,'drm' => $drm));
                ?>
            </div>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_societe',array('identifiant' => $drm->getEtablissement()->identifiant)); ?>" class="btn_etape_prec"><span>Précédent</span></a> 
                <button type="submit" class="btn_etape_suiv" id="choixProduitsSubmit"><span>Etape Suivante</span></button> 
            </div>
        </form>
    </div>
</section>

<?php
if ($isTeledeclarationMode):
    include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
endif;
?>
