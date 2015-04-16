<!-- #principal -->
<section id="principal" class="drm">

    <?php if (!$isTeledeclarationMode): ?>
        <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php endif; ?>
    <h2>Déclaration Récapitulative Mensuelle</h2>

    <ul id="recap_infos_header">
        <li>
            <?php if ($isTeledeclarationMode): ?>
                Télédéclaration de 
            <?php else: ?>  
                <label>Nom de l'opérateur : CCC</label> 
            <?php endif; ?>
            <?php echo $drm->getEtablissement()->nom ?> </li>
        <li><label>Période : </label><?php echo $drm->periode ?></li>
    </ul>

    <?php include_partial('drm_edition/etapes',array('isTeledeclarationMode' => $isTeledeclarationMode)); ?>

    <?php include_partial('drm/controlMessage'); ?>

    <div id="application_dr">

        <?php include_component('drm_edition', 'produitForm', array('drm' => $drm, 'config' => $config)) ?>

        <div id="contenu_onglet">
            <?php
            include_partial('drm_edition/choixProduitsList', array('drm_noeud' => $drm->declaration,
                'config' => $config,
                'detail' => $detail,
                'produits' => $details,
                'form' => $form,
                'detail' => $detail));
            ?>

        </div>



    </div>
    <div id="btn_etape_dr">
        <a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()); ?>" class="btn_brouillon btn_majeur">Enregistrer en brouillon</a>
        <a href="<?php echo url_for('drm_validation', $drm); ?>" class="btn_etape_suiv" id="facture"><span>Suivant</span></a> 
    </div>

</section>
