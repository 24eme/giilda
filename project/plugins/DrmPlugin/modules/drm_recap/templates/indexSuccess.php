<section id="contenu" style="background: #fff; padding: 10px;">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 
                                                   'etape' => 'recapitulatif', 
                                                   'certification' => $config_lieu->getCertification()->getKey(), 
                                                   'pourcentage' => '30')); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <!-- #principal -->
    <section id="principal" style="width: 945px">
        <div id="application_dr">

            
            <?php include_component('drm_recap', 'onglets', array('config_lieu' => $config_lieu, 
                                                                  'drm_lieu' => $drm_lieu)) ?>
            <div id="contenu_onglet">
            
                <!-- <a href="" data-popup="#raccourci_clavier" class="btn_popup" data-popup-config="configDefaut">Raccourcis clavier</a> -->
            
                <?php include_partial('shortcutKeys') ?>

                <div style="text-align: center; padding: 20px 0;">
                    <select class="autocomplete">
                        <?php foreach($produits as $key => $value): ?>
                            <option value="<?php echo $key ?>"><?php echo $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php include_component('drm_recap', 'list', array('drm_lieu' => $drm_lieu, 
                                                                   'config_lieu' => $config_lieu,
                                                                   'produits' => $produits,
                                                                   'form' => $form,
                												   'light_detail' => $light_detail)); ?>
                <div id="btn_suiv_prec">
                    <?php if ($previous): ?>
                        <a href="<?php echo url_for('drm_recap_lieu', $previous) ?>" class="btn_prec">
                            <span>Produit précédent</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($next): ?>
                        <a href="<?php echo url_for('drm_recap_lieu', $next) ?>" class="btn_suiv">
                            <span>Produit suivant</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div id="btn_etape_dr">
            	<?php if ($prev_certif): ?>
                <a href="<?php echo url_for('drm_recap', $drm->declaration->certifications->get($prev_certif)) ?>" class="btn_majeur btn_etape_prec btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_mouvements_generaux', $drm) ?>" class="btn_majeur btn_etape_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
                <form action="<?php echo url_for('drm_recap', $drm->declaration->certifications->get($config_lieu->getCertification()->getKey())) ?>" method="post">
                    <button type="submit" class="btn_majeur btn_etape_suiv"><span>Suivant</span></button>
                </form>
            </div>
        </div>
    </section>
</section>


