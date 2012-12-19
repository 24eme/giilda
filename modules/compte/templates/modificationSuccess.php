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
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Accueil des sociétés</span></a>
        </div>
    </div>
        <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Accueil de la société</span></a>
        </div>
    </div>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('compte_modification', array('identifiant' => $compteModificationForm->getObject()->identifiant) ); ?>" class="btn_majeur btn_acces"><span>Modifier le compte</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>