<div id="contenu">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contacts > </strong>Modification d'un établissement</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Modification d'un établissement</h2>
            <form action="<?php echo url_for('etablissement_modification', array('identifiant' => $etablissementModificationForm->getObject()->identifiant)); ?>" method="post">
                <?php 
                    include_partial('etablissement/modification', array('etablissementForm' => $etablissementModificationForm));
                    include_partial('compte/modification', array('compteForm' => $compteModificationForm, 'isSocieteCompte' => $isSocieteCompte));
                ?>
              <button id="btn_valider" type="submit">Valider</button>  
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