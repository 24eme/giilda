<!-- #principal -->
    <section id="principal" class="drm">
        <p id="fil_ariane"><a href="<?php echo url_for('drm') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('drm', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
            <fieldset id="historique_drm">
                <legend>Historique des DRMs de l'opérateur</legend>
                <?php if($etablissement->type_dr) : ?>
                    <div class="error_list">
                    Cet opérateur effectue des <?php echo $etablissement->type_dr; ?>
                    </div>
                <?php endif; ?>
                <nav>
                    <ul>
                        <li class="actif"><span>Vue calendaire</span></li>
                        <li><a href="<?php echo url_for('drm_etablissement_stocks', array('identifiant' => $etablissement->getIdentifiant(), 'campagne'=>$campagne)); ?>">Vue stock</a></li>
                    </ul>
                </nav>
							  <?php include_component('drm', 'calendrier', array('etablissement' => $etablissement, 'campagne' => $campagne, 'formCampagne' => $formCampagne)); ?>
            </fieldset>
        </section>
        <!-- fin #contenu_etape -->
        
     </section>
<!-- fin #principal -->