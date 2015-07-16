<section id="principal" class="drm">
    <div id="application_drm">
        <?php if (!$isTeledeclarationMode): ?>
            <?php include_partial('drm/header', array('drm' => $drm)); ?>
            <ul id="recap_infos_header">
                <li>
                    <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?><label style="float: right;">Période : <?php echo $drm->periode ?></label>
                </li>
            </ul>
        <?php endif; ?>

        <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_VALIDATION)); ?>

                <form action="<?php echo url_for('drm_validation_update_etablissement', $drm); ?>" method="POST" class="drm_validation_etablissement_form">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <div class="title"><?php echo $drm->declarant->nom; ?></div>
            <div class="panel">
                <ul>
                    <li>
                        <span class="label"><?php echo $form['cvi']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $form['cvi']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $form['no_accises']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $form['no_accises']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $form['adresse']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $form['adresse']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $form['code_postal']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $form['code_postal']->render(); ?></span>
                    </li>
                    <li>
                        <span class="label"><?php echo $form['commune']->renderLabel(); ?></span>
                        <span class="infos"><?php echo $form['commune']->render(); ?></span>
                    </li>  
                    <?php if ($drm->declarant->exist('adresse_compta')): ?>
                        <li>
                            <span class="label"><?php echo $form['adresse_compta']->renderLabel(); ?></span>
                            <span class="infos"><?php echo $form['adresse_compta']->render(); ?></span>
                        </li>  
                    <?php endif; ?>
                    <?php if ($drm->declarant->exist('caution')): ?>
                        <li>
                            <span class="label"><?php echo $form['caution']->renderLabel(); ?></span>
                            <span class="infos"><?php echo $form['caution']->render(); ?></span>
                        </li>  
                    <?php endif; ?>
                    <?php if ($drm->declarant->exist('raison_sociale_cautionneur')): ?>
                        <li>
                            <span class="label"><?php echo $form['raison_sociale_cautionneur']->renderLabel(); ?></span>
                            <span class="infos"><?php echo $form['raison_sociale_cautionneur']->render(); ?></span>
                        </li>  
                    <?php endif; ?>
                </ul>
                <div id="btn_etape_dr">
                    <a href="<?php echo url_for('drm_validation', $drm) ?>" class="btn_majeur btn_annuler" style="float: left;" id="drm_validation_etablissement_annuler_btn"><span>annuler</span></a>
                    <button type="submit" class="btn_majeur btn_valider" id="drm_validation_etablissement_valider_btn" style="float: right;"><span>Valider</span></button> 
                </div>
            </div>
         </form>

    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>
