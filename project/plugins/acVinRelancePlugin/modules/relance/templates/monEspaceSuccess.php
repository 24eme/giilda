<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="<?php echo url_for('relance') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->raison_sociale; ?></strong></p>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <?php include_component('relance', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
    </section>
    <br />

    <?php
    include_partial('historiqueRelances', array('etablissement' => $etablissement, 'relances' => $relances));
    ?>
    <hr />
    <h2>Génération de relance</h2>
    <br />
    <?php include_partial('relance/alertesRelance', array('alertes' => $alertes, 'etablissement' => $etablissement)) ?>
    <form action="<?php echo url_for("relance_etablissement_creation",$etablissement); ?>" method="post">
        <button type="submit">Créer une relancer</button> 
    </form>
</section>
    <!-- fin #principal -->
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('relance'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
