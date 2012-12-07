<div id="contenu">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contacts</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Gestion des contacts</h2>
            <div id="recherche_societe" class="section_label_maj">
                <h2>Recherche d'un société, d'un etablissement ou d'un interlocuteur</h2>
                <form action="<?php echo url_for('societe'); ?>" method="post">
                    <div class="section_label_maj" id="recherche_societe">
                        <?php echo $contactsForm['identifiant']->renderError(); ?>
                        <?php echo $contactsForm['identifiant']->render(); ?>
                    </div>
                    <button id="btn_rechercher" type="submit">Chercher</button>
                </form>
                    
            </div>

        </section>
    </section>
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
</div>