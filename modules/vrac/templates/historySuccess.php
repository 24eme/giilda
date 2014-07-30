<?php
use_helper('Vrac');
use_helper('Float');
?>
<section id="principal">
    <h2 class="titre_societe">
        Espace de <?php echo $societe->raison_sociale; ?>
    </h2>
    <div>
        <div id="etablissement_<?php echo $societe->identifiant; ?>" class="infos_etablissement">
            <a href="#" class="btn_majeur btn_excel">Exporter les contrats en CSV</a>
            
            <h3><?php echo $societe->raison_sociale; ?></h3>
    
            <ul id="liste_statuts_nb" class="">

            </ul>
            <div id="num_etb">
                N° <?php echo $societe->identifiant; ?>
            </div>
            <div id="cp_etb">
                Code postal: <?php echo $societe->siege->code_postal; ?>
            </div>
            <div id="commune_etb">
                Commune: <?php echo $societe->siege->commune; ?>
            </div>
        </div>
        
       <form class="filtres_historique" action="<?php echo url_for('vrac_history',array('identifiant' => $identifiant)); ?>" method="POST">
            <?php
    		  echo $form->renderHiddenFields();
    		  echo $form->renderGlobalErrors();
    		?>
	
            <div class="campagne">
                <?php echo $form['campagne']->renderError(); ?>
				<?php echo $form['campagne']->renderLabel() ?>
				<?php echo $form['campagne']->render() ?>
            </div>
            <div class="etablissement">       
				<?php echo $form['etablissement']->renderError(); ?>
				<?php echo $form['etablissement']->renderLabel() ?>
				<?php echo $form['etablissement']->render() ?> 
            </div>
		
    		<div class="btn_form">
    			<button type="submit" id="alerte_valid" class="btn_majeur btn_valider">Rechercher</button>
    		</div>
        </form>


    </div>
   <?php include_partial('teledeclarationActionsButtons', array('compte' => $compte, 'etablissementPrincipal' => $etablissementPrincipal, 'societe' => $societe)); ?>

   <?php 
      include_partial('contratsTable', array('contrats' => $contratsByEtablissementsAndCampagne, 'societe' => $societe)); ?>    
   <?php include_partial('teledeclarationActionsButtons', array('compte' => $compte, 'etablissementPrincipal' => $etablissementPrincipal, 'societe' => $societe)); ?>

</section>

<?php

include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));

?>