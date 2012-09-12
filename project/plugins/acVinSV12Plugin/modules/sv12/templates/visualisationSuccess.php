<div id="contenu" class="sv12">    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('sv12') ?>">Page d'accueil</a> &gt; <strong><?php echo $sv12->declarant->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Déclaration SV12</h2>
            <?php include_partial('negociant_infos',array('sv12' => $sv12)); ?>
       
            <?php if ($sv12->isModifiable()): ?>
            <a class="btn_majeur btn_modifier" href="<?php echo url_for('sv12_modificative', $sv12) ?>">Modifier la DRM</a>
            <?php endif; ?>

            <h2>Détail de la déclaration</h2>
            <?php include_partial('sv12ByProduitsTypes',array('sv12ByProduitsTypes' => $sv12ByProduitsTypes)); ?>

            <h2> Détail des mouvements </h2>
            <?php include_partial('mouvements',array('mouvements' => $mouvements)); ?>
            
            <a class="btn_etape_prec" href="<?php echo url_for('sv12_etablissement', $sv12->getEtablissementObject()) ?>" class="btn_suiv">
                <span>Retour à mon espace</span>
            </a>    
        </section>
        <!-- fin #contenu_etape -->
    </section>
    
    <?php include_partial('colonne', array('negociant' => $sv12->declarant)); ?>
    <!-- fin #principal -->
</div>
    