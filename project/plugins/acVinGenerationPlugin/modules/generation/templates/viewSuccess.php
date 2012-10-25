<div id="contenu" class="generation_facturation">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong> > Visualisation d'un générations d'impression</p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Visualisation d'une génération d'impression</h2>
            <?php include_partial('generations', array('generation' => $generation, 'type' => 'facture')); ?>
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
