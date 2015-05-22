<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><strong>Page d'accueil</strong></p>

    <!-- #contenu_etape -->
    <section id="contenu_etape" class="alerte">
        <?php include_partial('information_alerte', array('alerte' => $alerte)); ?>
        <?php if ($alerte->isModifiable()) : ?>
            <?php include_partial('modification_alerte', array('alerte' => $alerte, 'form' => $form)); ?>    
        <?php endif; ?>
        <?php include_partial('history_alerte', array('alerte' => $alerte)); ?>
    </section>
    <!-- fin #contenu_etape -->
</section>
<!-- fin #principal -->

<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('alerte'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('alerte_etablissement', array('identifiant' => $alerte->identifiant)); ?>" class="btn_majeur btn_acces"><span>Alerte de l'opérateur</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
 