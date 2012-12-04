<div id="contenu">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contact > </strong>Modification d'un Contact</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Modification d'un établissement</h2>
            <form action="<?php echo url_for('compte_modification', array('identifiant' => $compteModificationForm->getObject()->identifiant)); ?>" method="post">
                <?php 
                    include_partial('compte/modification', array('compteForm' => $compteModificationForm));
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