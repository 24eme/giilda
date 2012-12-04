<div id="contenu">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contacts > <?php echo $societe->raison_sociale; ?> > </strong>Nouvel établissement</p>

        <!-- #contenu_etape -->
        <section id="contacts">
            <div id="creation_societe">
            <h2><?php echo $societe->raison_sociale; ?></h2>
            <form action="<?php echo url_for('etablissement_new', array('identifiant' => $etablissementModificationForm->getObject()->identifiant)); ?>" method="post">
                <?php 
                    include_partial('etablissement/modification', array('etablissementForm' => $etablissementModificationForm));
                    include_partial('compte/modification', array('compteForm' => $compteModificationForm));
                ?>
              <button id="btn_valider" type="submit">Valider</button>  
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