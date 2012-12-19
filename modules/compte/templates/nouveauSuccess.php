<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">Contacts</a> &gt; <a href="#"><?php echo $societe->raison_sociale; ?></a> &gt; <strong>Nouveau contact</strong></p>

    <!-- #contacts -->
    <section id="contacts">
        <div id="creation_societe">
            <h1>Création d'une nouvelle société</h1>
            <form action="<?php echo url_for('compte_new', array('identifiant' => $compte->identifiant)); ?>" method="post">
                <button id="btn_valider" type="submit">Valider</button>

                <?php
                include_partial('modificationDetail', array('compteForm' => $compteForm));
                include_partial('modification', array('compteForm' => $compteForm));
                ?>
            </form>
    </section>
</section>
