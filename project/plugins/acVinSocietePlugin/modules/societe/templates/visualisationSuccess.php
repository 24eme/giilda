<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contacts > </strong> <?php echo $societe->raison_sociale; ?></p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2><?php echo $societe->raison_sociale; ?></h2>
            <div>
                <a href="<?php echo url_for('societe_addContact', array('identifiant' => $societe->identifiant)); ?>" class="btnNouveau">Nouveau Contact
                </a>
                
                <a href="#" class="btnNouveau">Nouvel Etablissement
                </a>
            </div>
            <?php
            include_partial('visualisationPanel', array('societe' => $societe));?>
                <h2>Coordonnées de la société </h2>
                <?php
            foreach ($etablissements as $ordre => $etablissement) {
                include_partial('etablissement/visualisation', array('etablissement' => $etablissement, 'ordre' => $ordre));
            }
            ?>

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