<div id="contenu">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contacts > </strong> Création d'une société</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Création d'une société</h2>
            <form action="<?php echo url_for('societe_modification', array('identifiant' => $societeForm->getObject()->identifiant)); ?>" method="post">
                <button id="btn_rechercher" type="submit">Valider</button>
                <?php include_partial('societeModification', array('societeForm' => $societeForm)); ?>
                <?php include_partial('contactSocieteModification', array('contactSocieteForm' => $contactSocieteForm)); ?>
                <?php if($societe->hasChais()){
                        include_partial('etablissementSocieteModification', array('etablissementSocieteForm' => $etablissementSocieteForm)); 
                        }
                ?>
            </form>
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