<section id="contenu" style="background: #fff; padding: 0 10px;">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php /*include_partial('etapes', array('drm' => $drm, 
                                                   'etape' => 'mouvements', 
                                                   'pourcentage' => '10'));*/ ?>
    <?php include_partial('drm/controlMessage'); ?>

    <form action="" method="post">    

        <!-- #principal -->
        <section id="principal" style="width: auto;">
            <?php include_partial('drm/recap', array('drm' => $drm)) ?>
            <?php include_partial('drm/mouvements', array('mouvements' => $drm->declaration->getMouvements())) ?>
        </section>

        <input type="submit" value="valider" />
    </form>

</section>