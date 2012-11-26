<div id="contenu" class="drm">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('drm') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('drm', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
            
            <fieldset id="historique_drm">
                <legend>Cette DRM est en cours de saisie.</legend>
            </fieldset>            
            Cette DRM est déjà en cours de saisie.
            <a href="<?php echo url_for('drm_etablissement', $etablissement) ?>" class="btn_majeur btn_jaune">Revenir au calendrier</a>
          
        </section>
        <!-- fin #contenu_etape -->
        
    </section>
    <!-- fin #principal -->
    
    <!-- #colonne -->
    <aside id="colonne">
        
        <div class="bloc_col" id="contrat_aide">
            <h2>Aide</h2>
            
            <div class="contenu">
                
            </div>
        </div>
        
        <div class="bloc_col" id="infos_contact">
            <h2>Infos contact</h2>
            
            <div class="contenu">
                
            </div>
        </div>
    
    </aside>
    <!-- fin #colonne -->
</div>