<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="<?php echo url_for('revendication') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
    <!-- #contenu_etape -->
    <section id="contenu_etape">

        <?php include_component('revendication', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>

        <form method="post">
            <?php echo $formCampagne->renderGlobalErrors() ?>
            <?php echo $formCampagne->renderHiddenFields() ?>
            <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
        </form>

        <h2>Volumes revendiqués</h2>
        
        <?php if($revendication): ?>
        <a class="btn_majeur btn_modifier" href="<?php echo url_for('revendication_edition', array('odg'=> $odg, 'campagne' => $campagne)); ?>">Modifier</a>
        <?php endif; ?>
        
        <?php include_partial('revendication/editionList', array('revendications' => $revendications, 'retour' => 'etablissement')); ?>
    </section>
</section>
