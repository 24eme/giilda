<div id="contenu">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong> > Création d'une société</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Gestion des contacts</h2>
            <?php include_component('societe', 'chooseSociete'); ?>
            <h2>Détail de la société</h2>
            <div>
                <?php include_partial('visualisation', array('societe' => $societe)); ?>
            </div>
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
