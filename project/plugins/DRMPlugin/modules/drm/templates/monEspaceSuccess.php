<div id="contenu" class="drm">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('drm') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('drm', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
            <fieldset id="historique_drm">
                <legend>Historique des DRMs de l'opérateur</legend>
                <nav>
                    <ul>
                        <li class="actif"><span>Vue calendaire</span></li>
                        <li><a href="<?php echo url_for('drm_etablissement_stocks', $etablissement) ?>">Vue stock</a></li>
                    </ul>
                </nav>
                <?php include_component('drm', 'calendrier', array('etablissement' => $etablissement, 'campagne' => $campagne)); ?>
            </fieldset>
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