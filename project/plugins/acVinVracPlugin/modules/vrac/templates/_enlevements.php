<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>
<section id="conditions_recapitulatif">
            <div class="bloc_form bloc_form_condensed">
    <div id="conditions_recapitulatif_typeContrat" class="ligne_form ligne_form_alt ">
        <label></label>
        <span>Liste des enlèvements depuis les DRM&nbsp;</span>
    </div>
    <?php if (count($enlevements)):
        $cpt = 0;
        ?>
        <?php foreach ($enlevements as $mvt_id => $enlevement): ?>
            <div id="conditions_recapitulatif_isvariable" class="ligne_form <?php if($cpt): ?>ligne_form_alt<?php endif ?>">
                <label>
                    <?php if($enlevement->type == 'DRM'): ?>
                    <a href="<?php echo url_for('drm_redirect_to_visualisation', array('identifiant_drm' => $enlevement->doc_id)); ?>"> <?php echo "DRM ".preg_replace("/^DRM-[0-9]+-[0-9]+-?(M[0-9]+)?$/","$1 ",$enlevement->doc_id) . getFrPeriodeElision($enlevement->periode); ?></a>
                    <?php endif; ?>
                    <?php if($enlevement->type == 'SV12'): ?>
                    <a href="<?php echo url_for('sv12_redirect_to_visualisation', array('identifiant_sv12' => $enlevement->doc_id)); ?>"> <?php echo "SV12 ".preg_replace("/^SV12-[0-9]+-([0-9]+-[0-9]+)-?(M[0-9]+)?$/","$1 ($2)",$enlevement->doc_id); ?></a>
                    <?php endif; ?>
                </label>
                <span><?php echoFloat($enlevement->volume, true) ; echo " hl"; ?></span>
            </div>
        <?php
        $cpt = !$cpt;
        endforeach; ?>
    <?php else: ?>
        <div id="conditions_recapitulatif_isvariable" class="ligne_form ">
            <label></label>
            <span>Pas d'enlèvements enregistrés pour le moment sur ce contrat</span>
        </div>
    <?php endif; ?>
    </div>
</section>
