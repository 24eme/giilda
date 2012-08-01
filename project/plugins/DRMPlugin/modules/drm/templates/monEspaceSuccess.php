<section id="contenu">
    
    <h1>Déclaration Récapitulative Mensuelle <a href="" class="msg_aide" data-msg="help_popup_monespace" data-doc="notice.pdf" title="Message aide"></a></h1>
    
   <section id="etablissement">
   <?php include_component('drm', 'chooseEtablissement', array('identifiant' => $historique->getEtablissementIdentifiant())); ?>
   </section>

    <section id="principal">
        <div id="recap_drm">
            <div id="drm_annee_courante" >
   <?php include_component('drm', 'historiqueList', array('historique' => $historique, 'limit' => 12)) ?>
            </div>
        </div>
    </section>
    <a href="<?php echo url_for('drm_historique', array('identifiant' => $historique->getEtablissementIdentifiant())) ?>">Votre historique complet &raquo;</a>
    
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

</section>
