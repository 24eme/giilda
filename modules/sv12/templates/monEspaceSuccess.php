 
    <!-- #principal -->
    <section id="principal" class="sv12">
        <p id="fil_ariane"><a href="<?php echo url_for('sv12') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('sv12', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
			
            <?php include_partial('negociant_information',array('etablissement' => $etablissement)); ?>
            <a class="btn_majeur btn_nouveau" href="<?php echo url_for('sv12_nouvelle', array('identifiant' => $etablissement->identifiant, 'periode' => $periode)) ?>">Créer une SV12</a>

            <?php include_partial('sv12/list', array('list' => $list)) ?>
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
            <a href="<?php echo url_for('sv12'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>