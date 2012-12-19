
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
    