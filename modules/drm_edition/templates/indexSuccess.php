<div id="contenu" class="drm">

    <!-- #principal -->
    <section id="principal" style="width: auto;">

        <?php include_partial('drm/header', array('drm' => $drm)); ?>

        <h2>Déclaration Récapitulative Mensuelle</h2>

        <div id="recap_infos_header">
            <li><label>Nom de l'opérateur : </label> <?php echo $drm->getEtablissement()->nom ?> </li>
            <li><label>Période : </label><?php echo $drm->periode ?></li>
        </div>

        <?php include_partial('drm_edition/etapes'); ?>

        <?php include_partial('drm/controlMessage'); ?>

        <?php include_partial('shortcutKeys') ?>

        <div id="application_dr">

            <?php include_component('drm_edition', 'produitForm', array('drm' => $drm, 'config' => $config)) ?>

            <div id="contenu_onglet">
                <?php
                include_partial('drm_edition/list', array('drm_noeud' => $drm->declaration,
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


