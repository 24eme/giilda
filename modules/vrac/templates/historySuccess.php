<?php
use_helper('Vrac');
use_helper('Float');
?>
<section id="principal">
    <h2 class="titre_societe">
        Espace de <?php echo $societe->raison_sociale; ?>
    </h2>
    <div>
        <div id="etablissement_<?php echo $societe->identifiant; ?>" class="">
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
        
       <form action="<?php echo url_for('vrac_history',array('identifiant' => $identifiant)); ?>" method="POST">
<?php
		echo $form->renderHiddenFields();
		echo $form->renderGlobalErrors();
		?>
	
		<ul>
                    <li id="date_debut" class="ligne_form ">                        
				<?php echo $form['campagne']->renderError(); ?>
				<?php echo $form['campagne']->renderLabel() ?>
				<?php echo $form['campagne']->render() ?>
                    </li>
                     <li id="date_fin" class="ligne_form ">       
				<?php echo $form['etablissement']->renderError(); ?>
				<?php echo $form['etablissement']->renderLabel() ?>
				<?php echo $form['etablissement']->render() ?> 
                     </li>
		</ul>
		
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