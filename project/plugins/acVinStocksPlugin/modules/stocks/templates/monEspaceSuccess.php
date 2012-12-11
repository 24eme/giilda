<div id="contenu" class="sv12">    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('stocks', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
            <?php include_partial('stocks/recap', array('campagne' => '2012-2013', 'etablissement' => $etablissement)); ?>

            <script type="text/javascript"> 
                var source_tags = {};
            </script>

            <div id="recherche_sv12" style="margin-top: 30px;">
                <div class="autocompletion_tags" data-table="#table_contrats" data-source="source_tags">
                    <label>Saisissez un type de document (DRM ou SV12), un produit ou un type de mouvement :</label>
                    
                    <ul id="recherche_sv12_tags" class="tags">
                        <li></li>
                    </ul>
                    <!--
                    <button class="btn_majeur btn_rechercher" type="button">Rechercher</button>
                    -->
                </div>
                
                <div class="volumes_vides">
                    <label for="champ_volumes_vides"><input type="checkbox" id="champ_volumes_vides" /> Afficher uniquement les volumes non-saisis</label>
                </div>
            </div>
            <?php include_component('stocks', 'mouvements', array('campagne' => '2012-2013', 'etablissement' => $etablissement)); ?> 
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
    </aside>
    <!-- fin #colonne -->
</div>