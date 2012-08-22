<div id="contenu" class="drm">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('drm') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('drm', 'chooseEtablissement', array('identifiant' => $historique->getIdentifiant())); ?>
            <!--<fieldset id="chai_info">
                <legend>Détail du chai</legend>
                <div class="section_label_maj" id="chai_detail">
                    <div class="bloc_form">
                        <div class="col">
                            <div class="ligne_form">
                                <span>
                                    <label for="">Code du chai</label>68237001008-2
                                </span>
                            </div>
                            <div class="ligne_form ligne_form_alt">
                                <span>
                                    <label for="">N° CVI</label>68237001008
                                </span>
                            </div>
                            <div class="ligne_form">
                                <span>
                                    <label for="">Société</label>Ackerman &amp; co.
                                </span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="ligne_form">
                                <span>
                                    <label for="">Adresse*</label>19, rue Léopold Palustre
                                </span>
                            </div>
                            <div class="ligne_form ligne_form_alt">
                                <span>
                                    <label for="">CP* </label>49400
                                </span>
                            </div>
                            <div class="ligne_form">
                                <span>
                                    <label for="">Ville*</label>Saumur
                                </span>
                            </div>
                        </div>
                        <div class="ligne_form ligne_form_alt">
                            <span>
                                <label for="">Type de déclaration :</label>
                                <span class="type_declar declar_mensuelle">Mensuelle (DRM)</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="btn_container_modifier">
                    <a href="#" id="chai_modif_btn">MODIFIER</a>
                </div>
            </fieldset>-->
            <?php include_partial('drm/calendrier', array('calendrier' => $calendrier)); ?>
        </section>
        <!-- fin #contenu_etape -->
        
    </section>
    <!-- fin #principal -->
    
    <!-- #colonne -->
    <aside id="colonne">
        
        <div class="bloc_col" id="contrat_aide">
            <h2>Aide</h2>
            
            <div class="contenu">
                <ul>
                    <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                    <li class="assistance"><a href="#">Assistance</a></li>
                    <li class="contact"><a href="#">Contacter le support</a></li>
                </ul>
            </div>
        </div>
        
        <div class="bloc_col" id="infos_contact">
            <h2>Infos contact</h2>
            
            <div class="contenu">
                <ul>
                    <li id="infos_contact_vendeur">
                        <a href="#">Coordonnées vendeur</a>
                        <ul>
                            <li class="nom">Nom du vendeur</li>
                            <li class="tel">00 00 00 00 00</li>
                            <li class="fax">00 00 00 00 00</li>
                            <li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
                        </ul>
                    </li>
                    <li id="infos_contact_acheteur">
                        <a href="#">Coordonnées acheteur</a>
                        <ul>
                            <li class="nom">Nom du vendeur</li>
                            <li class="tel">00 00 00 00 00</li>
                            <li class="fax">00 00 00 00 00</li>
                            <li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
                        </ul>
                    </li>
                    <li id="infos_contact_mendataire">
                        <a href="#">Coordonnées mandataire</a>
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
    <!-- fin #colonne -->
</div>


<!--<section id="contenu">
    
    <h1>Déclaration Récapitulative Mensuelle <a href="" class="msg_aide" data-msg="help_popup_monespace" data-doc="notice.pdf" title="Message aide"></a></h1>
    
   <section id="etablissement">
   <?php include_component('drm', 'chooseEtablissement', array('identifiant' => $historique->getIdentifiant())); ?>
   </section>

    <section id="principal">
        <div id="recap_drm">
            <div id="drm_annee_courante" >
   <?php include_component('drm', 'historiqueList', array('historique' => $historique, 'limit' => 12)) ?>
            </div>

            <?php include_partial('drm/mouvements', array('mouvements' => $mouvements)); ?>
        </div>


    </section>
    <a href="<?php echo url_for('drm_historique', array('identifiant' => $historique->getIdentifiant())) ?>">Votre historique complet &raquo;</a>
    
    <?php if($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
        <br /><br />
        <h1>Espace Admin <a href="" class="msg_aide" data-msg="help_popup_monespace_admin" data-doc="notice.pdf" title="Message aide"></a></h1>
    	<p class="intro">Saisir une DRM d'un mois différent.</p>
        <div id="espace_admin" style="float: left; width: 670px;">
            <div class="contenu clearfix">
            	<?php include_partial('formCampagne', array('form' => $formCampagne)) ?>
            </div>
        </div>
    <?php endif; ?>



</section>-->
