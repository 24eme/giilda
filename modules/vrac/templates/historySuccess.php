<?php
use_helper('Vrac');
use_helper('Float');
?>
<section id="principal">
    <h2 class="titre_societe">
        Espace de <?php echo $societe->raison_sociale; ?>
    </h2>
        
   <form class="filtres_historique" id="filtres_historique" action="<?php echo url_for('vrac_history',array('identifiant' => $identifiant)); ?>" method="POST">
        <?php
		  echo $form->renderHiddenFields();
		  echo $form->renderGlobalErrors();
		?>

        <div class="campagne">
            <?php echo $form['campagne']->renderError(); ?>
			<?php echo $form['campagne']->renderLabel() ?>
			<?php echo $form['campagne']->render() ?>
        </div>
       <?php if(!$isOnlyOneEtb): ?>
            <div class="etablissement">       
                            <?php echo $form['etablissement']->renderError(); ?>
                            <?php echo $form['etablissement']->renderLabel() ?>
                            <?php echo $form['etablissement']->render() ?> 
            </div>
       <?php endif; ?>
        <div class="statut">       
			<?php echo $form['statut']->renderError(); ?>
			<?php echo $form['statut']->renderLabel() ?>
			<?php echo $form['statut']->render() ?> 
        </div>
    </form>

<div class="ligne_btn txt_droite">   
    
    <a class="btn_majeur btn_vert btn_excel" href="<?php echo url_for('vrac_history_exportCsv', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => $campagne, 'etablissement' => $etablissement, 'statut' => $statut)); ?>">
        Exporter en Tableur
    </a>
</div>
   <?php 
      include_partial('contratsTable', array('contrats' => $contratsByCampagneEtablissementAndStatut, 'societe' => $societe)); ?>    
   
</section>

<?php

include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour' => true));

?>