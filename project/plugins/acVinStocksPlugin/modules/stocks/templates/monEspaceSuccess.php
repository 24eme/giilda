   <!-- #principal -->
    <section id="principal"  class="sv12">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('stocks', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>

            <form method="POST">
                <?php echo $formCampagne->renderGlobalErrors() ?>
                <?php echo $formCampagne->renderHiddenFields() ?>
                <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
            </form>

            <?php include_partial('stocks/recap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>

            <script type="text/javascript"> 
                var source_tags = {};
            </script>

<h2>Les mouvements</h2>

            <div id="recherche_sv12" style="margin-top: 30px;">
                <div class="autocompletion_tags" data-table="#table_contrats" data-source="source_tags">
                    <label>Saisissez un type de document (DRM ou SV12), un produit ou un type de mouvement :</label>
                    
                    <ul id="recherche_sv12_tags" class="tags">
                        <li></li>
                    </ul>
                    <!--
                    <button class="btn_majeur btn_rechercher" type="button">Rechercher</button>
                    -->
                </div>
            </div>

            <?php include_component('stocks', 'mouvements', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?> 
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
            <a href="<?php echo url_for('stocks'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
