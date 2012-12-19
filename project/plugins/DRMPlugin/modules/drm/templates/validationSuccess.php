<!-- #principal -->
    <section id="principal" class="drm">

        <?php include_partial('drm/header', array('drm' => $drm)); ?>

        <h2>Déclaration Récapitulative Mensuelle</h2>

		<ul id="recap_infos_header">
			<li><span>Nom de l'opérateur :</span> <?php echo $drm->getEtablissement()->nom ?> </li>
			<li><span>Période :</span> <?php echo ucfirst($drm->getHumanPeriode()); ?></li>
		</ul>

        <?php include_partial('drm/etapes'); ?>

        <form action="" method="post">
            <?php include_partial('drm/recap', array('drm' => $drm)) ?>
            <?php include_partial('drm/mouvements', array('mouvements' => $mouvements)) ?>

            <br />
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_edition', $drm) ?>" class="btn_etape_prec" id="facture"><span>Précédent</span></a>
                <a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()); ?>" class="btn_brouillon btn_majeur">Enregistrer en brouillon</a>
                <button type="submit" class="btn_etape_suiv" id="facture"><span>Valider</span></button> 
            </div>
        </form>

    </section>
