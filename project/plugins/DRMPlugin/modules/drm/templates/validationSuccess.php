<!-- #principal -->
    <section id="principal" class="drm">

        <?php include_partial('drm/header', array('drm' => $drm)); ?>

        <h2>Déclaration Récapitulative Mensuelle</h2>

		<ul id="recap_infos_header">
			<li><span>Nom de l'opérateur :</span> <?php echo $drm->getEtablissement()->nom ?> </li>
			<li><span>Période :</span> <?php echo ucfirst($drm->getHumanPeriode()); ?></li>
		</ul>

        <?php include_partial('drm/etapes', array('drm' => $drm)); ?>

        <form action="" method="post">
            <?php include_partial('document_validation/validation', array('validation' => $validation)); ?>

            <?php include_partial('drm/recap', array('drm' => $drm)) ?>
            <?php include_partial('drm/mouvements', array('mouvements' => $mouvements, 'no_link' => $no_link)) ?>

            <br />

            <?php echo $form ; ?>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_edition', $drm) ?>" class="btn_etape_prec" id="facture"><span>Précédent</span></a>
                <button type="submit" name="brouillon" value="brouillon" class="btn_brouillon btn_majeur"><span>Enregistrer en brouillon</span></button>
                <button type="submit" <?php if(!$validation->isValide()): ?>disabled="disabled"<?php endif; ?> class="btn_etape_suiv" id="facture"><span>Valider</span></button> 
            </div>
        </form>

    </section>
