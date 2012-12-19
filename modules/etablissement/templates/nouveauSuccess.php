<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><strong>Page d'accueil > Contacts > <?php echo $societe->raison_sociale; ?> > </strong>Modification établissement</p>

    <!-- #contenu_etape -->
    <section id="contacts">
        <div id="creation_societe">
            <h2><?php echo $societe->raison_sociale; ?></h2>
            <form action="<?php echo url_for('etablissement_new', array('identifiant' => $etablissementModificationForm->getObject()->identifiant)); ?>" method="post">
                <?php
                include_partial('etablissement/modification', array('etablissementForm' => $etablissementModificationForm));
                ?> 
                <div id="detail_etablissement" class="form_section">
                    <h2>Coordonnées de l'etablissement</h2>
                    <div class="form_contenu">
                        <div class="form_ligne">
                            <?php echo $compteModificationForm['adresse_societe']->renderError(); ?>
                            <?php echo $compteModificationForm['adresse_societe']->renderLabel(); ?>
                            <?php echo $compteModificationForm['adresse_societe']->render(); ?>
                        </div>
                        <?php
                        include_partial('compte/modification', array('compteForm' => $compteModificationForm));
                        ?>
                    </div>
                </div>
                <button class="btn_majeur btn_valider" type="submit">Valider</button>  
            </form>
        </div>
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
</div>
<?php
end_slot();
?> 