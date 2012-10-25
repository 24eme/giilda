<div id="contenu" class="drm">

    <!-- #principal -->
    <section id="principal" style="width: auto;">

        <?php include_partial('drm/header', array('drm' => $drm)); ?>

        <h2>Déclaration Récapitulative Mensuelle</h2>

		<ul id="recap_infos_header">
			<li><span>Nom de l'opérateur :</span> <?php echo $drm->getEtablissement()->nom ?> </li>
			<li><span>Période :</span> <?php echo $drm->periode ?></li>
            <li><span>Numéro d'archive :</span> <?php echo $drm->numero_archive ?></li>
		</ul>
		
        <?php if ($drm_suivante && $drm_suivante->isRectificative() && !$drm_suivante->isValidee()): ?>
            <div class="vigilance_list">
                <ul>
                    <li><?php echo MessagesClient::getInstance()->getMessage('msg_rectificatif_suivante') ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($drm->isModifiable()): ?>
            <a class="btn_majeur btn_modifier" href="<?php echo url_for('drm_modificative', $drm) ?>">Modifier la DRM</a>
        <?php endif; ?>

		<?php include_partial('drm/recap', array('drm' => $drm)) ?>
		<?php include_partial('drm/mouvements', array('mouvements' => $mouvements)) ?>

		<br />
		<div id="btn_etape_dr">
			<a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()) ?>" class="btn_etape_prec" id="facture"><span>Retour à mon espace</span></a> 
		</div>

    </section>
    <aside id="colonne">

        <div id="contrat_progression" class="bloc_col">
            <h2>Campagne viticole : 2011-2012</h2>

            <div class="contenu">
                <p><strong>10%</strong> de la DRM a été saisi</p>

                <div id="barre_progression">
                    <span style="width: 10%;"></span>
                </div>
            </div>
        </div>

        <div id="contrat_aide" class="bloc_col">
            <h2>Aide</h2>

            <div class="contenu">
                <ul>
                    <li class="raccourcis"><a href="" data-popup="#raccourci_clavier" class="btn_popup" data-popup-config="configDefaut">Raccourcis clavier</a></li>
                    <li class="assistance"><a href="#">Assistance</a></li>
                </ul>
            </div>
        </div>

        <div id="infos_contact" class="bloc_col">
            <h2>Infos contact</h2>

            <div class="contenu">
                <ul>
                    <li id="infos_contact_vendeur">
                        <a href="#">Coordonnées opérateur</a>
                        <ul>
                            <li class="nom">Nom du vendeur</li>
                            <li class="tel">00 00 00 00 00</li>
                            <li class="fax">00 00 00 00 00</li>
                            <li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
                        </ul>
                    </li>
                    <li id="infos_contact_acheteur">
                        <a href="#">Coordonnées recette locale</a>
                        <ul>
                            <li class="nom">Nom du vendeur</li>
                            <li class="tel">00 00 00 00 00</li>
                            <li class="fax">00 00 00 00 00</li>
                            <li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

    </aside>
</div>