<?php
/* Fichier : _condition_prixvariable.php
 * Description : Fichier php correspondant à une vue partielle de vrac/XXXXXXXXXXX/condition
 * Formulaire concernant la parti prix variable pour les conditions du contrat
 * Affiché si et seulement si type de contrat = 'pluriannuel' et partie de prix variable = 'Oui'
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */

use_helper('Vrac');
use_helper('Float');
 ?>
<div id="prix_variable" <?php echo ($displayPrixVariable)? '' : 'style="display:none;"'; ?> class="section_label_maj">
    <label>Prix variable</label>
    <div class="bloc_form">
        <!--  Affichage des la part variable sur la quantité du contrat  -->
        <div id="part_variable" class="ligne_form ligne_form_alt">
                <span>
                    <?php echo $form['part_variable']->renderError() ?>
                    <?php echo $form['part_variable']->renderLabel() ?>
                    <?php echo $form['part_variable']->render() ?> <span>% (50% max)</span>
                </span>
        </div>
        <div id="prix_definitif_unitaire" class="ligne_form">
                <span>
                    <?php echo $form['prix_definitif_unitaire']->renderError() ?>
                    <?php echo $form['prix_definitif_unitaire']->renderLabel() ?>
                    <?php echo $form['prix_definitif_unitaire']->render() ?>
                </span>
        </div>
        
        <!--  Affichage du taux de variation des produits du contrat  -->
        <div id="prixTotal_rappel" class="ligne_form ligne_form_alt">
            <span>
                <label>Prix total non définitif</label>
                <?php echoFloat($form->getObject()->prix_unitaire); ?> €/<?php echo showUnite($form->getObject()); ?>
                <?php if( $form->getObject()->type_transaction == "vin_bouteille"){ echo "(soit ".sprintFloat($form->getObject()->prix_hl)." €/hl)"; } ?>      
            </span>
        </div>
    </div>
</div>
<div class="section_label_maj">
    <label>CVO appliquée</label>
    <div class="bloc_form">
        <!--  Affichage de la nature du contrat  -->
        <div id="cvo_nature" class="ligne_form" >
            <span>
                <?php echo $form['cvo_nature']->renderError() ?> 
                <?php echo $form['cvo_nature']->renderLabel() ?>
                <?php echo $form['cvo_nature']->render() ?>
            </span>   
        </div>

        <!--  Affichage de la repartition (vendeur/acheteur) pour le paiement de la CVO  -->
        <div id="taux_variation" class="ligne_form ligne_form_alt" >
            <span>
                <?php echo $form['cvo_repartition']->renderError() ?>
                <?php echo $form['cvo_repartition']->renderLabel() ?>
                <?php echo $form['cvo_repartition']->render() ?>
            </span>
        </div>
<?php
$taux = $form->getObject()->getDroitCVO()->taux;
$volume = $form->getObject()->volume_propose;
?>
        <!-- CVO facturée vendeur  -->
        <div id="cvo_facturee_vendeur" class="ligne_form" >
            <span>
                <label>CVO facturée (vendeur)</label>
                <span id="prix_facturee_vendeur"><?php printf("%0.2f", $taux / 2); ?></span>&nbsp;€/<?php echo showUnite($form->getObject()); ?>
                (soit <span  id="cvo_totale_vendeur"> <?php printf("%.02f", $taux * $volume / 2); ?></span>&nbsp;€)
            </span>
        </div>


        <!-- CVO facturée acheteur -->
        <div id="cvo_facturee_acheteur" class="ligne_form ligne_form_alt" >
            <span>
                <label>CVO facturée (acheteur)</label>
	        <span  id="prix_facturee_acheteur"><?php printf("%.02f", $taux / 2); ?></span>&nbsp;€/<?php echo showUnite($form->getObject()); ?>
		(soit <span  id="cvo_totale_acheteur"> <?php printf("%.02f", $taux * $volume / 2); ?></span>&nbsp;€)
            </span>
        </div>
    </div>
</div>
<script>
<!--
var cvo_taux = <?php echo $taux ; ?>;
var cvo_volume = <?php echo $volume; ?>;
$('#vrac_cvo_repartition').change(function() {
    switch($('#vrac_cvo_repartition').val())
      {
      case "100":
	$('#prix_facturee_vendeur').html(cvo_taux);
	$('#prix_facturee_acheteur').html("0.00");
	$('#cvo_totale_vendeur').html(cvo_taux * cvo_volume);
	$('#cvo_totale_acheteur').html("0.00");
	break;
      case "50":
	$('#prix_facturee_vendeur').html(cvo_taux / 2);
	$('#prix_facturee_acheteur').html(cvo_taux / 2);
	$('#cvo_totale_vendeur').html(cvo_taux * cvo_volume / 2);
	$('#cvo_totale_acheteur').html(cvo_taux * cvo_volume / 2);
	break;
      default:
	$('#prix_facturee_vendeur').html("0.00");
	$('#prix_facturee_acheteur').html("0.00");
	$('#cvo_totale_vendeur').html("0.00");
	$('#cvo_totale_acheteur').html("0.00");
	break;
      }
});
-->
</script>
