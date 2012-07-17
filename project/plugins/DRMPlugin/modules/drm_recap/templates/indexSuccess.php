<?php //include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu" style="background: #fff; padding: 0 10px;">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php /*include_component('drm', 'etapes', array('drm' => $drm, 
                                                   'etape' => 'recapitulatif', 
                                                   'pourcentage' => '30'));*/ ?>
    <?php include_partial('drm/controlMessage'); ?>

    <a href="" data-popup="#raccourci_clavier" class="btn_popup" data-popup-config="configDefaut">Raccourcis clavier</a>

    <!-- #principal -->
    <section id="principal" style="width: auto;">
        <div id="application_dr">

        	<?php include_partial('shortcutKeys') ?>

        	<?php include_component('drm_recap', 'produitForm', array('drm' => $drm,
        															  'config' => $config)) ?>
            
            <?php /*include_component('drm_recap', 'onglets', array('config_lieu' => $config_lieu, 
                                                                  'drm_lieu' => $drm_lieu))*/ ?>
            <div id="contenu_onglet">

                <?php include_partial('drm_recap/list', array('drm_noeud' => $drm->declaration, 
                                                                   'config' => $config,
                                                                   'produits' => $produits,
                                                                   'form' => $form,
                												   'detail' => $detail)); ?>
                <div id="btn_suiv_prec">
                    <?php /*if ($previous): ?>
                        <a href="<?php echo url_for('drm_recap_lieu', $previous) ?>" class="btn_prec">
                            <span>Produit précédent</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($next): ?>
                        <a href="<?php echo url_for('drm_recap_lieu', $next) ?>" class="btn_suiv">
                            <span>Produit suivant</span>
                        </a>
                    <?php endif;*/ ?>
                </div>
            </div>
            <div id="btn_etape_dr">
            	<?php /*if ($previous_certif): ?>
                <a href="<?php echo url_for('drm_recap', $drm->declaration->certifications->get($previous_certif)) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_mouvements_generaux', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>

                <?php if ($next_certif): ?>
                <a href="<?php echo url_for('drm_recap', $drm->declaration->certifications->get($next_certif)) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_vrac', $drm) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
            	<?php endif;*/ ?>
            	
            </div>
        </div>
    </section>
</section>


