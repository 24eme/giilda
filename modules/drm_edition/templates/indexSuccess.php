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

        	<?php include_component('drm_edition', 'produitForm', array('drm' => $drm,
        															  'config' => $config)) ?>
            
            <div id="contenu_onglet">

                <?php include_partial('drm_edition/list', array('drm_noeud' => $drm->declaration, 
                                                                   'config' => $config,
                                                                   'produits' => $produits,
                                                                   'form' => $form,
                												   'detail' => $detail)); ?>

            </div>
            <div id="btn_etape_dr">
            	
            </div>
        </div>
    </section>
</section>


