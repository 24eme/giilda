<div id="contenu" class="sv12">    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('sv12') ?>">Page d'accueil</a> &gt; <a href="<?php echo url_for('sv12_etablissement', $sv12->getEtablissementObject()) ?>"><?php echo $sv12->declarant->nom ?></a> &gt; <strong><?php echo $sv12 ?></strong></p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Déclaration SV12</h2>

            <?php include_partial('negociant_infos', array('sv12' => $sv12)); ?>

            <form name="sv12_recapitulatif" method="POST" action="<?php echo url_for('sv12_recapitulatif', $sv12); ?>" >

                <h2>Récapitulatif</h2>            
                <?php include_partial('totaux', array('sv12' => $sv12)); ?>

                <h2> Détail des mouvements </h2>
                <?php include_partial('mouvements', array('mouvements' => $mouvements)); ?>

				<div class="btn_etape">
					<a href="<?php echo url_for('sv12_update', $sv12); ?>" class="btn_etape_prec"><span>Précedent</span></a>                
					<a href="<?php echo url_for('sv12_etablissement', $sv12->getEtablissementObject()); ?>" class="btn_majeur btnModification">Enregistrer le brouillon</a>
					<button type="submit" class="btn_majeur btn_terminer_saisie btnValidation">Valider</button>
				</div>
            </form>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    <?php include_partial('colonne', array('negociant' => $sv12->declarant)); ?>
    <!-- fin #principal -->
</div>
