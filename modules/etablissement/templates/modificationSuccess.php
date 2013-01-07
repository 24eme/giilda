<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="<?php echo url_for('societe');?>">Page d'accueil</a> &gt; Contacts
            &gt; <a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>">
            <?php echo $societe->raison_sociale; ?></a> &gt;
                <strong>
                    <?php echo ($etablissement->isNew())? 'Nouvel établissement' : 'Modification établissement' ;?>
                </strong></p>

        <!-- #contenu_etape -->
        <section id="contacts">
            <div id="nouveau_etablissement">
                <h2><?php echo ($etablissement->isNew())? 'Nouvel établissement' : $etablissement->nom; ?></h2>
				
				
                <form action="<?php echo ($etablissement->isNew())? url_for('etablissement_ajout', array('identifiant' => $societe->identifiant)) : url_for('etablissement_modification', array('identifiant' => $etablissement->identifiant)); ?>" method="post">
				<div class="form_btn">
					<a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>" type="submit" class="btn_majeur btn_annuler">Annuler</a>
					<button id="btn_valider" type="submit" class="btn_majeur btn_valider">Valider</button>
				</div>
					
					<div id="detail_etablissement" class="etablissement form_section ouvert">
						<h3>Détail de l'établissement</h3>
						<?php include_partial('etablissement/modification', array('etablissementForm' => $etablissementModificationForm, 'etablissement' => $etablissement)); ?>
					</div>
                    <div id="coordonnees_etablissement" class="etablissement form_section ouvert">
                        <h3>Coordonnées de l'établissement</h3>
						
                        <div class="form_contenu">
                            <div class="form_ligne">
                                <?php echo $compteModificationForm['adresse_societe']->renderError(); ?>
                                <?php echo $compteModificationForm['adresse_societe']->renderLabel('Même adresse que la société ?', array('class' => 'label_liste')); ?>
                                <?php echo $compteModificationForm['adresse_societe']->render(); ?>
                            </div>
                            <?php include_partial('compte/modification', array('compteForm' => $compteModificationForm)); ?>
                        </div>
                    </div>  
				<div class="form_btn">
					<a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>" type="submit" class="btn_majeur btn_annuler">Annuler</a>
					<button id="btn_valider" type="submit" class="btn_majeur btn_valider">Valider</button>
				</div>
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
