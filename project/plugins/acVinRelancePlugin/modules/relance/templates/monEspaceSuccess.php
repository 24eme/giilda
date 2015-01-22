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

    <div id="toutes_alertes">
        <h2>Les alertes à relancer de <?php echo $etablissement->raison_sociale; ?></h2>

        <?php include_partial('relance/alertesRelance', array('alertesARelancer' => $alertesARelancer, 'etablissement' => $etablissement)) ?>
    </div>

    <form action="<?php echo url_for("relance_etablissement_creation", $etablissement); ?>" method="post">
        <div class="generation_facture_valid">
            <span>Cliquer sur "Générer" pour créer les relances</span>
            <button id="relance_generation_btn" class="btn_majeur btn_refraichir" href="#">Générer</button>
        </div>
    </form>

    <div id="toutes_alertes">
        <h2>Les alertes à relancer avec Accusé de reception de <?php echo $etablissement->raison_sociale; ?></h2>
        <?php include_partial('relance/alertesRelance', array('alertesARelancer' => $alertesARelancerAR, 'etablissement' => $etablissement)) ?>
    </div>


    <form action="<?php echo url_for("relance_etablissement_creation_ar", $etablissement); ?>" method="post">
        <div class="generation_facture_valid">
            <span>Cliquer sur "Générer" pour créer les relances AR</span>
            <button id="relance_generation_btn" class="btn_majeur btn_refraichir" href="#">Générer les Relance AR</button>
        </div>
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
